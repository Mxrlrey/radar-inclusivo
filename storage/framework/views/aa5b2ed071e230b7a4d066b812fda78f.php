<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'headers' => [],
    'tableClass' => 'table table-hover mb-0',
    'records' => null
]));

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

foreach (array_filter(([
    'headers' => [],
    'tableClass' => 'table table-hover mb-0',
    'records' => null
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="table-container">
    <div class="table-responsive">
        <table <?php echo e($attributes->merge(['class' => $tableClass . ' w-100'])); ?>>
            <thead>
            <tr>
                <?php $__currentLoopData = $headers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $header): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if (isset($component)) { $__componentOriginal1291f4dfa458a95c228b06c14d34e160 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1291f4dfa458a95c228b06c14d34e160 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.th','data' => ['class' => $header['class'] ?? null]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.th'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($header['class'] ?? null)]); ?>
                        <?php echo e($header['label'] ?? $header); ?>

                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1291f4dfa458a95c228b06c14d34e160)): ?>
<?php $attributes = $__attributesOriginal1291f4dfa458a95c228b06c14d34e160; ?>
<?php unset($__attributesOriginal1291f4dfa458a95c228b06c14d34e160); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1291f4dfa458a95c228b06c14d34e160)): ?>
<?php $component = $__componentOriginal1291f4dfa458a95c228b06c14d34e160; ?>
<?php unset($__componentOriginal1291f4dfa458a95c228b06c14d34e160); ?>
<?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tr>
            </thead>
            <tbody>
            <?php echo e($slot); ?>

            </tbody>
        </table>
    </div>

    <?php if($records instanceof \Illuminate\Pagination\LengthAwarePaginator && $records->hasPages()): ?>
        <div class="px-4 py-3 border-top d-flex justify-content-between align-items-center bg-white custom-pagination-container">
            <div class="text-muted small fw-medium">
                Mostrando <span class="text-primary"><?php echo e($records->firstItem()); ?></span>
                - <span class="text-primary"><?php echo e($records->lastItem()); ?></span>
                de <span class="text-primary"><?php echo e($records->total()); ?></span>
            </div>

            <nav>
                <?php echo e($records->links('pagination::bootstrap-4')); ?>

            </nav>
        </div>
    <?php endif; ?>
</div>
<?php /**PATH /var/www/resources/views/components/table/table.blade.php ENDPATH**/ ?>