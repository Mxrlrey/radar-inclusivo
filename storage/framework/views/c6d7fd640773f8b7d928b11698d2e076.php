<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'label',
    'value' => null,
    'column' => 'col-md-12',
    'rich' => true
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
    'label',
    'value' => null,
    'column' => 'col-md-12',
    'rich' => true
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div <?php echo e($attributes->merge(['class' => $column . ' mb-4 px-4'])); ?>>
    <label class="d-block fw-bold text-primary small mb-2">
        <?php echo e($label); ?>

    </label>

    <div class="custom-display-box-textarea">
        <?php
            $content = $slot->isNotEmpty() ? $slot : ($value ?? '---');
        ?>

        <div class="textarea-content-wrapper">
            <?php if($rich): ?>
                <?php echo $content; ?>

            <?php else: ?>
                <?php echo nl2br(e($content)); ?>

            <?php endif; ?>
        </div>
    </div>
</div>
<?php /**PATH /var/www/resources/views/components/show/info-textarea.blade.php ENDPATH**/ ?>