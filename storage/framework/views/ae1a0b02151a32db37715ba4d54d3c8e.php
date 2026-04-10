<?php $__env->startSection('title', "Histórico - $assistiveTechnology->name"); ?>

<?php $__env->startSection('content'); ?>
    <div class="mb-4">
        <?php if (isset($component)) { $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.breadcrumb','data' => ['items' => [
            'Home' => route('dashboard'),
            'Tecnologias Assistivas' => route('assistive-technologies.index'),
            $assistiveTechnology->name => route('assistive-technologies.show', $assistiveTechnology),
            'Histórico de Alterações' => null
        ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
            'Home' => route('dashboard'),
            'Tecnologias Assistivas' => route('assistive-technologies.index'),
            $assistiveTechnology->name => route('assistive-technologies.show', $assistiveTechnology),
            'Histórico de Alterações' => null
        ])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2)): ?>
<?php $attributes = $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2; ?>
<?php unset($__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale19f62b34dfe0bfdf95075badcb45bc2)): ?>
<?php $component = $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2; ?>
<?php unset($__componentOriginale19f62b34dfe0bfdf95075badcb45bc2); ?>
<?php endif; ?>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-title">Histórico de Alterações</h2>
            <p class="text-muted mb-1">
                Rastreabilidade de:
                <strong><?php echo e($assistiveTechnology->name); ?></strong>
            </p>

            <div class="d-flex align-items-center gap-2">
                <span class="text-muted small text-uppercase fw-bold">
                    Registros
                </span>
                <span class="badge bg-purple fs-6">
                    <?php echo e($logs->total()); ?>

                </span>
            </div>
        </div>

        <div class="d-flex gap-2">
            <?php if (isset($component)) { $__componentOriginal5cd5668654a8f1a77aef15d054cb27b7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5cd5668654a8f1a77aef15d054cb27b7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.buttons.link-button','data' => ['href' => ''.e(route('assistive-technologies.show', $assistiveTechnology)).'','variant' => 'secondary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('buttons.link-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('assistive-technologies.show', $assistiveTechnology)).'','variant' => 'secondary']); ?>
                <i class="fas fa-arrow-left"></i> Voltar
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
        </div>
    </div>
    <?php if (isset($component)) { $__componentOriginal1506640349aa9d4a684dc650d5bac3e4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1506640349aa9d4a684dc650d5bac3e4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.logs.container','data' => ['logs' => $logs]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('logs.container'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['logs' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($logs)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1506640349aa9d4a684dc650d5bac3e4)): ?>
<?php $attributes = $__attributesOriginal1506640349aa9d4a684dc650d5bac3e4; ?>
<?php unset($__attributesOriginal1506640349aa9d4a684dc650d5bac3e4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1506640349aa9d4a684dc650d5bac3e4)): ?>
<?php $component = $__componentOriginal1506640349aa9d4a684dc650d5bac3e4; ?>
<?php unset($__componentOriginal1506640349aa9d4a684dc650d5bac3e4); ?>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/pages/assistive-technologies/logs/logs.blade.php ENDPATH**/ ?>