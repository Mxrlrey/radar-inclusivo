<?php

namespace App\Services;

use App\Exceptions\BusinessRuleException;
use App\Models\BarrierCategory;
use Illuminate\Support\Facades\DB;

class BarrierCategoryService
{
    /**
     * RF: cria uma categoria de barreira com persistência transacional.
     * Uso: cadastro de classificações usadas no fluxo de barreiras do radar.
     */
    public function store(array $data): BarrierCategory
    {
        return DB::transaction(
            fn () => BarrierCategory::create($data)
        );
    }

    /**
     * RF: atualiza uma categoria de barreira preservando consistência transacional.
     * Uso: manutenção de categorias exibidas em formulários e filtros do módulo.
     */
    public function update(BarrierCategory $category, array $data): BarrierCategory
    {
        return DB::transaction(function () use ($category, $data) {
            $category->update($data);
            return $category;
        });
    }

    /**
     * RF: exclui uma categoria apenas quando não houver barreiras em estado impeditivo.
     * Uso: protege o histórico classificatório antes de remover categorias do sistema.
     */
    public function delete(BarrierCategory $category): void
    {
        DB::transaction(function () use ($category) {
            $hasActiveBarrier = $category
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
                throw new BusinessRuleException("Esta categoria não pode ser excluída pois possui barreiras ativas.");
            }

            $category->delete();
        });
    }
}
