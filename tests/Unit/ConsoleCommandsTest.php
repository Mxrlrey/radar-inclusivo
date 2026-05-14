<?php

namespace Tests\Unit;

use App\Console\Commands\BackupAutomatic;
use App\Console\Commands\CheckOverdueLoans;
use App\Console\Commands\SendInstitutionalEventReminders;
use App\Notifications\InstitutionalEventStartingNotification;
use App\Notifications\InstitutionalEventUpcomingNotification;
use App\Notifications\LoanOverdueNotification;
use App\Services\BackupService;
use Illuminate\Console\Command;
use Illuminate\Console\OutputStyle;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Mockery;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Tests\TestCase;

class ConsoleCommandsTest extends TestCase
{
    public function test_backup_automatic_generates_backup_and_writes_success_messages(): void
    {
        $backupService = Mockery::mock(BackupService::class);
        $backupService->shouldReceive('generate')->once();

        [$command, $output] = $this->commandWithOutput(new BackupAutomatic());

        $result = $command->handle($backupService);

        $this->assertSame(Command::SUCCESS, $result);
        $this->assertStringContainsString('Iniciando backup automático...', $output->fetch());
    }

    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
    public function test_check_overdue_loans_reports_when_there_are_no_overdue_loans(): void
    {
        $loans = Mockery::mock('alias:App\Models\Loan');
        $query = Mockery::mock();

        $loans->shouldReceive('where')
            ->once()
            ->with('status', \App\Enums\LoanStatus::ACTIVE)
            ->andReturn($query);
        $query->shouldReceive('where')
            ->once()
            ->with('due_date', '<=', Mockery::type(Carbon::class))
            ->andReturnSelf();
        $query->shouldReceive('get')
            ->once()
            ->andReturn(collect());

        [$command, $output] = $this->commandWithOutput(new CheckOverdueLoans());

        $command->handle();

        $this->assertStringContainsString('Nenhum empréstimo atrasado hoje.', $output->fetch());
    }

    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
    public function test_check_overdue_loans_notifies_all_users_for_each_overdue_loan(): void
    {
        Carbon::setTestNow('2026-05-14 09:00:00');

        $loans = Mockery::mock('alias:App\Models\Loan');
        $firstLoan = new \App\Models\Loan();
        $secondLoan = new \App\Models\Loan();
        $admin = Mockery::mock();
        $admin->shouldReceive('notify')
            ->twice()
            ->with(Mockery::type(LoanOverdueNotification::class));

        $query = Mockery::mock();
        $users = Mockery::mock('alias:App\Models\User');

        $loans->shouldReceive('where')
            ->once()
            ->with('status', \App\Enums\LoanStatus::ACTIVE)
            ->andReturn($query);
        $query->shouldReceive('where')
            ->once()
            ->with('due_date', '<=', Mockery::type(Carbon::class))
            ->andReturnSelf();
        $query->shouldReceive('get')
            ->once()
            ->andReturn(collect([$firstLoan, $secondLoan]));
        $users->shouldReceive('all')
            ->once()
            ->andReturn(collect([$admin]));

        [$command, $output] = $this->commandWithOutput(new CheckOverdueLoans());

        $command->handle();

        $this->assertStringContainsString('2 notificações de atraso enviadas.', $output->fetch());

        Carbon::setTestNow();
    }

    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
    public function test_event_reminders_report_when_no_events_are_found(): void
    {
        Carbon::setTestNow('2026-05-14 10:30:00');

        $events = Mockery::mock('alias:App\Models\InstitutionalEvent');

        $events->shouldReceive('where')
            ->twice()
            ->with('is_active', true)
            ->andReturnUsing(fn() => $this->eventQueryReturning(collect()));

        [$command, $output] = $this->commandWithOutput(new SendInstitutionalEventReminders());

        $command->handle();

        $text = $output->fetch();
        $this->assertStringContainsString('Nenhum evento amanhã.', $text);
        $this->assertStringContainsString('Nenhum evento iniciando agora.', $text);

        Carbon::setTestNow();
    }

    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
    public function test_event_reminders_skip_events_that_were_already_notified(): void
    {
        Carbon::setTestNow('2026-05-14 10:30:00');

        $events = Mockery::mock('alias:App\Models\InstitutionalEvent');
        $upcomingEvent = new \App\Models\InstitutionalEvent();
        $upcomingEvent->id = 10;
        $upcomingEvent->title = 'Evento Amanhã';
        $startingEvent = new \App\Models\InstitutionalEvent();
        $startingEvent->id = 11;
        $startingEvent->title = 'Evento Agora';
        $users = Mockery::mock('alias:App\Models\User');

        $events->shouldReceive('where')
            ->once()
            ->with('is_active', true)
            ->andReturnUsing(fn() => $this->eventQueryReturning(collect([$upcomingEvent])));
        $events->shouldReceive('where')
            ->once()
            ->with('is_active', true)
            ->andReturnUsing(fn() => $this->eventQueryReturning(collect([$startingEvent])));

        $users->shouldReceive('all')->twice()->andReturn(collect());
        DB::shouldReceive('table->where->where->exists')
            ->twice()
            ->andReturn(true);

        [$command, $output] = $this->commandWithOutput(new SendInstitutionalEventReminders());

        $command->handle();

        $text = $output->fetch();
        $this->assertStringContainsString('[Amanhã] Já notificado: Evento Amanhã', $text);
        $this->assertStringContainsString('[Iniciando] Já notificado: Evento Agora', $text);

        Carbon::setTestNow();
    }

    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
    public function test_event_reminders_notify_users_for_new_events(): void
    {
        Carbon::setTestNow('2026-05-14 10:30:00');

        $events = Mockery::mock('alias:App\Models\InstitutionalEvent');
        $upcomingEvent = new \App\Models\InstitutionalEvent();
        $upcomingEvent->id = 20;
        $upcomingEvent->title = 'Evento Futuro';
        $startingEvent = new \App\Models\InstitutionalEvent();
        $startingEvent->id = 21;
        $startingEvent->title = 'Evento Pontual';
        $user = Mockery::mock();
        $user->shouldReceive('notify')
            ->once()
            ->with(Mockery::type(InstitutionalEventUpcomingNotification::class));
        $user->shouldReceive('notify')
            ->once()
            ->with(Mockery::type(InstitutionalEventStartingNotification::class));

        $users = Mockery::mock('alias:App\Models\User');

        $events->shouldReceive('where')
            ->once()
            ->with('is_active', true)
            ->andReturnUsing(fn() => $this->eventQueryReturning(collect([$upcomingEvent])));
        $events->shouldReceive('where')
            ->once()
            ->with('is_active', true)
            ->andReturnUsing(fn() => $this->eventQueryReturning(collect([$startingEvent])));

        $users->shouldReceive('all')->twice()->andReturn(collect([$user]));
        DB::shouldReceive('table->where->where->exists')
            ->twice()
            ->andReturn(false);

        [$command, $output] = $this->commandWithOutput(new SendInstitutionalEventReminders());

        $command->handle();

        $text = $output->fetch();
        $this->assertStringContainsString('[Amanhã] Lembretes enviados: Evento Futuro', $text);
        $this->assertStringContainsString('[Iniciando] Notificações enviadas: Evento Pontual', $text);

        Carbon::setTestNow();
    }

    private function commandWithOutput(Command $command): array
    {
        $input = new ArrayInput([]);
        $output = new BufferedOutput();
        $command->setLaravel($this->app);
        $command->setInput($input);
        $command->setOutput(new OutputStyle($input, $output));

        return [$command, $output];
    }

    private function eventQueryReturning(Collection $events): object
    {
        $query = Mockery::mock();
        $query->shouldReceive('whereDate')->andReturnSelf();
        $query->shouldReceive('whereTime')->andReturnSelf();
        $query->shouldReceive('get')->once()->andReturn($events);

        return $query;
    }
}
