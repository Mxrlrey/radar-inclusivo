<?php

namespace App\Services;

use App\Enums\BarrierStatus;
use App\Enums\InspectionType;
use App\Models\Barrier;
use Illuminate\Support\Facades\{Auth, DB};

class BarrierService
{
    /**
     * RF: injeta o serviço responsável por registrar inspeções vinculadas à barreira.
     * Uso: permite acoplar logs de vistoria ao fluxo de cadastro e atualização.
     */
    public function __construct(
        protected InspectionService $inspectionService
    ) {}

    /**
     * RF: cria uma barreira com sanitização de relato, relações e log inicial.
     * Uso: cadastro de novas ocorrências de barreiras no radar inclusivo.
     */
    public function store(array $data): Barrier
    {
        return DB::transaction(
            fn() => $this->persist(new Barrier(), $data)
        );
    }

    /**
     * RF: atualiza uma barreira reutilizando a rotina central de persistência e inspeção.
     * Uso: manutenção de ocorrências já registradas no radar.
     */
    public function update(Barrier $barrier, array $data): Barrier
    {
        return DB::transaction(
            fn() => $this->persist($barrier, $data)
        );
    }

    /**
     * RF: remove uma barreira em transação única.
     * Uso: exclusão administrativa de registros lançados indevidamente.
     */
    public function delete(Barrier $barrier): void
    {
        DB::transaction(fn() => $barrier->delete());
    }

    /**
     * RF: centraliza a persistência da barreira com saneamento de dados e sincronização.
     * Uso: evita duplicação entre os fluxos de criação e edição do módulo.
     */
    protected function persist(Barrier $barrier, array $data): Barrier
    {
        $data = $this->sanitizeReporterData($data);
        $data = $this->prepareData($barrier, $data);

        $barrier->fill($data)->save();

        $this->syncRelations($barrier, $data);
        $this->handleInspectionLog($barrier, $data);

        return $barrier->fresh([
            'category',
            'location',
            'deficiencies'
        ]);
    }

    /**
     * RF: prepara valores derivados da barreira antes do salvamento.
     * Uso: define autor do registro e limpa vínculo de localização quando necessário.
     */
    protected function prepareData(Barrier $barrier, array $data): array
    {
        if (!$barrier->exists && Auth::check()) {
            $data['registered_by_user_id'] = Auth::id();
        }

        if (!empty($data['no_location'])) {
            $data['location_id'] = null;
        }

        return $data;
    }

    /**
     * RF: normaliza os campos do relato conforme o tipo de identificação informado.
     * Uso: evita combinações inválidas entre anonimato, relato geral e pessoa identificada.
     */
    protected function sanitizeReporterData(array $data): array
    {
        $cleanFields = [
            'affected_student_id' => null,
            'affected_professional_id' => null,
            'affected_person_name' => null,
            'affected_person_role' => null,
            'is_anonymous' => false,
            'not_applicable' => false,
        ];

        if (!empty($data['is_anonymous'])) {
            return array_merge($data, $cleanFields, ['is_anonymous' => true]);
        }

        if (!empty($data['not_applicable'])) {
            return array_merge($data, $cleanFields, [
                'not_applicable' => true,
                'affected_person_name' => $data['affected_person_name'] ?? null,
                'affected_person_role' => $data['affected_person_role'] ?? null,
            ]);
        }

        return array_merge($data, [
            'is_anonymous' => false,
            'not_applicable' => false,
            'affected_person_name' => null,
            'affected_person_role' => null,
            'affected_student_id' => $data['affected_student_id'] ?? null,
            'affected_professional_id' => $data['affected_professional_id'] ?? null,
        ]);
    }

    /**
     * RF: sincroniza as relações many-to-many da barreira após a persistência.
     * Uso: mantém deficiências associadas alinhadas ao formulário enviado.
     */
    protected function syncRelations(Barrier $barrier, array $data): void
    {
        if (isset($data['deficiencies'])) {
            $barrier->deficiencies()->sync($data['deficiencies']);
        }
    }

    /**
     * RF: registra o log de inspeção e controla o timestamp de resolução da barreira.
     * Uso: sustenta histórico operacional e indicadores de acompanhamento do radar.
     */
    protected function handleInspectionLog(Barrier $barrier, array $data): void
    {
        $isUpdate = $barrier->wasRecentlyCreated === false;
        $oldStatus = $isUpdate ? $barrier->latestStatus()?->value : null;
        $newStatus = $data['status'] ?? $oldStatus ?? BarrierStatus::IDENTIFIED->value;

        $statusChanged = $isUpdate && $newStatus !== $oldStatus;
        $hasInteraction = filled($data['inspection_description'] ?? null) || !empty($data['images']);

        if (in_array($newStatus, [BarrierStatus::RESOLVED->value, BarrierStatus::NOT_APPLICABLE->value])) {
            $barrier->update(['resolved_at' => $barrier->resolved_at ?? now()]);
        } else {
            $barrier->update(['resolved_at' => null]);
        }

        if ($isUpdate && !$statusChanged && !$hasInteraction) {
            return;
        }

        $this->inspectionService->createForModel($barrier, [
            'state' => null,
            'status' => $newStatus,
            'inspection_date' => $data['inspection_date'] ?? now(),
            'type' => $data['inspection_type'] ?? ($isUpdate ? InspectionType::PERIODIC->value : InspectionType::INITIAL->value),
            'description' => $data['inspection_description'] ?? ($isUpdate ? null : 'Registro inicial da barreira.'),
            'images' => $data['images'] ?? [],
        ]);
    }
}
