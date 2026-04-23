<?php

namespace App\Services;

use App\Exceptions\BusinessRuleException;
use App\Models\Institution;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InstitutionService
{
    /**
     * RF: cria a instituição principal apenas quando ainda não existir registro ativo.
     * Uso: cadastro inicial da instância única da instituição no sistema.
     */
    public function store(array $data): ?Institution
    {
        if (Institution::exists()) {
            return null;
        }

        return DB::transaction(fn () => Institution::create($data));
    }

    /**
     * RF: atualiza a instituição validando pendências antes de permitir desativação.
     * Uso: manutenção dos dados centrais e da disponibilidade institucional no radar.
     */
    public function update(Institution $institution, array $data): Institution
    {
        return DB::transaction(function () use ($institution, $data) {
            $wasActive = $institution->is_active;
            $willDeactivate = $wasActive && isset($data['is_active']) && !$data['is_active'];

            if ($willDeactivate) {
                $hasUnresolvedBarriers = $institution
                    ->barriers()
                    ->whereNull('resolved_at')
                    ->exists();

                if ($hasUnresolvedBarriers) {
                    throw ValidationException::withMessages([
                        'is_active' => 'Existem barreiras não resolvidas. Resolva-as antes de desativar a instituição.'
                    ]);
                }
            }

            $institution->update($data);

            if ($willDeactivate) {
                $institution->locations()->update([
                    'is_active' => false
                ]);
            }

            return $institution;
        });
    }

    /**
     * RF: exclui a instituição somente quando não houver barreiras em estado impeditivo.
     * Uso: preserva coerência histórica antes da remoção da entidade central do sistema.
     */
    public function delete(Institution $institution): void
    {
        DB::transaction(function () use ($institution) {
            $hasActiveBarrier = $institution
                ->barriers()
                ->get()
                ->contains(function ($barrier) {
                    $status = $barrier->latestStatus();

                    if (!$status) {
                        return true;
                    }

                    return ! $status->allowsDeletion();
                });

            if ($hasActiveBarrier) {
                throw new BusinessRuleException("Não é possível excluir esta instituição pois ela possui barreiras ativas.");
            }

            $institution->locations()->delete();
            $institution->delete();
        });
    }
}
