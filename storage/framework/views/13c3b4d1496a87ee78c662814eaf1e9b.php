<?php $__env->startSection('title', 'Gerenciamento de Backups'); ?>

<?php $__env->startSection('content'); ?>
    <div class="mb-5">
        <?php if (isset($component)) { $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.breadcrumb','data' => ['items' => [
            'Home' => route('dashboard'),
            'Backups' => route('backup.backups.index'),
        ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
            'Home' => route('dashboard'),
            'Backups' => route('backup.backups.index'),
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.page-header','data' => ['title' => 'Gerenciamento de Backups','subtitle' => 'Visualize e administre as cópias de segurança do sistema.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Gerenciamento de Backups','subtitle' => 'Visualize e administre as cópias de segurança do sistema.']); ?>
            <div class="d-flex gap-2">
                
                <form action="<?php echo e(route('backup.backups.upload')); ?>" method="POST" enctype="multipart/form-data" id="form-upload-backup">
                    <?php echo csrf_field(); ?>
                    
                    <input type="file"
                           name="backup_file"
                           id="input-backup-file"
                           class="d-none"
                           accept=".zip"
                           onchange="if(this.value) { this.form.submit(); }">

                    
                    <?php if (isset($component)) { $__componentOriginal5cd5668654a8f1a77aef15d054cb27b7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5cd5668654a8f1a77aef15d054cb27b7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.buttons.link-button','data' => ['type' => 'button','variant' => 'outline-primary','onclick' => 'document.getElementById(\'input-backup-file\').click()']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('buttons.link-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','variant' => 'outline-primary','onclick' => 'document.getElementById(\'input-backup-file\').click()']); ?>
                        <i class="fas fa-upload"></i> Importar Backup
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
                </form>

                
                <form action="<?php echo e(route('backup.backups.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php if (isset($component)) { $__componentOriginal8c5c9de60cf694be03f8e7259692a21b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8c5c9de60cf694be03f8e7259692a21b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.buttons.submit-button','data' => ['type' => 'submit','variant' => 'new']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('buttons.submit-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','variant' => 'new']); ?>
                        <i class="fas fa-plus-circle"></i> Gerar Novo
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
            </div>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.filters.form','data' => ['dataDynamicFilter' => true,'dataTarget' => '#backups-table','action' => ''.e(route('backup.backups.index')).'','fields' => [
                    ['name' => 'name', 'placeholder' => 'Filtrar por nome...', 'column' => 'col-md-5'],
                    ['name' => 'status', 'type' => 'select', 'options' => [
                        '' => 'Todos os Status',
                        'success' => 'Sucesso',
                        'failed' => 'Falha',
                        'archived' => 'Arquivado'
                    ], 'column' => 'col-md-3'],
                    ['name' => 'user_id', 'type' => 'select', 'options' => $users->mapWithKeys(fn($u) => [$u->id => $u->name])->prepend('Todos os Responsáveis', ''), 'column' => 'col-md-4']
                ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.filters.form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data-dynamic-filter' => true,'data-target' => '#backups-table','action' => ''.e(route('backup.backups.index')).'','fields' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                    ['name' => 'name', 'placeholder' => 'Filtrar por nome...', 'column' => 'col-md-5'],
                    ['name' => 'status', 'type' => 'select', 'options' => [
                        '' => 'Todos os Status',
                        'success' => 'Sucesso',
                        'failed' => 'Falha',
                        'archived' => 'Arquivado'
                    ], 'column' => 'col-md-3'],
                    ['name' => 'user_id', 'type' => 'select', 'options' => $users->mapWithKeys(fn($u) => [$u->id => $u->name])->prepend('Todos os Responsáveis', ''), 'column' => 'col-md-4']
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

        
        <div id="backups-table" class="p-3">
            <?php echo $__env->make('pages.backup.partials.table', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>

    <div class="mt-4 alert alert-info d-flex align-items-center border-0 shadow-sm" role="alert">
        <i class="fas fa-shield-alt me-3 fa-lg text-primary"></i>
        <div>
            <span class="fw-bold d-block">Política de Armazenamento</span>
            <small>
                Os backups são armazenados em <code class="fw-bold text-dark">storage/app/GNAI</code>.
                Arquivos com status <span class="badge bg-info-subtle text-info-emphasis border px-1">Arquivado</span> não serão removidos por limpezas automáticas.
            </small>
        </div>
    </div>

    <script>
        document.querySelectorAll('.form-restore').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const fileName = this.querySelector('.btn-restore').dataset.filename;
                const confirmacao = confirm(
                    `⚠️ ATENÇÃO: Você está prestes a restaurar o backup: ${fileName}\n\n` +
                    `Isso substituirá TODOS os dados atuais do banco de dados e arquivos de mídia pelas informações desta data.\n\n` +
                    `Deseja continuar?`
                );

                if (confirmacao) {
                    const btn = this.querySelector('.btn-restore');
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Restaurando...';
                    this.submit();
                }
            });
        });
    </script>

    <?php $__env->startPush('scripts'); ?>
        <?php echo app('Illuminate\Foundation\Vite')('resources/js/components/dynamicFilters.js'); ?>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/pages/backup/index.blade.php ENDPATH**/ ?>