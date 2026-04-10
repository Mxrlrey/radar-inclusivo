<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['label', 'value' => null, 'column' => 'col-md-6', 'isBox' => false]));

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

foreach (array_filter((['label', 'value' => null, 'column' => 'col-md-6', 'isBox' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div <?php echo e($attributes->merge(['class' => $column . ' mb-4 px-4'])); ?>>
    
    <span class="d-block fw-bold text-primary small mb-1 text-uppercase" aria-hidden="true">
        <?php echo e($label); ?>

    </span>

    <?php
        $displayValue = $slot->isNotEmpty() ? $slot : ($value ?? '---');
        $plainTextValue = strip_tags($displayValue);
    ?>

    <div class="<?php echo e($isBox ? 'custom-display-box' : 'text-base'); ?>"
         style="<?php echo e(!$isBox ? 'color: var(--text-primary); font-size: 1.05rem;' : ''); ?>"
         role="text"
         aria-label="<?php echo e($label); ?>: <?php echo e($plainTextValue); ?>">
        <?php echo e($displayValue); ?>

    </div>
</div>
<?php /**PATH /var/www/resources/views/components/show/info-item.blade.php ENDPATH**/ ?>