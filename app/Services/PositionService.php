<?php

namespace App\Services;

use App\Exceptions\BusinessRuleException;
use App\Models\Position;
use Illuminate\Support\Facades\DB;

class PositionService
{
    /**
     * RF: cria um cargo com sincronização opcional de permissões.
     * Uso: cadastro de perfis funcionais usados por profissionais do sistema.
     */
    public function store(array $data): Position
    {
        return DB::transaction(fn() => $this->persist(new Position(), $data));
    }

    /**
     * RF: atualiza um cargo reutilizando a rotina central de persistência.
     * Uso: manutenção de cargos e permissões vinculadas a profissionais.
     */
    public function update(Position $position, array $data): Position
    {
        return DB::transaction(fn() => $this->persist($position, $data));
    }

    /**
     * RF: exclui um cargo apenas quando não houver profissionais vinculados.
     * Uso: protege a integridade dos vínculos funcionais já cadastrados.
     */
    public function delete(Position $position): void
    {
        DB::transaction(function () use ($position) {
            if ($position->professionals()->exists()) {
                throw new BusinessRuleException('Não é possível excluir um cargo que possui profissionais vinculados.');
            }

            $position->delete();
        });
    }

    /**
     * RF: centraliza a persistência do cargo e das permissões vinculadas.
     * Uso: padroniza o salvamento usado pelos fluxos de criação e edição.
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
