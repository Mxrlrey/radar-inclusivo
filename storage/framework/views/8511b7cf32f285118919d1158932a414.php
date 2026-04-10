<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'href' => null,
    'variant' => 'primary',
    'size' => 'md',
    'label' => null,
    'type' => 'button'
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
    'href' => null,
    'variant' => 'primary',
    'size' => 'md',
    'label' => null,
    'type' => 'button'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    // Mapeia o tamanho para a classe correta (sm, lg, ou vazio para md)
    $sizeClass = match ($size) {
        'sm' => 'sm',
        'lg' => 'lg',
        default => '',
    };
    $classes = "btn-action {$variant} {$sizeClass}";
    $tag = $href ? 'a' : 'button';
?>

<<?php echo e($tag); ?>

    <?php echo e($href ? "href=$href role=button" : "type=$type"); ?>

    <?php echo e($attributes->merge([
        'class' => $classes,
        'aria-label' => $label ?? strip_tags($slot)
    ])); ?>

>
    <?php echo e($slot); ?>

</<?php echo e($tag); ?>>
<?php /**PATH /var/www/resources/views/components/buttons/link-button.blade.php ENDPATH**/ ?>