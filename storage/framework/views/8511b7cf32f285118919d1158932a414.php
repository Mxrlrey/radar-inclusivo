<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'href' => null,
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
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
    'icon' => null,
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
    $sizeClass = match ($size) {
        'sm' => 'sm',
        'lg' => 'lg',
        'xs' => 'xs',
        default => '',
    };

    $hasIcon = !empty($icon);
    $hasText = trim($slot) !== '';

    $classes = "btn-action {$variant} {$sizeClass} waves-effect";
    $tag = $href ? 'a' : 'button';
?>

<<?php echo e($tag); ?>

    <?php echo e($href ? "href=$href role=button" : "type=$type"); ?>

    <?php echo e($attributes->merge([
        'class' => $classes,
    ])); ?>

>
<?php if($hasIcon): ?>
    <?php if($hasText): ?>
        <span class="btn-label"><?php echo e($icon); ?></span>
    <?php else: ?>
        <?php echo e($icon); ?>

    <?php endif; ?>
<?php endif; ?>

<?php if($hasText): ?>
    <?php echo e($slot); ?>

<?php endif; ?>
</<?php echo e($tag); ?>>
<?php /**PATH /var/www/resources/views/components/buttons/link-button.blade.php ENDPATH**/ ?>