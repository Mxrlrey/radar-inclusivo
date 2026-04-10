<?php

namespace App\Audit\Formatters;

use App\Enums\ConservationState;
use App\Enums\ResourceStatus;
use App\Models\Deficiency;

class AssistiveTechnologyFormatter extends AuditFormatter
{
    protected function formatters(): array
    {
        return [
            'is_digital'         => fn($v) => $v ? 'Digital' : 'Físico',
            'is_active'          => fn($v) => $v ? 'Ativo' : 'Inativo',
            'is_loanable'        => fn($v) => $v ? 'Sim' : 'Não',
            'status'             => ResourceStatus::class,
            'conservation_state' => ConservationState::class,
            'deficiencies'       => fn($ids) => is_array($ids)
                ? Deficiency::whereIn('id', $ids)->pluck('name')->join(', ') ?: 'Nenhuma'
                : null,
        ];
    }
}
