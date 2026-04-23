<?php

namespace App\Services;

use App\Exceptions\BusinessRuleException;
use App\Models\Deficiency;
use Illuminate\Support\Facades\DB;

class DeficiencyService
{
    /**
     * RF: cria uma deficiência com regras padrão de ativação.
     * Uso: cadastro inicial de públicos-alvo usados em recursos e barreiras.
     */
    public function store(array $data): Deficiency
    {
        return DB::transaction(fn() => $this->persist(new Deficiency(), $data));
    }

    /**
     * RF: atualiza uma deficiência reutilizando a rotina central de persistência.
     * Uso: manutenção cadastral da taxonomia de deficiências do sistema.
     */
    public function update(Deficiency $deficiency, array $data): Deficiency
    {
        return DB::transaction(fn() => $this->persist($deficiency, $data));
    }

    /**
     * RF: remove uma deficiência em transação única.
     * Uso: exclusão administrativa de registros que não devem mais aparecer no sistema.
     */
    public function delete(Deficiency $deficiency): void
    {
        DB::transaction(function () use ($deficiency) {
            $deficiency->delete();
        });
    }

    /**
     * RF: concentra a persistência de uma deficiência com valores padrão na criação.
     * Uso: evita duplicação entre os fluxos de cadastro e edição do módulo.
     */
    protected function persist(Deficiency $deficiency, array $data): Deficiency
    {
        if (!$deficiency->exists && !isset($data['is_active'])) {
            $data['is_active'] = true;
        }

        $deficiency->fill($data)->save();

        return $deficiency;
    }
}
