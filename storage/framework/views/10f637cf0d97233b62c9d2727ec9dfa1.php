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
    $actionConfig = match($log->action) {
        'created' => [
            'icon' => 'fas fa-plus',
            'color' => 'success',
            'label' => 'Criação'
        ],
        'updated' => [
            'icon' => 'fas fa-pen',
            'color' => 'primary', // substitui "purple" por "primary"
            'label' => 'Edição'
        ],
        'deleted' => [
            'icon' => 'fas fa-trash',
            'color' => 'danger',
            'label' => 'Exclusão'
        ],
        default => [
            'icon' => 'fas fa-history',
            'color' => 'secondary',
            'label' => $log->action
        ],
    };
?>

<div class="log-item">
    <div class="log-marker-column">
        <div class="log-marker log-marker-<?php echo e($actionConfig['color']); ?>">
            <i class="<?php echo e($actionConfig['icon']); ?>"></i>
        </div>
        <div class="log-connector"></div>
    </div>

    <div class="log-card">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
            <div>
                <span class="log-action-badge log-badge-<?php echo e($actionConfig['color']); ?>">
                    <?php echo e($actionConfig['label']); ?>

                </span>
            </div>

            <div class="text-muted small">
                <i class="far fa-clock me-1"></i>
                <?php echo e($log->created_at->format('d/m/Y H:i')); ?>

            </div>
        </div>

        <div class="log-details-area">
            <?php if (isset($component)) { $__componentOriginal761b1d7a15c8e0c1a717bdd120f29940 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal761b1d7a15c8e0c1a717bdd120f29940 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.logs.detail-renderer','data' => ['log' => $log]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('logs.detail-renderer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['log' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($log)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal761b1d7a15c8e0c1a717bdd120f29940)): ?>
<?php $attributes = $__attributesOriginal761b1d7a15c8e0c1a717bdd120f29940; ?>
<?php unset($__attributesOriginal761b1d7a15c8e0c1a717bdd120f29940); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal761b1d7a15c8e0c1a717bdd120f29940)): ?>
<?php $component = $__componentOriginal761b1d7a15c8e0c1a717bdd120f29940; ?>
<?php unset($__componentOriginal761b1d7a15c8e0c1a717bdd120f29940); ?>
<?php endif; ?>
        </div>

        <div class="log-author mt-3 pt-3">
            <div class="avatar-xs me-2">
                <span class="avatar-title">
                    <?php echo e(strtoupper(substr($log->user?->name ?? 'S', 0, 1))); ?>

                </span>
            </div>

            <small class="text-muted">
                Executado por: <strong><?php echo e($log->user?->name ?? 'Sistema'); ?></strong>
            </small>
        </div>
    </div>
</div>
<?php /**PATH /var/www/resources/views/components/logs/item.blade.php ENDPATH**/ ?>