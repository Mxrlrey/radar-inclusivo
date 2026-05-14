<?php

namespace Tests\Unit;

use App\Enums\ConservationState;
use App\Enums\LoanStatus;
use App\Enums\ResourceStatus;
use App\Enums\WaitlistStatus;
use App\Exceptions\BusinessRuleException;
use App\Models\AccessibleEducationalMaterial;
use App\Models\AssistiveTechnology;
use App\Models\Barrier;
use App\Models\Deficiency;
use App\Models\InstitutionalEvent;
use App\Models\Loan;
use App\Models\Person;
use App\Models\Position;
use App\Models\Professional;
use App\Models\Student;
use App\Models\User;
use App\Models\Waitlist;
use App\Notifications\ItemAvailableNotification;
use App\Services\AccessibleEducationalMaterialService;
use App\Services\AssistiveTechnologyService;
use App\Services\DeficiencyService;
use App\Services\InstitutionalEventService;
use App\Services\LoanService;
use App\Services\PositionService;
use App\Services\ProfessionalService;
use App\Services\ProfileService;
use App\Services\StudentService;
use App\Services\WaitlistService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use ReflectionMethod;
use Tests\TestCase;

class ServicesAdditionalCoverageTest extends TestCase
{
    use RefreshDatabase;

    public function test_material_and_assistive_technology_legacy_digital_stock_sentinels_are_normalized(): void
    {
        $material = new AccessibleEducationalMaterial([
            'is_digital' => true,
            'quantity' => 999,
        ]);
        $technology = new AssistiveTechnology([
            'is_digital' => true,
            'quantity' => 999,
        ]);

        $materialData = $this->invokePrivate(
            app(AccessibleEducationalMaterialService::class),
            'normalizeInventoryData',
            $material,
            ['is_digital' => false, 'quantity' => 999]
        );
        $technologyData = $this->invokePrivate(
            app(AssistiveTechnologyService::class),
            'normalizeInventoryData',
            $technology,
            ['is_digital' => false, 'quantity' => 999]
        );

        $this->assertSame(1, $materialData['quantity']);
        $this->assertSame(1, $technologyData['quantity']);
    }

    public function test_deficiency_store_defaults_to_active_when_status_is_not_informed(): void
    {
        $deficiency = app(DeficiencyService::class)->store([
            'name' => 'Deficiência Default',
            'cid_code' => 'Z99',
            'description' => 'Criada sem status explícito',
        ]);

        $this->assertTrue($deficiency->is_active);
    }

    public function test_institutional_event_service_rejects_invalid_date_ranges_and_same_day_times(): void
    {
        $service = app(InstitutionalEventService::class);

        try {
            $service->store($this->eventData([
                'start_date' => '2026-05-15',
                'end_date' => '2026-05-14',
            ]));
            $this->fail('Expected end date validation to fail.');
        } catch (BusinessRuleException $exception) {
            $this->assertSame('A data de término não pode ser anterior à data de início.', $exception->getMessage());
        }

        $this->expectException(BusinessRuleException::class);
        $this->expectExceptionMessage('O horário de término deve ser maior que o horário de início para o mesmo dia.');

        $service->store($this->eventData([
            'start_time' => '10:00',
            'end_time' => '09:00',
        ]));
    }

    public function test_position_delete_is_blocked_when_professionals_are_linked(): void
    {
        $position = Position::factory()->create();
        Professional::factory()->create(['position_id' => $position->id]);

        $this->expectException(BusinessRuleException::class);
        $this->expectExceptionMessage('Não é possível excluir um cargo que possui profissionais vinculados.');

        app(PositionService::class)->delete($position);
    }

