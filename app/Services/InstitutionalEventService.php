<?php

namespace App\Services;

use App\Exceptions\BusinessRuleException;
use App\Models\InstitutionalEvent;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InstitutionalEventService
{
    /**
     * RF: cria um evento institucional validando datas e horários antes de persistir.
     * Uso: cadastro de eventos exibidos no calendário institucional do sistema.
     */
    public function store(array $data): InstitutionalEvent
    {
        return DB::transaction(
            fn() => $this->persist(new InstitutionalEvent(), $data)
        );
    }

    /**
     * RF: atualiza um evento institucional reutilizando a rotina central de persistência.
     * Uso: manutenção de eventos já publicados no calendário da instituição.
     */
    public function update(InstitutionalEvent $event, array $data): InstitutionalEvent
    {
        return DB::transaction(
            fn() => $this->persist($event, $data)
        );
    }

    /**
     * RF: remove um evento institucional em transação única.
     * Uso: exclusão administrativa de eventos cancelados ou cadastrados incorretamente.
     */
    public function delete(InstitutionalEvent $event): void
    {
        DB::transaction(function () use ($event) {
            $event->delete();
        });
    }

    /**
     * RF: centraliza a validação e a persistência de eventos institucionais.
     * Uso: evita duplicação entre os fluxos de criação e edição do calendário.
     */
    protected function persist(InstitutionalEvent $event, array $data): InstitutionalEvent
    {
        $this->validateEventDates($data);

        $this->saveModel($event, $data);

        return $event->fresh();
    }

    /**
     * RF: salva o model do evento com os dados já validados.
     * Uso: isola a escrita do registro dentro da rotina principal de persistência.
     */
    private function saveModel(InstitutionalEvent $event, array $data): void
    {
        $event->fill($data)->save();
    }

    /**
     * RF: valida coerência entre datas e horários de início e término.
     * Uso: bloqueia eventos com intervalo temporal inválido antes da gravação.
     */
    private function validateEventDates(array $data): void
    {
        $startDate = Carbon::parse($data['start_date']);
        $endDate = Carbon::parse($data['end_date']);
        $startTime = Carbon::createFromFormat('H:i', $data['start_time']);
        $endTime = Carbon::createFromFormat('H:i', $data['end_time']);

        if ($endDate->lt($startDate)) {
            throw new BusinessRuleException('A data de término não pode ser anterior à data de início.');
        }

        if ($startDate->eq($endDate) && $endTime->lte($startTime)) {
            throw new BusinessRuleException('O horário de término deve ser maior que o horário de início para o mesmo dia.');
        }
    }
}
