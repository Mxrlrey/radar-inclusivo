<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'fields' => []
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
    'fields' => []
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<form <?php echo e($attributes->merge([
    'class' => 'search-wrapper'
])); ?>>

    <div class="search-filters-row">

        <?php $__currentLoopData = $fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            <?php if (isset($component)) { $__componentOriginal94deaaa0f7d455da835a00166fe0d7ce = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal94deaaa0f7d455da835a00166fe0d7ce = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.filters.group','data' => ['label' => $field['label'] ?? null]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.filters.group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($field['label'] ?? null)]); ?>

                <?php if(($field['type'] ?? 'text') === 'select'): ?>
                    <?php if (isset($component)) { $__componentOriginal7f1836f4ea687b7d2f00f300e5bd4cef = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7f1836f4ea687b7d2f00f300e5bd4cef = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.filters.select','data' => ['name' => $field['name'],'options' => $field['options']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.filters.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($field['name']),'options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($field['options'])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7f1836f4ea687b7d2f00f300e5bd4cef)): ?>
<?php $attributes = $__attributesOriginal7f1836f4ea687b7d2f00f300e5bd4cef; ?>
<?php unset($__attributesOriginal7f1836f4ea687b7d2f00f300e5bd4cef); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7f1836f4ea687b7d2f00f300e5bd4cef)): ?>
<?php $component = $__componentOriginal7f1836f4ea687b7d2f00f300e5bd4cef; ?>
<?php unset($__componentOriginal7f1836f4ea687b7d2f00f300e5bd4cef); ?>
<?php endif; ?>
                <?php else: ?>
                    <?php if (isset($component)) { $__componentOriginal4d3faafa4ee2d06c283f5ba28816da37 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4d3faafa4ee2d06c283f5ba28816da37 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.filters.input','data' => ['name' => $field['name'],'placeholder' => $field['placeholder'] ?? '']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.filters.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($field['name']),'placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($field['placeholder'] ?? '')]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4d3faafa4ee2d06c283f5ba28816da37)): ?>
<?php $attributes = $__attributesOriginal4d3faafa4ee2d06c283f5ba28816da37; ?>
<?php unset($__attributesOriginal4d3faafa4ee2d06c283f5ba28816da37); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4d3faafa4ee2d06c283f5ba28816da37)): ?>
<?php $component = $__componentOriginal4d3faafa4ee2d06c283f5ba28816da37; ?>
<?php unset($__componentOriginal4d3faafa4ee2d06c283f5ba28816da37); ?>
<?php endif; ?>
                <?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal94deaaa0f7d455da835a00166fe0d7ce)): ?>
<?php $attributes = $__attributesOriginal94deaaa0f7d455da835a00166fe0d7ce; ?>
<?php unset($__attributesOriginal94deaaa0f7d455da835a00166fe0d7ce); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal94deaaa0f7d455da835a00166fe0d7ce)): ?>
<?php $component = $__componentOriginal94deaaa0f7d455da835a00166fe0d7ce; ?>
<?php unset($__componentOriginal94deaaa0f7d455da835a00166fe0d7ce); ?>
<?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php if (isset($component)) { $__componentOriginala9440f8d81a306b1654b5405bc22d292 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala9440f8d81a306b1654b5405bc22d292 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.filters.clear','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.filters.clear'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala9440f8d81a306b1654b5405bc22d292)): ?>
<?php $attributes = $__attributesOriginala9440f8d81a306b1654b5405bc22d292; ?>
<?php unset($__attributesOriginala9440f8d81a306b1654b5405bc22d292); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala9440f8d81a306b1654b5405bc22d292)): ?>
<?php $component = $__componentOriginala9440f8d81a306b1654b5405bc22d292; ?>
<?php unset($__componentOriginala9440f8d81a306b1654b5405bc22d292); ?>
<?php endif; ?>
    </div>
</form>
<?php /**PATH /var/www/resources/views/components/table/filters/form.blade.php ENDPATH**/ ?>