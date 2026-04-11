<?php $__env->startSection('title', "$feature->name"); ?>

<?php $__env->startSection('content'); ?>
    <div class="mb-5">
        <?php if (isset($component)) { $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.breadcrumb','data' => ['items' => [
            'Home' => route('dashboard'),
            'Recursos de Acessibilidade' => route('accessibility-features.index'),
            $feature->name => null
        ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
            'Home' => route('dashboard'),
            'Recursos de Acessibilidade' => route('accessibility-features.index'),
            $feature->name => null
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

    <div class="page-header">
        <div class="page-header-title">
            <h1>Detalhes do Recurso de Acessibilidade</h1>
            <p class="text-muted">
                Visualize as informações cadastrais e status do recurso: <strong><?php echo e($feature->name); ?></strong>
            </p>
        </div>
        <div class="page-header-actions">
            <?php if (isset($component)) { $__componentOriginal5cd5668654a8f1a77aef15d054cb27b7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5cd5668654a8f1a77aef15d054cb27b7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.buttons.link-button','data' => ['href' => route('accessibility-features.edit', $feature),'variant' => 'warning']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('buttons.link-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('accessibility-features.edit', $feature)),'variant' => 'warning']); ?>
                <i class="fa fa-pencil"></i> Editar
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
            <?php if (isset($component)) { $__componentOriginal5cd5668654a8f1a77aef15d054cb27b7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5cd5668654a8f1a77aef15d054cb27b7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.buttons.link-button','data' => ['href' => route('accessibility-features.index'),'variant' => 'secondary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('buttons.link-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('accessibility-features.index')),'variant' => 'secondary']); ?>
                <i class="fa fa-arrow-left"></i> Voltar
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

    <div class="card-custom">
        <?php if (isset($component)) { $__componentOriginal289e7d811e995d6c1146b71ee55f1359 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal289e7d811e995d6c1146b71ee55f1359 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.forms.section','data' => ['title' => 'Identificação do Recurso']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('forms.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Identificação do Recurso']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal289e7d811e995d6c1146b71ee55f1359)): ?>
<?php $attributes = $__attributesOriginal289e7d811e995d6c1146b71ee55f1359; ?>
<?php unset($__attributesOriginal289e7d811e995d6c1146b71ee55f1359); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal289e7d811e995d6c1146b71ee55f1359)): ?>
<?php $component = $__componentOriginal289e7d811e995d6c1146b71ee55f1359; ?>
<?php unset($__componentOriginal289e7d811e995d6c1146b71ee55f1359); ?>
<?php endif; ?>

        <div class="row g-3">
            <?php if (isset($component)) { $__componentOriginal9f1d3c583f863b58f67d69d581aee455 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f1d3c583f863b58f67d69d581aee455 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.show.info-item','data' => ['label' => 'Nome do Recurso','column' => 'col-md-12','isBox' => 'true']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('show.info-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Nome do Recurso','column' => 'col-md-12','isBox' => 'true']); ?>
                <strong><?php echo e($feature->name); ?></strong>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9f1d3c583f863b58f67d69d581aee455)): ?>
<?php $attributes = $__attributesOriginal9f1d3c583f863b58f67d69d581aee455; ?>
<?php unset($__attributesOriginal9f1d3c583f863b58f67d69d581aee455); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9f1d3c583f863b58f67d69d581aee455)): ?>
<?php $component = $__componentOriginal9f1d3c583f863b58f67d69d581aee455; ?>
<?php unset($__componentOriginal9f1d3c583f863b58f67d69d581aee455); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginale7d0762cdc34dc0558abd88227ee2637 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale7d0762cdc34dc0558abd88227ee2637 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.show.info-textarea','data' => ['label' => 'Descrição Detalhada','column' => 'col-md-12','value' => $feature->description ?: '---','rich' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('show.info-textarea'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Descrição Detalhada','column' => 'col-md-12','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($feature->description ?: '---'),'rich' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale7d0762cdc34dc0558abd88227ee2637)): ?>
<?php $attributes = $__attributesOriginale7d0762cdc34dc0558abd88227ee2637; ?>
<?php unset($__attributesOriginale7d0762cdc34dc0558abd88227ee2637); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale7d0762cdc34dc0558abd88227ee2637)): ?>
<?php $component = $__componentOriginale7d0762cdc34dc0558abd88227ee2637; ?>
<?php unset($__componentOriginale7d0762cdc34dc0558abd88227ee2637); ?>
<?php endif; ?>
        </div>

        <?php if (isset($component)) { $__componentOriginal289e7d811e995d6c1146b71ee55f1359 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal289e7d811e995d6c1146b71ee55f1359 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.forms.section','data' => ['title' => 'Configurações de Status']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('forms.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Configurações de Status']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal289e7d811e995d6c1146b71ee55f1359)): ?>
<?php $attributes = $__attributesOriginal289e7d811e995d6c1146b71ee55f1359; ?>
<?php unset($__attributesOriginal289e7d811e995d6c1146b71ee55f1359); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal289e7d811e995d6c1146b71ee55f1359)): ?>
<?php $component = $__componentOriginal289e7d811e995d6c1146b71ee55f1359; ?>
<?php unset($__componentOriginal289e7d811e995d6c1146b71ee55f1359); ?>
<?php endif; ?>

        <div class="row g-3">
            <?php if (isset($component)) { $__componentOriginal9f1d3c583f863b58f67d69d581aee455 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f1d3c583f863b58f67d69d581aee455 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.show.info-item','data' => ['label' => 'Recurso Ativo','column' => 'col-md-12','isBox' => 'true']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('show.info-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Recurso Ativo','column' => 'col-md-12','isBox' => 'true']); ?>
                <?php echo e($feature->is_active ? 'Sim' : 'Não'); ?>

             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9f1d3c583f863b58f67d69d581aee455)): ?>
