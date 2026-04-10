<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['href']));

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

foreach (array_filter((['href']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<a href="<?php echo e($href); ?>"
   target="_blank"
   aria-label="Exportar em PDF"
    <?php echo e($attributes->merge(['class' => 'btn-pdf-custom'])); ?>>
    <i class="fas fa-file-pdf"></i>
</a>

<style>
    .btn-pdf-custom {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background-color: #e02424; /* Vermelho PDF */
        color: white !important;
        padding: 8px 15px;
        border: 1px solid #000000; /* Bordinha preta fina */
        border-radius: 4px; /* Quadradinho com leve arredondamento */
        text-decoration: none;
        font-weight: bold;
        font-size: 0.85rem;
        transition: all 0.2s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .btn-pdf-custom:hover {
        background-color: #c81e1e;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.15);
    }

    .btn-pdf-custom i {
        font-size: 1.1rem;
    }
</style>
<?php /**PATH /var/www/resources/views/components/buttons/pdf-button.blade.php ENDPATH**/ ?>