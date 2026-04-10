<?php $__env->startSection('title', 'Barreiras'); ?>

<?php $__env->startSection('content'); ?>
    <div class="mb-5">
        <?php if (isset($component)) { $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.breadcrumb','data' => ['items' => [
            'Home' => route('dashboard'),
            'Barreiras' => route('barriers.index'),
        ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
            'Home' => route('dashboard'),
            'Barreiras' => route('barriers.index'),
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

    <div class="custom-table-card shadow-sm border rounded-3 overflow-hidden">
        <?php if (isset($component)) { $__componentOriginal2beb5d40d67dc2dc6f501ca45725f185 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2beb5d40d67dc2dc6f501ca45725f185 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.page-header','data' => ['title' => 'Mapa de Barreiras','subtitle' => 'Contribuições da comunidade para uma instituição mais acessível.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Mapa de Barreiras','subtitle' => 'Contribuições da comunidade para uma instituição mais acessível.']); ?>
            <?php if (isset($component)) { $__componentOriginal5cd5668654a8f1a77aef15d054cb27b7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5cd5668654a8f1a77aef15d054cb27b7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.buttons.link-button','data' => ['href' => route('barriers.create'),'variant' => 'new','title' => 'Adicionar Barreiras']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('buttons.link-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('barriers.create')),'variant' => 'new','title' => 'Adicionar Barreiras']); ?>
                <i class="fas fa-plus"></i>
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
<?php if (isset($__attributesOriginal2beb5d40d67dc2dc6f501ca45725f185)): ?>
<?php $attributes = $__attributesOriginal2beb5d40d67dc2dc6f501ca45725f185; ?>
<?php unset($__attributesOriginal2beb5d40d67dc2dc6f501ca45725f185); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2beb5d40d67dc2dc6f501ca45725f185)): ?>
<?php $component = $__componentOriginal2beb5d40d67dc2dc6f501ca45725f185; ?>
<?php unset($__componentOriginal2beb5d40d67dc2dc6f501ca45725f185); ?>
<?php endif; ?>

        <div class="px-3 pt-3">
            <?php if (isset($component)) { $__componentOriginald1c029f02addcde30ed6072f3aa4cb40 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald1c029f02addcde30ed6072f3aa4cb40 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.filters.form','data' => ['dataDynamicFilter' => true,'dataTarget' => '#barriers-table','fields' => [
                    ['name' => 'name', 'placeholder' => 'Filtrar por nome da barreira...'],
                    ['name' => 'category', 'placeholder' => 'Filtrar por categoria...'],
                    ['name' => 'priority', 'type' => 'select', 'options' => collect(\App\Enums\Priority::cases())
                        ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
                        ->prepend('Prioridade (Todas)', '')
                        ->toArray()],
                    ['name' => 'status', 'type' => 'select', 'options' => collect(\App\Enums\BarrierStatus::cases())
                        ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
                        ->prepend('Status (Todos)', '')
                        ->toArray()],
                ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.filters.form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data-dynamic-filter' => true,'data-target' => '#barriers-table','fields' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                    ['name' => 'name', 'placeholder' => 'Filtrar por nome da barreira...'],
                    ['name' => 'category', 'placeholder' => 'Filtrar por categoria...'],
                    ['name' => 'priority', 'type' => 'select', 'options' => collect(\App\Enums\Priority::cases())
                        ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
                        ->prepend('Prioridade (Todas)', '')
                        ->toArray()],
                    ['name' => 'status', 'type' => 'select', 'options' => collect(\App\Enums\BarrierStatus::cases())
                        ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
                        ->prepend('Status (Todos)', '')
                        ->toArray()],
                ])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald1c029f02addcde30ed6072f3aa4cb40)): ?>
<?php $attributes = $__attributesOriginald1c029f02addcde30ed6072f3aa4cb40; ?>
<?php unset($__attributesOriginald1c029f02addcde30ed6072f3aa4cb40); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald1c029f02addcde30ed6072f3aa4cb40)): ?>
<?php $component = $__componentOriginald1c029f02addcde30ed6072f3aa4cb40; ?>
<?php unset($__componentOriginald1c029f02addcde30ed6072f3aa4cb40); ?>
<?php endif; ?>
        </div>

        <div id="barriers-table" class="p-3">
            <?php echo $__env->make('pages.barriers.partials.table', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <?php echo app('Illuminate\Foundation\Vite')('resources/js/components/dynamicFilters.js'); ?>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/pages/barriers/index.blade.php ENDPATH**/ ?>