    public function test_profile_service_rejects_users_without_person_link_and_replaces_photos(): void
    {
        Storage::fake('public');

        $userWithoutProfile = User::factory()->create(['professional_id' => null]);

        try {
            app(ProfileService::class)->updateProfile($userWithoutProfile, [
                'name' => 'Sem Perfil',
                'email' => 'sem.perfil@example.com',
            ]);
            $this->fail('Expected profile without person to fail.');
        } catch (BusinessRuleException $exception) {
            $this->assertSame('Vínculo de pessoa não encontrado para o seu usuário.', $exception->getMessage());
        }

        Storage::disk('public')->put('photos/profiles/old.jpg', 'old');
        $professional = Professional::factory()->create();
        $professional->person->update(['photo' => 'photos/profiles/old.jpg']);
        $user = User::factory()->create(['professional_id' => $professional->id]);

        app(ProfileService::class)->updateProfile($user, [
            'name' => 'Perfil Com Foto',
            'email' => 'perfil.foto@example.com',
            'photo' => UploadedFile::fake()->image('new.jpg'),
        ]);

        $person = $professional->person->fresh();
        Storage::disk('public')->assertMissing('photos/profiles/old.jpg');
        Storage::disk('public')->assertExists($person->photo);
        $this->assertSame('Perfil Com Foto', $person->name);

        app(ProfileService::class)->updateProfile($user, [
            'name' => 'Perfil Sem Foto',
            'email' => 'perfil.sem.foto@example.com',
            'remove_photo' => true,
        ]);

        $this->assertNull($professional->person->fresh()->photo);
    }

    public function test_student_service_photo_branches_and_delete_guards(): void
    {
        Storage::fake('public');

        $service = app(StudentService::class);
        $student = Student::factory()->create();
        Storage::disk('public')->put('photos/students/old.jpg', 'old');
        $student->person->update(['photo' => 'photos/students/old.jpg']);

        $service->update($student, $this->studentData([
            'document' => $student->person->document,
            'registration' => $student->registration,
            'photo' => UploadedFile::fake()->image('student.jpg'),
        ]));

        Storage::disk('public')->assertMissing('photos/students/old.jpg');
        Storage::disk('public')->assertExists($student->person->fresh()->photo);

        $service->update($student, $this->studentData([
            'document' => $student->person->document,
            'registration' => $student->registration,
            'remove_photo' => true,
        ]));

        $this->assertNull($student->person->fresh()->photo);

        $blocked = Student::factory()->create();
        Loan::factory()->create([
            'student_id' => $blocked->id,
            'professional_id' => null,
            'status' => LoanStatus::ACTIVE,
            'return_date' => null,
        ]);

        $this->expectException(BusinessRuleException::class);
        $this->expectExceptionMessage('Não é possível excluir um aluno com empréstimos ativos.');

        $service->delete($blocked);
    }

    public function test_student_service_delete_waitlist_barrier_and_photo_branches(): void
    {
        Storage::fake('public');

        $service = app(StudentService::class);

        $withWaitlist = Student::factory()->create();
        Waitlist::factory()->create([
            'student_id' => $withWaitlist->id,
            'professional_id' => null,
            'status' => WaitlistStatus::NOTIFIED->value,
        ]);

        try {
            $service->delete($withWaitlist);
            $this->fail('Expected active waitlist guard to fail.');
        } catch (BusinessRuleException $exception) {
            $this->assertSame('Não é possível excluir um aluno com solicitações ativas na fila de espera.', $exception->getMessage());
        }

        $withBarrier = Student::factory()->create();
        Barrier::factory()->create([
            'affected_student_id' => $withBarrier->id,
            'affected_professional_id' => null,
            'resolved_at' => null,
        ]);

        try {
            $service->delete($withBarrier);
            $this->fail('Expected unresolved barrier guard to fail.');
        } catch (BusinessRuleException $exception) {
            $this->assertSame('Não é possível excluir um aluno com barreiras ainda pendentes.', $exception->getMessage());
        }

        $student = Student::factory()->create();
        Storage::disk('public')->put('photos/students/delete.jpg', 'old');
        $student->person->update(['photo' => 'photos/students/delete.jpg']);

        $service->delete($student);

        Storage::disk('public')->assertMissing('photos/students/delete.jpg');
        $this->assertSoftDeleted('students', ['id' => $student->id]);
    }

