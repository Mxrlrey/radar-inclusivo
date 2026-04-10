<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['inspection']));

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

foreach (array_filter((['inspection']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $isBarrier = str_contains($inspection->inspectable_type, 'barrier');
?>

<div <?php echo e($attributes->merge(['class' => 'card mb-3 overflow-hidden'])); ?>>
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-2 border-bottom-0">
        <span class="badge px-3" style="background-color: var(--color-primary); color: white;">
            <?php echo e($inspection->inspection_date->format('d/m/Y')); ?>

        </span>
        <span class="text-uppercase fw-bold small text-muted" style="letter-spacing: 1px;">
            <?php echo e($inspection->type->label()); ?>

        </span>
    </div>

    <div class="card-body pt-0 pb-3">
        <div class="row g-0">
            <div class="col-md-7 border-end pe-4">
                <div class="pt-3">
                    <?php if($isBarrier): ?>
                        <span class="d-block text-muted uppercase fw-bold mb-2" style="font-size: 0.65rem; line-height: 1;">
                            Status da Barreira
                        </span>
                        <div class="d-flex align-items-center gap-2">
                            <span class="fw-bold text-primary fs-5 <?php echo e($inspection->status?->color()); ?>">
                                <?php echo e($inspection->status?->label() ?? 'Identificada'); ?>

                            </span>
                        </div>
                    <?php else: ?>
                        <span class="d-block text-muted uppercase fw-bold mb-2" style="font-size: 0.65rem; line-height: 1;">
                            Estado de Conservação
                        </span>
                        <div class="d-flex align-items-center gap-2">
                            <span class="fw-bold text-primary fs-5">
                                <?php echo e($inspection->state?->label() ?? '---'); ?>

                            </span>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if($inspection->description): ?>
                    <div class="mt-3">
                        <span class="d-block text-muted uppercase fw-bold mb-2" style="font-size: 0.65rem; line-height: 1; text-transform: uppercase;">
                            Parecer Técnico / Descrição
                        </span>

                        <div class="history-description-text">
                            <?php echo $inspection->description; ?>

                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-md-5 ps-md-4">
                <div class="pt-3">
                    <span class="d-block text-muted uppercase fw-bold mb-2" style="font-size: 0.65rem; line-height: 1;">
                        Evidências Visuais
                    </span>

                    <?php if($inspection->images && $inspection->images->count() > 0): ?>
                        <?php if($isBarrier): ?>
                            <div class="row g-2 pt-1">
                                <?php $__currentLoopData = $inspection->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-4">
                                        <div class="position-relative" style="aspect-ratio: 1/1;">
                                            <a href="<?php echo e(asset('storage/' . $img->path)); ?>" target="_blank">
                                                <img src="<?php echo e(asset('storage/' . $img->path)); ?>"
                                                     class="border w-100 h-100"
                                                     alt="Foto de evidência da vistoria"
                                                     style="object-fit:cover;"
                                                >
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <div class="d-flex flex-wrap gap-2 pt-1">
                                <?php $__currentLoopData = $inspection->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="position-relative d-inline-block" style="width:70px; height:70px;">
                                        <a href="<?php echo e(asset('storage/' . $img->path)); ?>" target="_blank">
                                            <img src="<?php echo e(asset('storage/' . $img->path)); ?>"
                                                 class="border"
                                                 alt="Foto de evidência da vistoria"
                                                 style="width:100%; height:100%; object-fit:cover;"
                                            >
                                        </a>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center py-3 bg-light border mt-1">
                            <span class="text-muted small" style="font-size:0.7rem;">
                                Nenhuma foto registrada
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH /var/www/resources/views/components/forms/inspection-history-card.blade.php ENDPATH**/ ?>