<?php

namespace Tests\Unit;

use App\Enums\BarrierStatus;
use App\Enums\ConservationState;
use App\Enums\Gender;
use App\Enums\InspectionType;
use App\Enums\LoanStatus;
use App\Enums\Priority;
use App\Enums\ResourceStatus;
use App\Enums\WaitlistStatus;
use Tests\TestCase;

class EnumsTest extends TestCase
{
    public function test_barrier_status_labels_colors_and_deletion_rules(): void
    {
        $expectations = [
            BarrierStatus::IDENTIFIED->value => ['Identificada', 'secondary', false],
            BarrierStatus::UNDER_ANALYSIS->value => ['Em Análise', 'info', false],
            BarrierStatus::IN_PROGRESS->value => ['Em Tratamento', 'warning', false],
            BarrierStatus::RESOLVED->value => ['Resolvida', 'success', true],
            BarrierStatus::NOT_APPLICABLE->value => ['Não Aplicável', 'danger', true],
        ];

        foreach (BarrierStatus::cases() as $status) {
            [$label, $color, $allowsDeletion] = $expectations[$status->value];

            $this->assertSame($label, $status->label());
            $this->assertSame($color, $status->color());
            $this->assertSame($allowsDeletion, $status->allowsDeletion());
        }
    }

    public function test_conservation_state_labels_colors_and_usage_rules(): void
    {
        $expectations = [
            ConservationState::NEW->value => ['Novo', 'success', false, false, true],
            ConservationState::GOOD->value => ['Bom (Sinais de uso)', 'primary', false, false, true],
            ConservationState::REGULAR->value => ['Regular (Avarias leves)', 'warning', false, false, true],
            ConservationState::BAD->value => ['Ruim (Danificado)', 'danger', true, true, false],
            ConservationState::NOT_APPLICABLE->value => ['Não se aplica', 'secondary', false, false, false],
        ];

        foreach (ConservationState::cases() as $state) {
            [$label, $color, $blocksLoan, $requiresMaintenance, $isUsable] = $expectations[$state->value];

            $this->assertSame($label, $state->label());
            $this->assertSame($color, $state->color());
            $this->assertSame($blocksLoan, $state->blocksLoan());
            $this->assertSame($requiresMaintenance, $state->requiresMaintenance());
            $this->assertSame($isUsable, $state->isUsable());
        }
    }

    public function test_gender_labels_and_helper_methods(): void
    {
        $labels = [
            Gender::MALE->value => 'Masculino',
            Gender::FEMALE->value => 'Feminino',
            Gender::OTHER->value => 'Outro',
            Gender::NOT_SPECIFIED->value => 'Não Informado',
        ];

        foreach (Gender::cases() as $gender) {
            $this->assertSame($labels[$gender->value], $gender->label());
        }

        $this->assertSame($labels, Gender::options());
        $this->assertSame(array_keys($labels), Gender::values());
    }

    public function test_inspection_type_labels(): void
    {
        $labels = [
            InspectionType::INITIAL->value => 'Vistoria Inicial',
            InspectionType::PERIODIC->value => 'Vistoria Periódica',
            InspectionType::RETURN->value => 'Retorno de Empréstimo',
            InspectionType::MAINTENANCE->value => 'Manutenção',
        ];

        foreach (InspectionType::cases() as $type) {
            $this->assertSame($labels[$type->value], $type->label());
        }
    }

    public function test_loan_status_labels_colors_and_state_rules(): void
    {
        $expectations = [
            LoanStatus::ACTIVE->value => ['Ativo (Com o Beneficiário)', 'success', true, false, false],
            LoanStatus::RETURNED->value => ['Devolvido (No prazo)', 'primary', false, true, false],
            LoanStatus::LATE->value => ['Devolvido (Com atraso)', 'warning', false, true, false],
            LoanStatus::DAMAGED->value => ['Devolvido (Com avaria)', 'danger', false, true, true],
        ];

        foreach (LoanStatus::cases() as $status) {
            [$label, $color, $isActive, $isReturned, $requiresMaintenance] = $expectations[$status->value];

            $this->assertSame($label, $status->label());
            $this->assertSame($color, $status->color());
            $this->assertSame($isActive, $status->isActive());
            $this->assertSame($isReturned, $status->isReturned());
            $this->assertSame($requiresMaintenance, $status->requiresMaintenance());
        }

        $this->assertSame([
            LoanStatus::ACTIVE->value,
            LoanStatus::LATE->value,
        ], LoanStatus::openStatuses());
    }

    public function test_priority_labels_and_colors(): void
    {
        $expectations = [
            Priority::LOW->value => ['Baixa', 'info'],
            Priority::MEDIUM->value => ['Média', 'warning'],
            Priority::HIGH->value => ['Alta', 'danger'],
            Priority::CRITICAL->value => ['Crítica', 'dark'],
            Priority::URGENT->value => ['Urgente', 'danger'],
        ];

        foreach (Priority::cases() as $priority) {
            [$label, $color] = $expectations[$priority->value];

            $this->assertSame($label, $priority->label());
            $this->assertSame($color, $priority->color());
        }
    }

    public function test_resource_status_labels_descriptions_colors_and_blocking_rules(): void
    {
        $expectations = [
            ResourceStatus::AVAILABLE->value => [
                'Disponível',
                'Recurso disponível para uso e empréstimo.',
                'success',
                false,
                false,
            ],
            ResourceStatus::IN_USE->value => [
                'Em uso',
                'Recurso atualmente em uso.',
                'primary',
                true,
                false,
            ],
            ResourceStatus::UNDER_MAINTENANCE->value => [
                'Em manutenção',
                'Recurso em manutenção ou reparo.',
                'warning',
                true,
                false,
            ],
            ResourceStatus::DAMAGED->value => [
                'Danificado',
                'Recurso danificado e indisponível temporariamente.',
                'danger',
                true,
                false,
            ],
            ResourceStatus::UNAVAILABLE->value => [
                'Indisponível',
                'Recurso indisponível para acesso.',
                'secondary',
                true,
                true,
            ],
        ];

        foreach (ResourceStatus::cases() as $status) {
            [$label, $description, $color, $blocksLoan, $blocksAccess] = $expectations[$status->value];

            $this->assertSame($label, $status->label());
            $this->assertSame($description, $status->description());
            $this->assertSame($color, $status->color());
            $this->assertSame($blocksLoan, $status->blocksLoan());
            $this->assertSame($blocksAccess, $status->blocksAccess());
        }
    }

    public function test_waitlist_status_labels_and_colors(): void
    {
        $expectations = [
            WaitlistStatus::WAITING->value => ['Em Espera', 'warning'],
            WaitlistStatus::NOTIFIED->value => ['Notificado', 'info'],
            WaitlistStatus::FULFILLED->value => ['Atendido', 'success'],
            WaitlistStatus::CANCELLED->value => ['Cancelado', 'danger'],
        ];

        foreach (WaitlistStatus::cases() as $status) {
            [$label, $color] = $expectations[$status->value];

            $this->assertSame($label, $status->label());
            $this->assertSame($color, $status->color());
        }
    }
}
