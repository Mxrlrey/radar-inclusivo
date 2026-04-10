<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name' => 'images[]',
    'label' => 'Fotos de Evidência',
    'existingImages' => [],
    'ariaLabel' => 'Escolher arquivos de imagens para upload'
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
    'name' => 'images[]',
    'label' => 'Fotos de Evidência',
    'existingImages' => [],
    'ariaLabel' => 'Escolher arquivos de imagens para upload'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $cleanId = str_replace(['[', ']'], '', $name);
?>

<div class="mb-3 image-uploader">
    
    <label for="input-<?php echo e($cleanId); ?>" class="form-label fw-bold text-primary">
        <?php echo e($label); ?>

    </label>

    
    <input type="file"
           id="input-<?php echo e($cleanId); ?>"
           name="<?php echo e($name); ?>"
           multiple
           accept="image/*"
           class="d-none"
           aria-describedby="help-<?php echo e($cleanId); ?>">

    
    <div class="preview-container d-flex flex-wrap gap-2" role="list" aria-live="polite">
        <?php $__currentLoopData = $existingImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="position-relative d-inline-block" role="listitem" style="width:70px;height:70px;">
                <a href="<?php echo e(asset('storage/' . $img)); ?>" target="_blank" class="d-block">
                    <img src="<?php echo e(asset('storage/' . $img)); ?>"
                         alt="Miniatura da imagem de evidência"
                         class="border"
                         style="width:100%;height:100%;object-fit:cover;">
                </a>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <?php if (isset($component)) { $__componentOriginal5cd5668654a8f1a77aef15d054cb27b7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5cd5668654a8f1a77aef15d054cb27b7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.buttons.link-button','data' => ['href' => 'javascript:void(0)','class' => 'mt-2 mb-3','onclick' => 'document.getElementById(\'input-'.e($cleanId).'\').click()','variant' => 'primary','label' => $ariaLabel]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('buttons.link-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => 'javascript:void(0)','class' => 'mt-2 mb-3','onclick' => 'document.getElementById(\'input-'.e($cleanId).'\').click()','variant' => 'primary','label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($ariaLabel)]); ?>
        <i class="fas fa-upload me-1"></i> Escolher Arquivos
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5cd5668654a8f1a77aef15d054cb27b7)): ?>
<?php $attributes = $__attributesOriginal5cd5668654a8f1a77aef15d054cb27b7; ?>
<?php unset($__attributesOriginal5cd5668654a8f1a77aef15d054cb27b7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5cd5668654a8f1a77aef15d054cb27b7)): ?>
<?php $component = $__componentOriginal5cd5668654a8f1a77aef15d054cb27b7; ?>
<?php unset($__componentOriginal5cd5668654a8f1a77aef15d054cb27b7); ?>
<?php endif; ?>

    
    <div id="help-<?php echo e($cleanId); ?>" class="d-block text-muted" style="font-size: 0.75rem;">
        Você pode selecionar múltiplos arquivos de imagem.
    </div>
</div>


<?php echo app('Illuminate\Foundation\Vite')('resources/js/pages/image-uploader.js'); ?>
<?php /**PATH /var/www/resources/views/components/forms/image-uploader.blade.php ENDPATH**/ ?>