<?php $attributes = $__attributesOriginal9f1d3c583f863b58f67d69d581aee455; ?>
<?php unset($__attributesOriginal9f1d3c583f863b58f67d69d581aee455); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9f1d3c583f863b58f67d69d581aee455)): ?>
<?php $component = $__componentOriginal9f1d3c583f863b58f67d69d581aee455; ?>
<?php unset($__componentOriginal9f1d3c583f863b58f67d69d581aee455); ?>
<?php endif; ?>
        </div>

        <?php if (isset($component)) { $__componentOriginalfd3af4e23b6f818b6b027b3d64d14d4a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfd3af4e23b6f818b6b027b3d64d14d4a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.forms.form-footer','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('forms.form-footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
             <?php $__env->slot('leftContent', null, []); ?> 
                <i class="fa fa-id-card me-1" aria-hidden="true"></i> ID no Sistema: #<?php echo e($feature->id); ?>

             <?php $__env->endSlot(); ?>

            <form action="<?php echo e(route('accessibility-features.destroy', $feature)); ?>"
                  method="POST"
                  onsubmit="return confirm('Deseja excluir permanentemente?')">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <?php if (isset($component)) { $__componentOriginal8c5c9de60cf694be03f8e7259692a21b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8c5c9de60cf694be03f8e7259692a21b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.buttons.submit-button','data' => ['variant' => 'danger']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('buttons.submit-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'danger']); ?>
                    <i class="fa fa-eraser"></i> Excluir
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8c5c9de60cf694be03f8e7259692a21b)): ?>
<?php $attributes = $__attributesOriginal8c5c9de60cf694be03f8e7259692a21b; ?>
<?php unset($__attributesOriginal8c5c9de60cf694be03f8e7259692a21b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8c5c9de60cf694be03f8e7259692a21b)): ?>
<?php $component = $__componentOriginal8c5c9de60cf694be03f8e7259692a21b; ?>
<?php unset($__componentOriginal8c5c9de60cf694be03f8e7259692a21b); ?>
<?php endif; ?>
            </form>

            <?php if (isset($component)) { $__componentOriginal5cd5668654a8f1a77aef15d054cb27b7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5cd5668654a8f1a77aef15d054cb27b7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.buttons.link-button','data' => ['href' => route('accessibility-features.index'),'variant' => 'secondary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('buttons.link-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('accessibility-features.index')),'variant' => 'secondary']); ?>
                <i class="fa fa-arrow-left"></i> Voltar
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
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfd3af4e23b6f818b6b027b3d64d14d4a)): ?>
<?php $attributes = $__attributesOriginalfd3af4e23b6f818b6b027b3d64d14d4a; ?>
<?php unset($__attributesOriginalfd3af4e23b6f818b6b027b3d64d14d4a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfd3af4e23b6f818b6b027b3d64d14d4a)): ?>
<?php $component = $__componentOriginalfd3af4e23b6f818b6b027b3d64d14d4a; ?>
<?php unset($__componentOriginalfd3af4e23b6f818b6b027b3d64d14d4a); ?>
<?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/pages/accessibility-features/show.blade.php ENDPATH**/ ?>