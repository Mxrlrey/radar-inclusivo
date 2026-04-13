@props(['log'])

@php
    use Illuminate\Database\Eloquent\Relations\Relation;
    use Illuminate\Support\Str;

    $modelClass = Relation::getMorphedModel($log->auditable_type) ?? $log->auditable_type;

    $fieldLabels = (class_exists($modelClass) && method_exists($modelClass, 'auditLabels'))
        ? $modelClass::auditLabels()
        : [];

    $oldValues = $log->old_values ?? [];
    $newValues = $log->new_values ?? [];
    $allFields = array_unique(array_merge(array_keys($oldValues), array_keys($newValues)));

    $formatValue = function ($field, $value) use ($modelClass) {
        if (is_null($value) || $value === '' || (is_array($value) && empty($value))) {
            return '—';
        }

        $formatted = null;

        // 1. Tenta usar o seu Formatter customizado (AccessibleEducationalMaterialFormatter)
        if (class_exists($modelClass) && method_exists($modelClass, 'auditFormatter')) {
            $formatterClass = $modelClass::auditFormatter();
            if (class_exists($formatterClass)) {
                $formatter = new $formatterClass();
                $formatted = $formatter->format($field, $value);
            }
        }

        // 2. Se o formatter não tratou o campo, aplica lógica padrão
        if ($formatted === null) {
            if (is_bool($value)) $formatted = $value ? 'Sim' : 'Não';
            elseif (is_array($value)) $formatted = implode(', ', $value);
            else $formatted = (string) $value;
        }

        // 3. LIMPEZA FINAL (CKEditor e HTML)
        if (is_string($formatted)) {
            $formatted = strip_tags($formatted);
            $formatted = html_entity_decode($formatted);
            $formatted = Str::limit($formatted, 150);
        }

        return $formatted;
    };
@endphp

<div class="table-responsive">
    <table class="table table-sm table-borderless mb-0">
        <thead style="background-color: var(--table-header-bg); color: var(--table-header-color);">
        <tr>
            <th class="ps-3 py-2" style="width: 30%">Campo</th>
            <th class="py-2">De</th>
            <th class="py-2">Para</th>
        </tr>
        </thead>
        <tbody>
        @foreach($allFields as $field)
            @continue(in_array($field, ['updated_at', 'created_at', 'deleted_at']))
            <tr class="border-bottom">
                <td class="ps-3 fw-bold text-muted small">
                    {{ $fieldLabels[$field] ?? ucfirst(str_replace('_', ' ', $field)) }}
                </td>
                <td class="small text-danger">
                    <strike>{{ $formatValue($field, $oldValues[$field] ?? null) }}</strike>
                </td>
                <td class="small text-success fw-bold">
                    {{ $formatValue($field, $newValues[$field] ?? null) }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
