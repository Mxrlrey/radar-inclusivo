<?php

namespace App\Services;

use App\Exceptions\BusinessRuleException;
use App\Models\Position;
use Illuminate\Support\Facades\DB;

class PositionService
{
    public function store(array $data): Position
    {
        return DB::transaction(fn() => $this->persist(new Position(), $data));
    }

    public function update(Position $position, array $data): Position
    {
        return DB::transaction(fn() => $this->persist($position, $data));
    }

    public function delete(Position $position): void
    {
        DB::transaction(function () use ($position) {
            /* Regra de Negócio: Não permitimos excluir cargos que possuem
               vínculo com profissionais para evitar inconsistência. */
            if ($position->professionals()->exists()) {
                throw new BusinessRuleException('Não é possível excluir um cargo que possui profissionais vinculados.');
            }

            $position->delete();
        });
    }

    /**
     * Lógica central de persistência e sincronização de ACL
     */
    protected function persist(Position $position, array $data): Position
    {
        $position->fill($data)->save();

        if (isset($data['permissions'])) {
            $position->permissions()->sync($data['permissions']);
        }

        return $position->fresh('permissions');
    }
}
