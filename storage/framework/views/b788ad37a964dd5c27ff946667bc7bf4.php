<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name',
    'label',
    'value' => 1,
    'checked' => false,
    'description' => null,
    'id' => null,
    'required' => false
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
    'name',
    'label',
    'value' => 1,
    'checked' => false,
    'description' => null,
    'id' => null,
    'required' => false
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $checkboxId = $id ?? $name;
?>

<div <?php echo e($attributes->merge(['class' => 'custom-checkbox-wrapper'])); ?>>
    <input
        type="checkbox"
        name="<?php echo e($name); ?>"
        id="<?php echo e($checkboxId); ?>"
        value="<?php echo e($value); ?>"
        <?php echo e($checked ? 'checked' : ''); ?>

        class="form-check-input custom-checkbox"
    >

    <label class="form-check-label" for="<?php echo e($checkboxId); ?>">
        <span class="fw-bold text-primary">
            <?php echo e($label); ?>

            <?php if($required): ?>
                <span class="text-danger">*</span>
            <?php endif; ?>
        </span>

        <?php if($description): ?>
            <small class="d-block text-muted" style="font-size: 0.75rem;">
                <?php echo e($description); ?>

            </small>
        <?php endif; ?>
    </label>
</div>
<?php /**PATH /var/www/resources/views/components/forms/checkbox.blade.php ENDPATH**/ ?>