<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'color' => 'light',
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
    'color' => 'light',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $style = $color === 'dark'
        ? 'background-color: var(--color-primary); color: var(--text-on-primary); border-color: var(--color-primary);'
        : 'background-color: var(--bg-soft-primary); color: var(--color-primary); border-color: var(--color-primary);';
?>

<span <?php echo e($attributes->merge(['class' => 'tag-item', 'style' => $style])); ?>>
    <?php echo e($slot); ?>

</span>
<?php /**PATH /var/www/resources/views/components/show/tag.blade.php ENDPATH**/ ?>