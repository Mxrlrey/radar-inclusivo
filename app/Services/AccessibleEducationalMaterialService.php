<?php

namespace App\Services;

use App\Audit\AuditLogger;
use App\Enums\ResourceStatus;
use App\Exceptions\BusinessRuleException;
use App\Models\AccessibleEducationalMaterial;
use Illuminate\Support\Facades\DB;

class AccessibleEducationalMaterialService
{
    /**
     * RF: injeta os serviços necessários para inspeção, estoque e auditoria de materiais.
     * Uso: orquestra regras complementares durante o ciclo de vida dos materiais acessíveis.
     */
    public function __construct(
        protected InspectionService $inspectionService,
        protected LoanService $loanService,
        protected AuditLogger $auditLogger,
    ) {}

    /**
     * RF: cria um material educacional acessível com validação, estoque e relações.
     * Uso: cadastro inicial de materiais físicos ou digitais do acervo acessível.
     */
    public function store(array $data): AccessibleEducationalMaterial
    {
        return DB::transaction(function () use ($data) {
            $material = new AccessibleEducationalMaterial();
            $data = $this->normalizeInventoryData($material, $data);

            $this->validateBusinessRules($material, $data);

            $data['status'] = $data['status'] ?? ResourceStatus::AVAILABLE->value;
            $data = $this->loanService->calculateStockForLoan($material, $data);

            $material->fill($data)->save();

            if (isset($data['deficiencies'])) {
                $material->deficiencies()->sync($data['deficiencies']);
            }

            if (isset($data['accessibility_features'])) {
                $material->accessibilityFeatures()->sync($data['accessibility_features']);
            }

            $this->inspectionService->createInspectionForModel($material, $data);

            return $material->fresh(['deficiencies', 'accessibilityFeatures']);
        });
    }

    /**
     * RF: atualiza um material acessível preservando regras de estoque, status e auditoria.
     * Uso: edição de itens já cadastrados no acervo educacional acessível.
     */
    public function update(AccessibleEducationalMaterial $material, array $data): AccessibleEducationalMaterial
    {
        return DB::transaction(function () use ($material, $data) {
            $data = $this->normalizeInventoryData($material, $data);

            $this->validateBusinessRules($material, $data);
            $this->validateStatusChangeWithActiveLoans($material, $data);

            if (isset($data['quantity'])) {
                $this->loanService->validateStockAvailability(
                    $material,
                    (int) $data['quantity'],
                    (bool) ($data['is_digital'] ?? $material->is_digital ?? false)
                );
            }

            $oldDef = $material->deficiencies()->pluck('deficiencies.id')->toArray();
            $oldFeatures = $material->accessibilityFeatures()->pluck('accessibility_features.id')->toArray();

            $data = $this->loanService->calculateStockForLoan($material, $data);

            $material->fill($data)->save();

            if (isset($data['deficiencies'])) {
                $material->deficiencies()->sync($data['deficiencies']);

                $this->auditLogger->logRelationIfChanged(
                    $material,
                    'deficiencies',
                    $oldDef,
                    array_map('intval', $data['deficiencies'])
                );
            }

            if (isset($data['accessibility_features'])) {
                $material->accessibilityFeatures()->sync($data['accessibility_features']);

                $this->auditLogger->logRelationIfChanged(
                    $material,
                    'accessibility_features',
                    $oldFeatures,
                    array_map('intval', $data['accessibility_features'])
                );
            }

            $this->inspectionService->createInspectionForModel($material, $data);

            return $material->fresh(['deficiencies', 'accessibilityFeatures']);
        });
    }

    /**
     * RF: exclui um material apenas quando não houver empréstimos ativos em aberto.
     * Uso: protege o histórico de circulação antes da remoção física do item.
     */
    public function delete(AccessibleEducationalMaterial $material): void
    {
        DB::transaction(function () use ($material) {
            if ($material->loans()->whereNull('return_date')->exists()) {
                throw new BusinessRuleException("Não é possível excluir um item com empréstimos ativos.");
            }

            $material->delete();
        });
    }

    /**
     * RF: valida regras de negócio do material quanto a público, quantidade e disponibilidade.
     * Uso: bloqueia persistência de dados inconsistentes antes do salvamento.
     */
    private function validateBusinessRules(AccessibleEducationalMaterial $material, array $data): void
    {
        $isDigital = $data['is_digital']  ?? $material->is_digital  ?? false;
        $isLoanable = $data['is_loanable'] ?? $material->is_loanable ?? false;
        $quantity = isset($data['quantity']) ? (int) $data['quantity'] : $material->quantity;
        $available = isset($data['quantity_available']) ? (int) $data['quantity_available'] : $material->quantity_available;

        if (isset($data['deficiencies']) && empty($data['deficiencies'])) {
            throw new BusinessRuleException("Selecione pelo menos um público-alvo.");
        }

        if (!$isDigital && $quantity <= 0) {
            throw new BusinessRuleException("Para materiais físicos, a quantidade deve ser no mínimo 1.");
        }

        if (!$isDigital && $isLoanable && $quantity <= 0) {
            throw new BusinessRuleException("Materiais marcados como emprestáveis devem ter quantidade maior que zero.");
        }

        if ($available > $quantity) {
            throw new BusinessRuleException("A quantidade disponível ({$available}) não pode ser maior que a quantidade total ({$quantity}).");
        }
    }

    /**
     * RF: impede alteração de status quando o material ainda possui empréstimos ativos.
     * Uso: mantém coerência operacional no fluxo de devolução e disponibilidade.
     */
    private function validateStatusChangeWithActiveLoans(AccessibleEducationalMaterial $material, array $data): void
    {
        if (!isset($data['status'])) return;

        if ($material->loans()->whereNull('return_date')->exists() && $material->status->value !== $data['status']) {
            throw new BusinessRuleException("Não é possível alterar o status do item enquanto houver empréstimos ativos.");
        }
    }

    /**
     * RF: normaliza dados de estoque ao alternar entre material digital e físico.
     * Uso: remove sentinelas legadas de estoque digital e evita persisti-las como estoque real.
     */
    private function normalizeInventoryData(AccessibleEducationalMaterial $material, array $data): array
    {
        $isDigital = $data['is_digital'] ?? $material->is_digital ?? false;

        if ($isDigital) {
            $data['asset_code'] = null;
            $data['quantity'] = null;
            $data['quantity_available'] = null;

            return $data;
        }

        $isLegacyDigitalQuantitySentinel = ($material->is_digital ?? false)
            && (int) ($material->quantity ?? 0) === 999
            && array_key_exists('quantity', $data)
            && (int) $data['quantity'] === 999;

        if ($isLegacyDigitalQuantitySentinel) {
            $data['quantity'] = 1;
        }

        return $data;
    }
}
