<?php

namespace Tests\Unit;

use App\Models\AccessibleEducationalMaterial;
use App\Models\AssistiveTechnology;
use App\Models\InstitutionalEvent;
use App\Models\Loan;
use App\Models\Person;
use App\Models\Professional;
use App\Models\Student;
use App\Models\Waitlist;
use App\Notifications\InstitutionalEventStartingNotification;
use App\Notifications\InstitutionalEventUpcomingNotification;
use App\Notifications\ItemAvailableNotification;
use App\Notifications\LoanOverdueNotification;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class NotificationsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(Carbon::parse('2026-05-14 10:30:00'));
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_item_available_notification_for_student(): void
    {
        $waitlist = $this->makeWaitlist(
            beneficiaryRelation: 'student',
            beneficiaryName: 'Maria Silva',
            item: $this->makeModel(AssistiveTechnology::class, 15, ['name' => 'Leitor de Tela'])
        );

        $notification = new ItemAvailableNotification($waitlist);

        $this->assertSame(['database'], $notification->via(null));

        $data = $notification->toDatabase(null);

        $this->assertSame(5, $data['waitlist_id']);
        $this->assertSame('Próximo da fila disponível', $data['title']);
        $this->assertSame(
            "O item 'Leitor de Tela' está disponível para o beneficiário: Maria Silva. Realize o empréstimo.",
            $data['message']
        );
        $this->assertStringContainsString(route('emprestimos.criar', [], false), $data['url']);
        $this->assertStringContainsString('item_id=15', $data['url']);
        $this->assertStringContainsString('student_id=7', $data['url']);
        $this->assertSame('2026-05-14 10:30:00', $data['created_at']);
    }

    public function test_item_available_notification_for_professional_and_fallback_item(): void
    {
        $waitlist = $this->makeWaitlist(
            beneficiaryRelation: 'professional',
            beneficiaryName: 'João Santos',
            item: null
        );
        $waitlist->forceFill([
            'waitlistable_id' => 99,
            'waitlistable_type' => AccessibleEducationalMaterial::class,
        ]);

        $data = (new ItemAvailableNotification($waitlist))->toDatabase(null);

        $this->assertSame(
            "O item 'Recurso' está disponível para o beneficiário: João Santos. Realize o empréstimo.",
            $data['message']
        );
        $this->assertStringContainsString('professional_id=8', $data['url']);
    }

    public function test_item_available_notification_uses_unknown_beneficiary_fallback(): void
    {
        $waitlist = $this->makeWaitlist(
            beneficiaryRelation: null,
            beneficiaryName: null,
            item: $this->makeModel(AssistiveTechnology::class, 12, ['name' => 'Mouse Adaptado'])
        );

        $data = (new ItemAvailableNotification($waitlist))->toDatabase(null);

        $this->assertSame(
            "O item 'Mouse Adaptado' está disponível para o beneficiário: Beneficiário desconhecido. Realize o empréstimo.",
            $data['message']
        );
    }

    public function test_loan_overdue_notification_for_student(): void
    {
        $loan = $this->makeLoan(
            beneficiaryRelation: 'student',
            beneficiaryName: 'Ana Lima',
            item: $this->makeModel(AssistiveTechnology::class, 22, ['name' => 'Teclado Ampliado']),
            dueDate: now()->subDays(4)
        );

        $notification = new LoanOverdueNotification($loan);

        $this->assertSame(['database'], $notification->via(null));

        $data = $notification->toDatabase(null);

        $this->assertSame(11, $data['loan_id']);
        $this->assertSame('Empréstimo Atrasado', $data['title']);
        $this->assertSame(
            "O item 'Teclado Ampliado' está com o beneficiário Ana Lima e encontra-se atrasado há 4 dia(s).",
            $data['message']
        );
        $this->assertSame(route('emprestimos.visualizar', 11), $data['url']);
        $this->assertSame('2026-05-14 10:30:00', $data['created_at']);
    }

    public function test_loan_overdue_notification_for_professional_and_fallbacks(): void
    {
        $loan = $this->makeLoan(
            beneficiaryRelation: 'professional',
            beneficiaryName: 'Carlos Souza',
            item: null,
            dueDate: now()->subDays(2)
        );

        $data = (new LoanOverdueNotification($loan))->toDatabase(null);

        $this->assertSame(
            "O item 'Item' está com o beneficiário Carlos Souza e encontra-se atrasado há 2 dia(s).",
            $data['message']
        );
    }

    public function test_loan_overdue_notification_uses_na_for_missing_beneficiary(): void
    {
        $loan = $this->makeLoan(
            beneficiaryRelation: null,
            beneficiaryName: null,
            item: $this->makeModel(AccessibleEducationalMaterial::class, 31, ['name' => 'Livro em Braille']),
            dueDate: now()->subDay()
        );

        $data = (new LoanOverdueNotification($loan))->toDatabase(null);

        $this->assertSame(
            "O item 'Livro em Braille' está com o beneficiário N/A e encontra-se atrasado há 1 dia(s).",
            $data['message']
        );
    }

    public function test_institutional_event_starting_notification(): void
    {
        $event = $this->makeModel(InstitutionalEvent::class, 44, [
            'title' => 'Semana de Inclusão',
            'location' => 'Auditório Central',
        ]);

        $notification = new InstitutionalEventStartingNotification($event);

        $this->assertSame(['database'], $notification->via(null));

        $data = $notification->toDatabase(null);

        $this->assertSame(44, $data['event_id']);
        $this->assertSame('Evento Iniciando Agora', $data['title']);
        $this->assertSame(
            'O evento "Semana de Inclusão" está começando agora! Local: Auditório Central.',
            $data['message']
        );
        $this->assertSame(route('agenda-institucional.visualizar', 44), $data['url']);
        $this->assertSame('2026-05-14 10:30:00', $data['created_at']);
    }

    public function test_institutional_event_upcoming_notification(): void
    {
        $event = $this->makeModel(InstitutionalEvent::class, 45, [
            'title' => 'Oficina de Libras',
            'start_date' => '2026-05-15',
            'start_time' => '14:45',
            'location' => 'Sala 2',
        ]);

        $notification = new InstitutionalEventUpcomingNotification($event);

        $this->assertSame(['database'], $notification->via(null));

        $data = $notification->toDatabase(null);

        $this->assertSame(45, $data['event_id']);
        $this->assertSame('Lembrete de Evento', $data['title']);
        $this->assertSame(
            'O evento "Oficina de Libras" acontece amanhã, 15/05/2026 às 14:45. Local: Sala 2.',
            $data['message']
        );
        $this->assertSame(route('agenda-institucional.visualizar', 45), $data['url']);
        $this->assertSame('2026-05-14 10:30:00', $data['created_at']);
    }

    private function makeWaitlist(?string $beneficiaryRelation, ?string $beneficiaryName, ?object $item): Waitlist
    {
        $waitlist = new Waitlist([
            'waitlistable_id' => $item?->id,
            'waitlistable_type' => $item ? get_class($item) : null,
            'student_id' => $beneficiaryRelation === 'student' ? 7 : null,
            'professional_id' => $beneficiaryRelation === 'professional' ? 8 : null,
        ]);
        $waitlist->forceFill(['id' => 5]);

        $waitlist->setRelation('waitlistable', $item);
        $waitlist->setRelation('student', $beneficiaryRelation === 'student' ? $this->makeStudent($beneficiaryName) : null);
        $waitlist->setRelation('professional', $beneficiaryRelation === 'professional' ? $this->makeProfessional($beneficiaryName) : null);

        return $waitlist;
    }

    private function makeLoan(?string $beneficiaryRelation, ?string $beneficiaryName, ?object $item, Carbon $dueDate): Loan
    {
        $loan = new Loan([
            'loanable_id' => $item?->id,
            'loanable_type' => $item ? get_class($item) : null,
            'student_id' => $beneficiaryRelation === 'student' ? 7 : null,
            'professional_id' => $beneficiaryRelation === 'professional' ? 8 : null,
            'due_date' => $dueDate,
        ]);
        $loan->forceFill(['id' => 11]);

        $loan->setRelation('loanable', $item);
        $loan->setRelation('student', $beneficiaryRelation === 'student' ? $this->makeStudent($beneficiaryName) : null);
        $loan->setRelation('professional', $beneficiaryRelation === 'professional' ? $this->makeProfessional($beneficiaryName) : null);

        return $loan;
    }

    private function makeStudent(?string $name): Student
    {
        $student = new Student();
        $student->forceFill(['id' => 7]);
        $student->setRelation('person', new Person(['name' => $name]));

        return $student;
    }

    private function makeProfessional(?string $name): Professional
    {
        $professional = new Professional();
        $professional->forceFill(['id' => 8]);
        $professional->setRelation('person', new Person(['name' => $name]));

        return $professional;
    }

    private function makeModel(string $class, int $id, array $attributes): object
    {
        $model = new $class($attributes);
        $model->forceFill(['id' => $id]);

        return $model;
    }
}
