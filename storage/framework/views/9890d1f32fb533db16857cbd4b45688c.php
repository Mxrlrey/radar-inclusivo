<?php if (isset($component)) { $__componentOriginalce08cb48157c4a917fb06b4e6b178eb7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce08cb48157c4a917fb06b4e6b178eb7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.table','data' => ['headers' => ['Nome', 'Status', 'Ações'],'records' => $features,'class' => 'table-striped']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['headers' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['Nome', 'Status', 'Ações']),'records' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($features),'class' => 'table-striped']); ?>
    <?php $__empty_1 = true; $__currentLoopData = $features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
            <?php if (isset($component)) { $__componentOriginalc91c98e046a1434e6f8cdd0cdedd160b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc91c98e046a1434e6f8cdd0cdedd160b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.td','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.td'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                <?php echo e($feature->name); ?>

             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc91c98e046a1434e6f8cdd0cdedd160b)): ?>
<?php $attributes = $__attributesOriginalc91c98e046a1434e6f8cdd0cdedd160b; ?>
<?php unset($__attributesOriginalc91c98e046a1434e6f8cdd0cdedd160b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc91c98e046a1434e6f8cdd0cdedd160b)): ?>
<?php $component = $__componentOriginalc91c98e046a1434e6f8cdd0cdedd160b; ?>
<?php unset($__componentOriginalc91c98e046a1434e6f8cdd0cdedd160b); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginalc91c98e046a1434e6f8cdd0cdedd160b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc91c98e046a1434e6f8cdd0cdedd160b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.td','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.td'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                <span class="badge bg-<?php echo e($feature->is_active ? 'success' : 'secondary'); ?>">
                    <?php echo e($feature->is_active ? 'Ativo' : 'Inativo'); ?>

                </span>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc91c98e046a1434e6f8cdd0cdedd160b)): ?>
<?php $attributes = $__attributesOriginalc91c98e046a1434e6f8cdd0cdedd160b; ?>
<?php unset($__attributesOriginalc91c98e046a1434e6f8cdd0cdedd160b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc91c98e046a1434e6f8cdd0cdedd160b)): ?>
<?php $component = $__componentOriginalc91c98e046a1434e6f8cdd0cdedd160b; ?>
<?php unset($__componentOriginalc91c98e046a1434e6f8cdd0cdedd160b); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginalc91c98e046a1434e6f8cdd0cdedd160b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc91c98e046a1434e6f8cdd0cdedd160b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.td','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.td'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                <?php if (isset($component)) { $__componentOriginalf6c151271844b557d2fb19dbc1eaf516 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf6c151271844b557d2fb19dbc1eaf516 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.actions','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.actions'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                    <?php if (isset($component)) { $__componentOriginal5cd5668654a8f1a77aef15d054cb27b7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5cd5668654a8f1a77aef15d054cb27b7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.buttons.link-button','data' => ['href' => route('accessibility-features.show', $feature),'variant' => 'info','size' => 'xs','title' => 'Visualizar Recursos de Acessibilidade']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('buttons.link-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('accessibility-features.show', $feature)),'variant' => 'info','size' => 'xs','title' => 'Visualizar Recursos de Acessibilidade']); ?>
                        <i class="fa fa-eye"></i>
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

                    <form action="<?php echo e(route('accessibility-features.destroy', $feature)); ?>"
                          method="POST"
                          class="d-inline"
                          title="Remover Recursos de Acessibilidade"
                    >
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <?php if (isset($component)) { $__componentOriginal8c5c9de60cf694be03f8e7259692a21b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8c5c9de60cf694be03f8e7259692a21b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.buttons.submit-button','data' => ['variant' => 'danger','size' => 'xs','onclick' => 'return confirm(\'Deseja realmente remover este recurso?\')']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('buttons.submit-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'danger','size' => 'xs','onclick' => 'return confirm(\'Deseja realmente remover este recurso?\')']); ?>
                            <i class="fa fa-eraser"></i>
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
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf6c151271844b557d2fb19dbc1eaf516)): ?>
<?php $attributes = $__attributesOriginalf6c151271844b557d2fb19dbc1eaf516; ?>
<?php unset($__attributesOriginalf6c151271844b557d2fb19dbc1eaf516); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf6c151271844b557d2fb19dbc1eaf516)): ?>
<?php $component = $__componentOriginalf6c151271844b557d2fb19dbc1eaf516; ?>
<?php unset($__componentOriginalf6c151271844b557d2fb19dbc1eaf516); ?>
<?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc91c98e046a1434e6f8cdd0cdedd160b)): ?>
<?php $attributes = $__attributesOriginalc91c98e046a1434e6f8cdd0cdedd160b; ?>
<?php unset($__attributesOriginalc91c98e046a1434e6f8cdd0cdedd160b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc91c98e046a1434e6f8cdd0cdedd160b)): ?>
<?php $component = $__componentOriginalc91c98e046a1434e6f8cdd0cdedd160b; ?>
<?php unset($__componentOriginalc91c98e046a1434e6f8cdd0cdedd160b); ?>
<?php endif; ?>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr>
            <td colspan="3" class="text-center text-muted py-4">
                Nenhum recurso de acessibilidade cadastrado.
            </td>
        </tr>
    <?php endif; ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce08cb48157c4a917fb06b4e6b178eb7)): ?>
<?php $attributes = $__attributesOriginalce08cb48157c4a917fb06b4e6b178eb7; ?>
<?php unset($__attributesOriginalce08cb48157c4a917fb06b4e6b178eb7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce08cb48157c4a917fb06b4e6b178eb7)): ?>
<?php $component = $__componentOriginalce08cb48157c4a917fb06b4e6b178eb7; ?>
<?php unset($__componentOriginalce08cb48157c4a917fb06b4e6b178eb7); ?>
<?php endif; ?>
<?php /**PATH /var/www/resources/views/pages/accessibility-features/partials/table.blade.php ENDPATH**/ ?>