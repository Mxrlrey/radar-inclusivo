<?php

namespace App\Services;

use App\Models\AccessibilityFeature;
use Illuminate\Support\Facades\DB;

class AccessibilityFeatureService
{
    /**
     * RF: cria um recurso de acessibilidade com persistência transacional.
     * Uso: atende o cadastro inicial de recursos vinculados a materiais acessíveis.
     */
    public function store(array $data): AccessibilityFeature
    {
        return DB::transaction(function () use ($data) {
            return AccessibilityFeature::create($data);
        });
    }

    /**
     * RF: atualiza um recurso de acessibilidade preservando consistência transacional.
     * Uso: edição de cadastros usados por materiais e relatórios do módulo.
     */
    public function update(AccessibilityFeature $feature, array $data): AccessibilityFeature
    {
        return DB::transaction(function () use ($feature, $data) {
            $feature->update($data);
            return $feature->fresh();
        });
    }

    /**
     * RF: remove um recurso de acessibilidade em transação única.
     * Uso: exclusão administrativa de registros não mais utilizados no sistema.
     */
    public function delete(AccessibilityFeature $feature): void
    {
        DB::transaction(function () use ($feature) {
            $feature->delete();
        });
    }
}
