<?php $__env->startSection('title', $assistiveTechnology->name); ?>

<?php $__env->startSection('content'); ?>
    <div class="mb-5">
        <?php if (isset($component)) { $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.breadcrumb','data' => ['items' => [
            'Home' => route('dashboard'),
            'Tecnologias Assistivas' => route('assistive-technologies.index'),
            $assistiveTechnology->name => null
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
            $assistiveTechnology->name => null
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

    <div class="d-flex justify-content-between mb-3 align-items-center">
        <header>
            <h2 class="text-title">Detalhes da Tecnologia Assistiva</h2>
            <p class="text-muted mb-0">
                Visualize informações cadastrais, histórico de vistorias, treinamentos e gestão do item.
            </p>
        </header>

        <div role="group" aria-label="Ações principais">
            <?php if (isset($component)) { $__componentOriginal5cd5668654a8f1a77aef15d054cb27b7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5cd5668654a8f1a77aef15d054cb27b7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.buttons.link-button','data' => ['href' => route('assistive-technologies.edit', $assistiveTechnology),'variant' => 'warning']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('buttons.link-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('assistive-technologies.edit', $assistiveTechnology)),'variant' => 'warning']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.buttons.link-button','data' => ['href' => route('assistive-technologies.index'),'variant' => 'secondary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('buttons.link-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('assistive-technologies.index')),'variant' => 'secondary']); ?>
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

    <div class="mt-3">
        <main class="custom-table-card bg-white shadow-sm">

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

            <div class="row g-3 px-4 pb-4">
                <?php if (isset($component)) { $__componentOriginal9f1d3c583f863b58f67d69d581aee455 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f1d3c583f863b58f67d69d581aee455 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.show.info-item','data' => ['label' => 'Tipo da Tecnologia','column' => 'col-md-12','isBox' => 'true']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('show.info-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Tipo da Tecnologia','column' => 'col-md-12','isBox' => 'true']); ?>
                    <?php echo e($assistiveTechnology->name); ?>

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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.show.info-textarea','data' => ['label' => 'Descrição Detalhada','column' => 'col-md-12','value' => $assistiveTechnology->notes ?: '---','rich' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('show.info-textarea'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Descrição Detalhada','column' => 'col-md-12','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($assistiveTechnology->notes ?: '---'),'rich' => true]); ?>
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

                <?php if (isset($component)) { $__componentOriginal9f1d3c583f863b58f67d69d581aee455 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f1d3c583f863b58f67d69d581aee455 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.show.info-item','data' => ['label' => 'Natureza do Recurso','column' => 'col-md-6','isBox' => 'true']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('show.info-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Natureza do Recurso','column' => 'col-md-6','isBox' => 'true']); ?>
                    <?php echo e($assistiveTechnology->is_digital ? 'Recurso Digital' : 'Recurso Físico'); ?>

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

                <?php if (isset($component)) { $__componentOriginal9f1d3c583f863b58f67d69d581aee455 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f1d3c583f863b58f67d69d581aee455 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.show.info-item','data' => ['label' => 'Patrimônio / Tombamento','column' => 'col-md-6','isBox' => 'true']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('show.info-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Patrimônio / Tombamento','column' => 'col-md-6','isBox' => 'true']); ?>
                    <?php echo e($assistiveTechnology->asset_code ?? 'Não se Aplica'); ?>

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

            <?php if (isset($component)) { $__componentOriginal289e7d811e995d6c1146b71ee55f1359 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal289e7d811e995d6c1146b71ee55f1359 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.forms.section','data' => ['title' => 'Histórico de Vistorias']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('forms.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Histórico de Vistorias']); ?>
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

            <div class="history-timeline p-4 border border-secondary-subtle rounded bg-white" style="max-height: 450px; overflow-y:auto;">
                <?php $__empty_1 = true; $__currentLoopData = $inspections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inspection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="mb-3 cursor-pointer p-2 rounded border shadow-sm hover-shadow"
                         role="button"
                         tabindex="0"
                         data-url="<?php echo e(route('assistive-technologies.inspection.show', [$assistiveTechnology, $inspection])); ?>"
                         aria-label="Ver detalhes da vistoria de <?php echo e($inspection->inspection_date->format('d/m/Y')); ?>">
                        <?php if (isset($component)) { $__componentOriginal158115db38720691e0086bfc921ccf41 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal158115db38720691e0086bfc921ccf41 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.forms.inspection-history-card','data' => ['inspection' => $inspection]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('forms.inspection-history-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['inspection' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($inspection)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal158115db38720691e0086bfc921ccf41)): ?>
<?php $attributes = $__attributesOriginal158115db38720691e0086bfc921ccf41; ?>
<?php unset($__attributesOriginal158115db38720691e0086bfc921ccf41); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal158115db38720691e0086bfc921ccf41)): ?>
<?php $component = $__componentOriginal158115db38720691e0086bfc921ccf41; ?>
<?php unset($__componentOriginal158115db38720691e0086bfc921ccf41); ?>
<?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-5 bg-light rounded border border-dashed">
                        <i class="fas fa-clipboard-list fa-2x text-secondary mb-2"></i>
                        <p class="fw-bold text-dark mb-0">Nenhum histórico de vistoria encontrado.</p>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (isset($component)) { $__componentOriginal289e7d811e995d6c1146b71ee55f1359 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal289e7d811e995d6c1146b71ee55f1359 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.forms.section','data' => ['title' => 'Gestão e Público']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('forms.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Gestão e Público']); ?>
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

            <div class="row g-3 px-4 pb-4">
                <?php if (isset($component)) { $__componentOriginal9f1d3c583f863b58f67d69d581aee455 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f1d3c583f863b58f67d69d581aee455 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.show.info-item','data' => ['label' => 'Quantidade Total','column' => 'col-md-6','isBox' => 'true','value' => $assistiveTechnology->quantity]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('show.info-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Quantidade Total','column' => 'col-md-6','isBox' => 'true','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($assistiveTechnology->quantity)]); ?>
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
                <?php if (isset($component)) { $__componentOriginal9f1d3c583f863b58f67d69d581aee455 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f1d3c583f863b58f67d69d581aee455 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.show.info-item','data' => ['label' => 'Quantidade Disponível','column' => 'col-md-6','isBox' => 'true','value' => $assistiveTechnology->quantity_available ?? '---']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('show.info-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Quantidade Disponível','column' => 'col-md-6','isBox' => 'true','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($assistiveTechnology->quantity_available ?? '---')]); ?>
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

                <?php if (isset($component)) { $__componentOriginal9f1d3c583f863b58f67d69d581aee455 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f1d3c583f863b58f67d69d581aee455 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.show.info-item','data' => ['label' => 'Status do Recurso','column' => 'col-md-4','isBox' => 'true']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('show.info-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Status do Recurso','column' => 'col-md-4','isBox' => 'true']); ?>
                    <span class="fw-bold text-<?php echo e($assistiveTechnology->status?->color() ?? 'secondary'); ?> text-uppercase">
                        <?php echo e($assistiveTechnology->status?->label() ?? '---'); ?>

                    </span>
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

                <?php if (isset($component)) { $__componentOriginal9f1d3c583f863b58f67d69d581aee455 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f1d3c583f863b58f67d69d581aee455 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.show.info-item','data' => ['label' => 'Permite Empréstimos','column' => 'col-md-4','isBox' => 'true']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('show.info-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Permite Empréstimos','column' => 'col-md-4','isBox' => 'true']); ?>
                    <span class="text-<?php echo e($assistiveTechnology->is_loanable ? 'success' : 'secondary'); ?> fw-bold text-uppercase">
                        <?php echo e($assistiveTechnology->is_loanable ? 'Sim' : 'Não'); ?>

                    </span>
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

                <?php if (isset($component)) { $__componentOriginal9f1d3c583f863b58f67d69d581aee455 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f1d3c583f863b58f67d69d581aee455 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.show.info-item','data' => ['label' => 'Status no Sistema','column' => 'col-md-4','isBox' => 'true']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('show.info-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Status no Sistema','column' => 'col-md-4','isBox' => 'true']); ?>
                    <span class="text-<?php echo e($assistiveTechnology->is_active ? 'success' : 'secondary'); ?> fw-bold text-uppercase">
                        <?php echo e($assistiveTechnology->is_active ? 'Ativo' : 'Inativo'); ?>

                    </span>
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

                <?php if (isset($component)) { $__componentOriginal9f1d3c583f863b58f67d69d581aee455 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f1d3c583f863b58f67d69d581aee455 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.show.info-item','data' => ['label' => 'Público-alvo (Deficiências Atendidas)','column' => 'col-md-12','isBox' => 'true']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('show.info-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Público-alvo (Deficiências Atendidas)','column' => 'col-md-12','isBox' => 'true']); ?>
                    <div class="tag-container">
                        <?php $__empty_1 = true; $__currentLoopData = $deficiencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $def): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php if (isset($component)) { $__componentOriginal9189db18a24af3faa05762aaceb1069a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9189db18a24af3faa05762aaceb1069a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.show.tag','data' => ['color' => 'light']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('show.tag'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['color' => 'light']); ?><?php echo e($def->name); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9189db18a24af3faa05762aaceb1069a)): ?>
<?php $attributes = $__attributesOriginal9189db18a24af3faa05762aaceb1069a; ?>
<?php unset($__attributesOriginal9189db18a24af3faa05762aaceb1069a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9189db18a24af3faa05762aaceb1069a)): ?>
<?php $component = $__componentOriginal9189db18a24af3faa05762aaceb1069a; ?>
<?php unset($__componentOriginal9189db18a24af3faa05762aaceb1069a); ?>
<?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <span class="text-muted">Nenhum público-alvo definido.</span>
                        <?php endif; ?>
                    </div>
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

            <footer class="col-12 border-top p-4 d-flex justify-content-between align-items-center bg-light-subtle">
                <div class="text-muted small">
                    ID no Sistema: #<?php echo e($assistiveTechnology->id); ?>

                    <?php if (isset($component)) { $__componentOriginaldbdbd75a06f6617c2bbe04ed538940a8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldbdbd75a06f6617c2bbe04ed538940a8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.buttons.pdf-button','data' => ['href' => route('assistive-technologies.pdf', $assistiveTechnology),'class' => 'ms-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('buttons.pdf-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('assistive-technologies.pdf', $assistiveTechnology)),'class' => 'ms-1']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldbdbd75a06f6617c2bbe04ed538940a8)): ?>
<?php $attributes = $__attributesOriginaldbdbd75a06f6617c2bbe04ed538940a8; ?>
<?php unset($__attributesOriginaldbdbd75a06f6617c2bbe04ed538940a8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldbdbd75a06f6617c2bbe04ed538940a8)): ?>
<?php $component = $__componentOriginaldbdbd75a06f6617c2bbe04ed538940a8; ?>
<?php unset($__componentOriginaldbdbd75a06f6617c2bbe04ed538940a8); ?>
<?php endif; ?>
                </div>
                <div class="d-flex gap-2">
                    <?php if (isset($component)) { $__componentOriginal5cd5668654a8f1a77aef15d054cb27b7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5cd5668654a8f1a77aef15d054cb27b7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.buttons.link-button','data' => ['href' => route('assistive-technologies.logs', $assistiveTechnology),'variant' => 'secondary-outline']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('buttons.link-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('assistive-technologies.logs', $assistiveTechnology)),'variant' => 'secondary-outline']); ?>
                        <i class="fas fa-history"></i> Logs
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

                    <form action="<?php echo e(route('assistive-technologies.destroy', $assistiveTechnology)); ?>" method="POST" onsubmit="return confirm('Deseja excluir permanentemente?')">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
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
                            <i class="fas fa-trash-alt"></i> Excluir
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.buttons.link-button','data' => ['href' => route('assistive-technologies.index'),'variant' => 'secondary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('buttons.link-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('assistive-technologies.index')),'variant' => 'secondary']); ?>
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
            </footer>
        </main>
    </div>
    <?php echo app('Illuminate\Foundation\Vite')('resources/js/pages/assistive-technologies.js'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/pages/assistive-technologies/show.blade.php ENDPATH**/ ?>