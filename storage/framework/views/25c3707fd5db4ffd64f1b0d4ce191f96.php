<nav class="navbar navbar-expand-lg navbar-dark navbar-custom" role="navigation" aria-label="Navegação Principal">
    <div class="container-fluid">

        <div class="d-flex align-items-center">

            <button id="sidebarToggle"
                    class="btn btn-fw text-white p-0 me-3"
                    type="button"
                    aria-label="Abrir ou fechar menu lateral">
                <i class="bi bi-list fs-3" aria-hidden="true"></i>
            </button>

            <?php if(session()->has('impersonator_id')): ?>
            <div class="alert alert-warning py-1 px-3 mb-0 me-3 d-flex align-items-center">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <small>
                    Você está navegando como <strong><?php echo e(auth()->user()->name); ?></strong>
                </small>
            </div>
            <?php endif; ?>

            <a class="navbar-brand me-auto d-flex align-items-center"
               href="<?php echo e(route('institutions.index')); ?>"
               aria-label="Gerenciar Instituição: <?php echo e($institution?->name ?? 'GNAI'); ?>">

                <span class="fw-bold">
                    <?php echo e($institution?->name ?? 'GNAI'); ?>

                </span>
            </a>
        </div>

        <div class="d-flex align-items-center gap-2">
            <div role="region" aria-label="Notificações">
                <?php echo $__env->make('partials._notifications', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>

            <div role="region" aria-label="Menu do usuário">
                <?php echo $__env->make('partials._user_menu', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </div>

    </div>
</nav>
<?php /**PATH /var/www/resources/views/partials/navbar.blade.php ENDPATH**/ ?>