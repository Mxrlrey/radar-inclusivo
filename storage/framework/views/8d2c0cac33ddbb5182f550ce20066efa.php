<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title',
    'value',
    'icon' => 'bi-people',
    'color' => 'primary',
    'href' => null,
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
    'title',
    'value',
    'icon' => 'bi-people',
    'color' => 'primary',
    'href' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $tag = $href ? 'a' : 'div';
    $extraAttributes = [];
    if ($href) $extraAttributes['href'] = $href;
?>

<<?php echo e($tag); ?>

    <?php echo e($attributes->merge(array_merge(['class' => 'stat-widget'], $extraAttributes))); ?>

>
<div class="stat-widget-content">
    <span class="stat-widget-value"><?php echo e($value); ?></span>
    <span class="stat-widget-title"><?php echo e($title); ?></span>
</div>
<div class="stat-widget-icon text-<?php echo e($color); ?>">
    <i class="<?php echo e($icon); ?>"></i>
</div>
</<?php echo e($tag); ?>>
<?php /**PATH /var/www/resources/views/components/stat-widget.blade.php ENDPATH**/ ?>