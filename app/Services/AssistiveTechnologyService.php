<?php

namespace App\Services;

use App\Audit\AuditLogger;
use App\Enums\ResourceStatus;
use App\Exceptions\BusinessRuleException;
use App\Models\AssistiveTechnology;
use Illuminate\Support\Facades\DB;

class AssistiveTechnologyService
{
    /**
     * RF: injeta os serviços necessários para inspeção, estoque e auditoria de tecnologias assistivas.
     * Uso: orquestra regras complementares no ciclo de vida dos recursos assistivos.
     */
    public function __construct(
        protected InspectionService $inspectionService,
        protected LoanService $loanService,
        protected AuditLogger $auditLogger,
    ) {}

    /**
     * RF: cria uma tecnologia assistiva com validação, estoque e relações obrigatórias.
     * Uso: cadastro inicial de recursos assistivos físicos ou digitais.
     */
    public function store(array $data): AssistiveTechnology
    {
        return DB::transaction(function () use ($data) {
            $at = new AssistiveTechnology();

            $this->validateBusinessRules($at, $data);

            $data['status'] = $data['status'] ?? ResourceStatus::AVAILABLE->value;
            $data = $this->loanService->calculateStockForLoan($at, $data);

            $at->fill($data)->save();

            if (isset($data['deficiencies'])) {
                $at->deficiencies()->sync($data['deficiencies']);
            }

            $this->inspectionService->createInspectionForModel($at, $data);

            return $at->fresh(['deficiencies']);
        });
    }

    /**
     * RF: atualiza uma tecnologia assistiva preservando regras de estoque e auditoria.
     * Uso: edição de itens já registrados no catálogo de tecnologias assistivas.
     */
    public function update(AssistiveTechnology $at, array $data): AssistiveTechnology
    {
        return DB::transaction(function () use ($at, $data) {
            $this->validateBusinessRules($at, $data);
            $this->validateStatusChangeWithActiveLoans($at, $data);

            if (isset($data['quantity'])) {
                $this->loanService->validateStockAvailability($at, (int) $data['quantity']);
            }

            $oldDef = $at->deficiencies()->pluck('deficiencies.id')->toArray();

            $data = $this->loanService->calculateStockForLoan($at, $data);

            $at->fill($data)->save();

            if (isset($data['deficiencies'])) {
                $at->deficiencies()->sync($data['deficiencies']);

                $this->auditLogger->logRelationIfChanged(
                    $at,
                    'deficiencies',
                    $oldDef,
                    array_map('intval', $data['deficiencies'])
                );
            }

            $this->inspectionService->createInspectionForModel($at, $data);

            return $at->fresh(['deficiencies']);
        });
    }

    /**
     * RF: exclui uma tecnologia assistiva apenas quando não houver empréstimos ativos.
     * Uso: protege o histórico de circulação antes da remoção do item.
     */
    public function delete(AssistiveTechnology $assistiveTechnology): void
    {
        DB::transaction(function () use ($assistiveTechnology) {
            if ($assistiveTechnology->loans()->whereNull('return_date')->exists()) {
                throw new BusinessRuleException("Não é possível excluir um item com empréstimos ativos.");
            }

            $assistiveTechnology->delete();
        });
    }

    /**
     * RF: valida regras de negócio da tecnologia assistiva quanto a público e estoque.
     * Uso: bloqueia persistência de dados inconsistentes no cadastro do recurso.
     */
    private function validateBusinessRules(AssistiveTechnology $at, array $data): void
    {
        $isDigital = $data['is_digital'] ?? $at->is_digital  ?? false;
        $isLoanable = $data['is_loanable'] ?? $at->is_loanable ?? false;
        $quantity = isset($data['quantity']) ? (int) $data['quantity'] : $at->quantity;
        $available = isset($data['quantity_available']) ? (int) $data['quantity_available'] : $at->quantity_available;

        if (isset($data['deficiencies']) && empty($data['deficiencies'])) {
            throw new BusinessRuleException("Selecione pelo menos um público-alvo.");
        }

        if (!$isDigital && $quantity <= 0) {
            throw new BusinessRuleException("Para recursos físicos, a quantidade deve ser no mínimo 1.");
        }

        if ($isLoanable && $quantity <= 0) {
            throw new BusinessRuleException("Recursos marcados como emprestáveis devem ter quantidade maior que zero.");
        }

        if ($available > $quantity) {
            throw new BusinessRuleException("A quantidade disponível ({$available}) não pode ser maior que a quantidade total ({$quantity}).");
        }
    }

    /**
     * RF: impede mudança de status quando a tecnologia ainda está vinculada a empréstimos ativos.
     * Uso: mantém coerência entre inventário, circulação e disponibilidade do recurso.
     */
    private function validateStatusChangeWithActiveLoans(AssistiveTechnology $at, array $data): void
    {
        if (!isset($data['status'])) return;

        if ($at->loans()->whereNull('return_date')->exists() && $at->status->value !== $data['status']) {
            throw new BusinessRuleException("Não é possível alterar o status do item enquanto houver empréstimos ativos.");
        }
    }
}
