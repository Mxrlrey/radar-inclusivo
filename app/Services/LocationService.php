<?php

namespace App\Services;

use App\Exceptions\BusinessRuleException;
use App\Models\Location;
use Illuminate\Support\Facades\DB;

class LocationService
{
    /**
     * RF: cria um ponto de referência com persistência transacional.
     * Uso: cadastro de locais vinculados à instituição e ao mapa de barreiras.
     */
    public function store(array $data): Location
    {
        return DB::transaction(
            fn () => Location::create($data)
        );
    }

    /**
     * RF: atualiza um ponto de referência validando pendências antes de desativá-lo.
     * Uso: manutenção de locais usados no radar institucional.
     */
    public function update(Location $location, array $data): Location
    {
        return DB::transaction(function () use ($location, $data) {
            $wasActive = $location->is_active;
            $willDeactivate = $wasActive && isset($data['is_active']) && !$data['is_active'];

            if ($willDeactivate) {
                $hasUnresolvedBarriers = $location
                    ->barriers()
                    ->whereNull('resolved_at')
                    ->exists();

                if ($hasUnresolvedBarriers) {
                    throw new BusinessRuleException('Existem barreiras não resolvidas vinculadas a este local. Resolva-as antes de desativá-lo.');
                }
            }

            $location->update($data);

            return $location;
        });
    }

    /**
     * RF: exclui um ponto de referência apenas quando não houver barreiras pendentes.
     * Uso: protege o histórico do mapa antes de remover locais do sistema.
     */
    public function delete(Location $location): void
    {
        DB::transaction(function () use ($location) {
            $hasActiveBarriers = $location
                ->barriers()
                ->whereNull('resolved_at')
                ->exists();

            if ($hasActiveBarriers) {
                throw new BusinessRuleException("Não é possível excluir este ponto de referência pois ele possui barreiras ativas.");
            }

            $location->delete();
        });
    }
}
