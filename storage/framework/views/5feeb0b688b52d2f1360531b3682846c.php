<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'variant' => 'primary',
    'size' => 'md',
    'label' => null,
    'disabled' => false,
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
    'variant' => 'primary',
    'size' => 'md',
    'label' => null,
    'disabled' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $sizeClass = match ($size) {
        'sm' => 'sm',
        'lg' => 'lg',
        default => '',
    };
    $classes = "btn-action {$variant} {$sizeClass} d-inline-flex align-items-center justify-content-center";
?>

<button
    type="submit"
    <?php echo e($attributes->merge([
        'class' => $classes,
        'aria-label' => $label ?? 'Enviar formulário',
        'disabled' => $disabled,
    ])); ?>

>
    <?php echo e($slot); ?>

</button>
<?php /**PATH /var/www/resources/views/components/buttons/submit-button.blade.php ENDPATH**/ ?>