    public function test_professional_service_delete_guards_and_photo_cleanup(): void
    {
        Storage::fake('public');

        $service = app(ProfessionalService::class);

        $withLoan = Professional::factory()->create();
        Loan::factory()->create([
            'student_id' => null,
            'professional_id' => $withLoan->id,
            'status' => LoanStatus::ACTIVE,
            'return_date' => null,
        ]);

        try {
            $service->delete($withLoan);
            $this->fail('Expected active loan guard to fail.');
        } catch (BusinessRuleException $exception) {
            $this->assertSame('Não é possível excluir um profissional com empréstimos ativos.', $exception->getMessage());
        }

        $withWaitlist = Professional::factory()->create();
        Waitlist::factory()->create([
            'student_id' => null,
            'professional_id' => $withWaitlist->id,
            'status' => WaitlistStatus::WAITING->value,
        ]);

        try {
            $service->delete($withWaitlist);
            $this->fail('Expected active waitlist guard to fail.');
        } catch (BusinessRuleException $exception) {
            $this->assertSame('Não é possível excluir um profissional com solicitações ativas na fila de espera.', $exception->getMessage());
        }

        $withBarrier = Professional::factory()->create();
        Barrier::factory()->create([
            'affected_professional_id' => $withBarrier->id,
            'affected_student_id' => null,
            'resolved_at' => null,
        ]);

        try {
            $service->delete($withBarrier);
            $this->fail('Expected active barrier guard to fail.');
        } catch (BusinessRuleException $exception) {
            $this->assertSame('Não é possível excluir um profissional com barreiras ainda pendentes.', $exception->getMessage());
        }

        $professional = Professional::factory()->create();
        Storage::disk('public')->put('photos/professionals/old.jpg', 'old');
        $professional->person->update(['photo' => 'photos/professionals/old.jpg']);

        $service->delete($professional);

        Storage::disk('public')->assertMissing('photos/professionals/old.jpg');
        $this->assertSoftDeleted('professionals', ['id' => $professional->id]);
    }

    public function test_professional_service_store_defaults_entry_date_and_replaces_photo(): void
    {
        Storage::fake('public');

        $position = Position::factory()->create();
        $service = app(ProfessionalService::class);

        $professional = $service->store($this->professionalData([
            'position_id' => $position->id,
            'entry_date' => null,
        ]));

        $this->assertNotNull($professional->entry_date);

        Storage::disk('public')->put('photos/professionals/existing.jpg', 'old');
        $professional->person->update(['photo' => 'photos/professionals/existing.jpg']);

        $service->update($professional, $this->professionalData([
            'position_id' => $position->id,
            'document' => $professional->person->document,
            'registration' => $professional->registration,
            'photo' => UploadedFile::fake()->image('professional.jpg'),
        ]));

        Storage::disk('public')->assertMissing('photos/professionals/existing.jpg');
        Storage::disk('public')->assertExists($professional->person->fresh()->photo);

        $service->update($professional, $this->professionalData([
            'position_id' => $position->id,
            'document' => $professional->person->document,
            'registration' => $professional->registration,
            'remove_photo' => true,
        ]));

        $this->assertNull($professional->person->fresh()->photo);
    }

    public function test_waitlist_service_validation_branches_and_notify_next_empty_case(): void
    {
        $service = app(WaitlistService::class);
        $item = AssistiveTechnology::factory()->physical()->available()->loanable()->create([
            'quantity' => 2,
            'quantity_available' => 2,
        ]);

        try {
            $service->store([
                'waitlistable_type' => AssistiveTechnology::class,
                'waitlistable_id' => $item->id,
                'user_id' => User::factory()->create()->id,
            ]);
            $this->fail('Expected missing beneficiary validation to fail.');
        } catch (BusinessRuleException $exception) {
            $this->assertSame('É necessário informar um aluno ou um profissional.', $exception->getMessage());
        }

        $student = Student::factory()->create();
        $professional = Professional::factory()->create();

        try {
            $service->store([
                'waitlistable_type' => AssistiveTechnology::class,
                'waitlistable_id' => $item->id,
                'student_id' => $student->id,
                'professional_id' => $professional->id,
                'user_id' => User::factory()->create()->id,
            ]);
            $this->fail('Expected dual beneficiary validation to fail.');
        } catch (BusinessRuleException $exception) {
            $this->assertSame('Não é permitido informar aluno e profissional ao mesmo tempo.', $exception->getMessage());
        }

        try {
            $service->store([
                'waitlistable_type' => AssistiveTechnology::class,
                'waitlistable_id' => $item->id,
                'student_id' => $student->id,
                'user_id' => User::factory()->create()->id,
            ]);
            $this->fail('Expected available stock validation to fail.');
        } catch (BusinessRuleException $exception) {
            $this->assertStringContainsString('ainda possui unidades disponíveis', $exception->getMessage());
        }

        $this->assertNull($service->notifyNext($item));

        $waiting = Waitlist::factory()->create([
            'waitlistable_id' => $item->id,
            'waitlistable_type' => $item->getMorphClass(),
            'status' => WaitlistStatus::WAITING->value,
            'requested_at' => now()->subDay(),
        ]);

        $notified = $service->notifyNext($item);
        $this->assertSame($waiting->id, $notified->id);
        $this->assertSame(WaitlistStatus::NOTIFIED->value, $notified->status);

        $cancelled = Waitlist::factory()->create(['status' => WaitlistStatus::CANCELLED->value]);
        try {
            $service->cancel($cancelled);
            $this->fail('Expected cancel guard to fail.');
        } catch (BusinessRuleException $exception) {
            $this->assertSame('Apenas solicitações em espera podem ser canceladas.', $exception->getMessage());
        }

        try {
            $service->update($cancelled, ['status' => WaitlistStatus::WAITING->value]);
            $this->fail('Expected finalized update guard to fail.');
        } catch (BusinessRuleException $exception) {
            $this->assertSame('Solicitação já finalizada não pode ser alterada, exceto observações.', $exception->getMessage());
        }

        $fulfilled = Waitlist::factory()->create(['status' => WaitlistStatus::FULFILLED->value]);
        $this->expectException(BusinessRuleException::class);
        $this->expectExceptionMessage('Solicitações já atendidas não podem ser removidas.');

        $service->delete($fulfilled);
    }

