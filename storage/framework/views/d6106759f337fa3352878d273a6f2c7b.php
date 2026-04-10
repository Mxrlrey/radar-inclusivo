<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['logs']));

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

foreach (array_filter((['logs']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="log-timeline-wrapper p-4 p-lg-5">
    <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php if (isset($component)) { $__componentOriginalda5b69d14575e83730eda0ce68a37d7a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalda5b69d14575e83730eda0ce68a37d7a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.logs.item','data' => ['log' => $log]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('logs.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['log' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($log)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalda5b69d14575e83730eda0ce68a37d7a)): ?>
<?php $attributes = $__attributesOriginalda5b69d14575e83730eda0ce68a37d7a; ?>
<?php unset($__attributesOriginalda5b69d14575e83730eda0ce68a37d7a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalda5b69d14575e83730eda0ce68a37d7a)): ?>
<?php $component = $__componentOriginalda5b69d14575e83730eda0ce68a37d7a; ?>
<?php unset($__componentOriginalda5b69d14575e83730eda0ce68a37d7a); ?>
<?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="empty-history text-center py-5">
            <div class="empty-history-icon mb-3">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <h6 class="mb-1 text-primary">Nenhum registro encontrado</h6>
            <p class="text-muted mb-0">Não há alterações registradas para este item.</p>
        </div>
    <?php endif; ?>

    <?php if($logs->hasPages()): ?>
        <div class="pt-4 mt-4 border-top">
            <?php echo e($logs->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php /**PATH /var/www/resources/views/components/logs/container.blade.php ENDPATH**/ ?>