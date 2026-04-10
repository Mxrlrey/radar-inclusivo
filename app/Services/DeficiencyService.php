<?php

namespace App\Services;

use App\Exceptions\BusinessRuleException;
use App\Models\Deficiency;
use Illuminate\Support\Facades\DB;

class DeficiencyService
{
    public function store(array $data): Deficiency
    {
        return DB::transaction(fn() => $this->persist(new Deficiency(), $data));
    }

    public function update(Deficiency $deficiency, array $data): Deficiency
    {
        return DB::transaction(fn() => $this->persist($deficiency, $data));
    }

    public function delete(Deficiency $deficiency): void
    {
        DB::transaction(function () use ($deficiency) {
            // Regra de Negócio: Impedir a exclusão se houver alunos vinculados
            if ($deficiency->students()->exists()) {
                throw new BusinessRuleException('Não é possível excluir uma deficiência que possui alunos vinculados.');
            }

            $deficiency->delete();
        });
    }

    protected function persist(Deficiency $deficiency, array $data): Deficiency
    {
        // Se for uma criação e não veio status, define como ativo por padrão
        if (!$deficiency->exists && !isset($data['is_active'])) {
            $data['is_active'] = true;
        }

        $deficiency->fill($data)->save();

        return $deficiency;
    }
}