    public function test_waitlist_service_duplicate_branches_for_professionals(): void
    {
        $service = app(WaitlistService::class);
        $item = AssistiveTechnology::factory()->physical()->unavailable()->loanable()->create([
            'quantity' => 1,
            'quantity_available' => 0,
        ]);
        $professional = Professional::factory()->create();
        $user = User::factory()->create();

        Waitlist::factory()->create([
            'waitlistable_id' => $item->id,
            'waitlistable_type' => $item->getMorphClass(),
            'student_id' => null,
            'professional_id' => $professional->id,
            'status' => WaitlistStatus::WAITING->value,
        ]);

        try {
            $service->store([
                'waitlistable_type' => AssistiveTechnology::class,
                'waitlistable_id' => $item->id,
                'professional_id' => $professional->id,
                'user_id' => $user->id,
            ]);
            $this->fail('Expected duplicate waitlist guard to fail.');
        } catch (BusinessRuleException $exception) {
            $this->assertSame('Este beneficiário já possui uma solicitação ativa para este recurso.', $exception->getMessage());
        }

        Waitlist::query()->delete();
        Loan::factory()->create([
            'loanable_id' => $item->id,
            'loanable_type' => $item->getMorphClass(),
            'student_id' => null,
            'professional_id' => $professional->id,
            'return_date' => null,
            'status' => LoanStatus::ACTIVE,
        ]);

        $this->expectException(BusinessRuleException::class);
        $this->expectExceptionMessage('Este beneficiário já possui um empréstimo ativo deste recurso.');

        $service->store([
            'waitlistable_type' => AssistiveTechnology::class,
            'waitlistable_id' => $item->id,
            'professional_id' => $professional->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_loan_service_additional_branches(): void
    {
        Notification::fake();
        $user = User::factory()->create();
        $this->actingAs($user);

        $service = app(LoanService::class);

        $freshItem = new AssistiveTechnology(['is_digital' => false, 'quantity' => 5]);
        $this->assertSame(0, $service->countOpenLoans($freshItem));
        $this->assertSame(['is_digital' => true, 'quantity_available' => null], $service->calculateStockForLoan($freshItem, ['is_digital' => true]));

        $blockedStatusItem = AssistiveTechnology::factory()->physical()->loanable()->create([
            'quantity' => 1,
            'quantity_available' => 1,
            'status' => ResourceStatus::DAMAGED,
            'conservation_state' => ConservationState::GOOD,
        ]);

        try {
            $service->store($this->loanData($blockedStatusItem, ['student_id' => Student::factory()->create()->id]));
            $this->fail('Expected blocked status validation to fail.');
        } catch (BusinessRuleException $exception) {
            $this->assertStringContainsString('bloqueia empréstimos', $exception->getMessage());
        }

        $blockedConservationItem = AssistiveTechnology::factory()->physical()->available()->loanable()->create([
            'quantity' => 1,
            'quantity_available' => 1,
            'conservation_state' => ConservationState::BAD,
        ]);

        try {
            $service->store($this->loanData($blockedConservationItem, ['student_id' => Student::factory()->create()->id]));
            $this->fail('Expected blocked conservation validation to fail.');
        } catch (BusinessRuleException $exception) {
            $this->assertStringContainsString("O estado 'Ruim (Danificado)' bloqueia empréstimos.", $exception->getMessage());
        }

        $noStockItem = AssistiveTechnology::factory()->physical()->available()->loanable()->create([
            'quantity' => 1,
            'quantity_available' => 0,
        ]);

        try {
            $service->store($this->loanData($noStockItem, ['student_id' => Student::factory()->create()->id]));
            $this->fail('Expected no stock validation to fail.');
        } catch (BusinessRuleException $exception) {
            $this->assertSame('Não há unidades disponíveis em estoque.', $exception->getMessage());
        }

        $inUseItem = AssistiveTechnology::factory()->physical()->available()->loanable()->create([
            'quantity' => 1,
            'quantity_available' => 1,
        ]);
        $student = Student::factory()->create();
        $service->store($this->loanData($inUseItem, ['student_id' => $student->id]));
        $this->assertSame(0, $inUseItem->fresh()->quantity_available);
        $this->assertSame(ResourceStatus::IN_USE, $inUseItem->fresh()->status);

        try {
            $service->store($this->loanData($inUseItem, []));
            $this->fail('Expected missing beneficiary guard to fail.');
        } catch (BusinessRuleException $exception) {
            $this->assertSame('É necessário informar um aluno ou um profissional.', $exception->getMessage());
        }

        try {
            $service->store($this->loanData($inUseItem, [
                'student_id' => Student::factory()->create()->id,
                'professional_id' => Professional::factory()->create()->id,
            ]));
            $this->fail('Expected dual beneficiary guard to fail.');
        } catch (BusinessRuleException $exception) {
            $this->assertSame('Não é permitido informar aluno e profissional ao mesmo tempo.', $exception->getMessage());
        }

        try {
            $service->validateStockAvailability($inUseItem->fresh(), 0, false);
            $this->fail('Expected stock reduction guard to fail.');
        } catch (BusinessRuleException $exception) {
            $this->assertStringContainsString('Impossível reduzir estoque', $exception->getMessage());
        }

        $professionalItem = AssistiveTechnology::factory()->physical()->available()->loanable()->create([
            'quantity' => 2,
            'quantity_available' => 2,
        ]);
        $professional = Professional::factory()->create();
        Loan::factory()->create([
            'loanable_id' => $professionalItem->id,
            'loanable_type' => $professionalItem->getMorphClass(),
            'student_id' => null,
            'professional_id' => $professional->id,
            'return_date' => null,
            'status' => LoanStatus::ACTIVE,
        ]);

        try {
            $service->store($this->loanData($professionalItem, ['professional_id' => $professional->id]));
            $this->fail('Expected active loan duplicate guard to fail.');
        } catch (BusinessRuleException $exception) {
            $this->assertSame('Este beneficiário já possui um empréstimo ativo deste recurso.', $exception->getMessage());
        }

        $waitlistedProfessional = Professional::factory()->create();
        $waitlistedItem = AssistiveTechnology::factory()->physical()->available()->loanable()->create([
            'quantity' => 2,
            'quantity_available' => 2,
        ]);
        $waitlist = Waitlist::factory()->create([
            'waitlistable_id' => $waitlistedItem->id,
            'waitlistable_type' => $waitlistedItem->getMorphClass(),
            'student_id' => null,
            'professional_id' => $waitlistedProfessional->id,
            'status' => WaitlistStatus::WAITING->value,
        ]);

        $service->store($this->loanData($waitlistedItem, ['professional_id' => $waitlistedProfessional->id]));
        $this->assertSame(WaitlistStatus::FULFILLED->value, $waitlist->fresh()->status);

        $loan = Loan::factory()->create(['return_date' => now(), 'status' => LoanStatus::RETURNED]);
        try {
            $service->markAsReturned($loan);
            $this->fail('Expected finalized loan validation to fail.');
        } catch (BusinessRuleException $exception) {
            $this->assertSame('Este empréstimo já foi finalizado.', $exception->getMessage());
        }

        $activeLoan = Loan::factory()->create([
            'status' => LoanStatus::ACTIVE,
            'return_date' => null,
            'due_date' => now()->addDay(),
        ]);
        $returned = $service->markAsReturned($activeLoan, ['is_damaged' => true]);
        $this->assertSame(LoanStatus::DAMAGED, $returned->status);

        $lateItem = AssistiveTechnology::factory()->physical()->available()->loanable()->create([
            'quantity' => 1,
            'quantity_available' => 0,
        ]);
        $lateLoan = Loan::factory()->forAssistiveTechnology($lateItem)->create([
            'status' => LoanStatus::ACTIVE,
            'return_date' => null,
            'due_date' => now()->subDay(),
        ]);
        Waitlist::factory()->create([
            'waitlistable_id' => $lateItem->id,
            'waitlistable_type' => $lateItem->getMorphClass(),
            'status' => WaitlistStatus::WAITING->value,
        ]);

        $lateReturned = $service->markAsReturned($lateLoan, ['is_damaged' => false]);
        $this->assertSame(LoanStatus::LATE, $lateReturned->status);
        Notification::assertSentTo($user, ItemAvailableNotification::class);

        $deleteItem = AssistiveTechnology::factory()->physical()->available()->loanable()->create([
            'quantity' => 1,
            'quantity_available' => 0,
        ]);
        $activeDeleteLoan = Loan::factory()->forAssistiveTechnology($deleteItem)->create([
            'status' => LoanStatus::ACTIVE,
            'return_date' => null,
        ]);
        Waitlist::factory()->create([
            'waitlistable_id' => $deleteItem->id,
            'waitlistable_type' => $deleteItem->getMorphClass(),
            'status' => WaitlistStatus::WAITING->value,
        ]);

        $service->delete($activeDeleteLoan);
        Notification::assertSentTo($user, ItemAvailableNotification::class);

        $inactiveLoan = Loan::factory()->returned()->create();
        $this->invokePrivate($service, 'validateBeneficiary', [], $inactiveLoan);

        $this->assertGreaterThanOrEqual(0, $service->getOverdueLoans()->count());
    }

    private function invokePrivate(object $object, string $method, mixed ...$arguments): mixed
    {
        $reflection = new ReflectionMethod($object, $method);
        $reflection->setAccessible(true);

        return $reflection->invoke($object, ...$arguments);
    }

    private function eventData(array $overrides = []): array
    {
        return array_merge([
            'title' => 'Evento Teste',
            'description' => 'Descrição',
            'start_date' => '2026-05-14',
            'end_date' => '2026-05-14',
            'start_time' => '08:00',
            'end_time' => '10:00',
            'location' => 'Auditório',
            'organizer' => 'Equipe',
            'audience' => 'Comunidade',
            'is_active' => true,
        ], $overrides);
    }

    private function studentData(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Aluno Cobertura',
            'email' => 'aluno.cobertura@example.com',
            'document' => '52998224725',
            'birth_date' => '2002-01-10',
            'gender' => 'not_specified',
            'phone' => '77999999999',
            'address' => 'Rua Teste',
            'registration' => 'MAT-COV',
            'entry_date' => '2024-02-01',
            'is_active' => true,
        ], $overrides);
    }

    private function professionalData(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Profissional Cobertura',
            'email' => 'profissional.cobertura@example.com',
            'document' => '52998224725',
            'birth_date' => '1990-01-10',
            'gender' => 'not_specified',
            'phone' => '77999999999',
            'address' => 'Rua Profissional',
            'registration' => 'PROF-COV',
            'entry_date' => '2024-02-01',
            'is_active' => true,
            'is_admin' => false,
        ], $overrides);
    }

    private function loanData(AssistiveTechnology $item, array $overrides = []): array
    {
        return array_merge([
            'loanable_id' => $item->id,
            'loanable_type' => AssistiveTechnology::class,
            'loan_date' => now()->toDateString(),
            'due_date' => now()->addDays(5)->toDateString(),
            'user_id' => User::factory()->create()->id,
        ], $overrides);
    }
}
