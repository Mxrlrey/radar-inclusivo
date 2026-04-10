<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['log']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['log']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    use Illuminate\Database\Eloquent\Relations\Relation;

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

        if (class_exists($modelClass) && method_exists($modelClass, 'auditFormatter')) {
            $formatted = (new ($modelClass::auditFormatter()))->format($field, $value);
            if ($formatted !== null) return $formatted;
        }

        if (is_bool($value)) return $value ? 'Sim' : 'Não';
        if (is_array($value)) return implode(', ', $value);

        return (string) $value;
    };
?>

<div class="log-change-list">
    <?php if($log->action === 'updated' && !empty($allFields)): ?>
        <?php $__currentLoopData = $allFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(in_array($field, ['updated_at', 'created_at', 'deleted_at'])) continue; ?>

            <div class="change-item">
                <div class="field-name">
                    <?php echo e($fieldLabels[$field] ?? ucfirst(str_replace('_', ' ', $field))); ?>

                </div>

                <div class="values-diff">
                    <span class="old-value">
                        <?php echo $formatValue($field, $oldValues[$field] ?? null); ?>

                    </span>

                    <i class="fas fa-long-arrow-alt-right diff-arrow"></i>

                    <span class="new-value">
                        <?php echo $formatValue($field, $newValues[$field] ?? null); ?>

                    </span>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <?php elseif($log->action === 'created'): ?>
        <div class="audit-note audit-note-info">
            Registro inicializado com os dados do sistema.
        </div>

    <?php elseif($log->action === 'deleted'): ?>
        <div class="audit-note audit-note-danger">
            Registro removido permanentemente.
        </div>

    <?php else: ?>
        <div class="text-muted small">—</div>
    <?php endif; ?>
</div>
<?php /**PATH /var/www/resources/views/components/logs/detail-renderer.blade.php ENDPATH**/ ?>