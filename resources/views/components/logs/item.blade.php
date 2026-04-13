@props(['log'])

@php
    $actionConfig = match($log->action) {
        'created' => ['icon' => 'fa fa-plus',   'color' => 'success', 'label' => 'Criação'],
        'updated' => ['icon' => 'fa fa-pencil',    'color' => 'info', 'label' => 'Edição'],
        'deleted' => ['icon' => 'fa fa-eraser',  'color' => 'danger',  'label' => 'Exclusão'],
        default   => ['icon' => 'fa fa-history','color' => 'secondary','label' => $log->action],
    };

    $modelClass = Illuminate\Database\Eloquent\Relations\Relation::getMorphedModel($log->auditable_type) ?? $log->auditable_type;
    $fieldLabels = (class_exists($modelClass) && method_exists($modelClass, 'auditLabels')) ? $modelClass::auditLabels() : [];

    $changedFields = array_keys($log->new_values ?? []);
    $changedFields = array_filter($changedFields, fn($f) => !in_array($f, ['updated_at', 'created_at', 'deleted_at']));

    $labelsArray = array_map(fn($f) => $fieldLabels[$f] ?? ucfirst(str_replace('_', ' ', $f)), $changedFields);
    $modalId = "modal-log-" . $log->id;
@endphp

<div class="cd-timeline-block">
    <div class="cd-timeline-img cd-{{ $actionConfig['color'] }}">
        <i class="{{ $actionConfig['icon'] }}"></i>
    </div>

    <div class="cd-timeline-content">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
            <h3>{{ $actionConfig['label'] }}</h3>
            <span class="cd-date">
                <i class="far fa-clock me-1"></i>
                {{ $log->created_at->format('d/m/Y H:i') }}
            </span>
        </div>

        <div class="mt-2">
            @if($log->action === 'updated')
                <p class="mb-1 text-muted">
                    <strong>Campos alterados:</strong>
                    <span>{{ empty($labelsArray) ? 'Nenhum campo rastreável' : implode(', ', $labelsArray) }}</span>
                </p>
                <div class="mt-2 d-flex justify-content-end">
                    <x-buttons.link-button
                        :href="'javascript:void(0)'"
                        variant="info"
                        class="mt-2"
                        data-bs-toggle="modal"
                        data-bs-target="#{{ $modalId }}"
                        aria-label="Visualizar detalhes das alterações do registro {{ $log->id }}"
                    >
                        <span class="btn-label">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </span>
                        Ver detalhes
                    </x-buttons.link-button>
                </div>
            @elseif($log->action === 'created')
                <p class="text-success mb-0 small"><i class="fa fa-check-circle me-1"></i> Registro inicializado no sistema.</p>
            @elseif($log->action === 'deleted')
                <p class="text-danger mb-0 small"><i class="fa fa-trash me-1"></i> Registro removido do banco de dados.</p>
            @endif
        </div>

        <div class="d-flex align-items-center mt-3 pt-2 border-top">
            <small class="text-muted">
                Executado por: <strong>{{ $log->user?->name ?? 'Sistema' }}</strong>
            </small>
        </div>
    </div>

    @if($log->action === 'updated')
        <x-modal.modal
            :id="$modalId"
            title="Detalhes da Alteração"
        >
            <div class="p-3 border-bottom" style="background-color: var(--bg-surface-secondary); color: var(--text-primary);">
                <div class="small" style="color: var(--text-muted);">
                    Ação realizada em
                </div>
                <div class="fw-bold" style="color: var(--text-primary);">
                    {{ $log->created_at->format('d/m/Y à\s H:i') }}
                </div>
            </div>

            <x-logs.detail-renderer :log="$log" />
        </x-modal.modal>
    @endif
</div>
