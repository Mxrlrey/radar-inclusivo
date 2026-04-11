<nav class="navbar-custom" role="navigation" aria-label="Navegação Principal">
    <div class="navbar-inner">
        
        <div class="navbar-left">
            <button id="sidebarToggle"
                    class="btn-theme-toggle waves-effect"
                    type="button"
                    aria-label="Abrir ou fechar menu lateral">
                <i class="ion-navicon" aria-hidden="true"></i>
            </button>

            <div class="navbar-left-content">
                <?php if(session()->has('impersonator_id')): ?>
                    <div class="alert alert-warning py-1 px-3 mb-0 me-3 d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <small>
                            Você está navegando como <strong><?php echo e(auth()->user()->name); ?></strong>
                        </small>
                    </div>
                <?php endif; ?>

                <a class="navbar-brand d-none d-md-flex align-items-center text-primary fw-bold"
                   href="<?php echo e(route('institutions.index')); ?>"
                   aria-label="Gerenciar Instituição: <?php echo e($institution?->name ?? 'GNAI'); ?>">
                    <?php echo e($institution?->name ?? 'GNAI'); ?>

                </a>
            </div>
        </div>

        <div class="navbar-right">
            <div role="region" aria-label="Notificações">
                <?php echo $__env->make('partials._notifications', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>

            <button id="themeToggle"
                    class="btn-theme-toggle waves-effect"
                    type="button"
                    aria-label="Alternar modo escuro">
                <i class="fa fa-moon-o" id="themeIcon" aria-hidden="true"></i>
            </button>

            <button id="fullscreenToggle"
                    class="btn-theme-toggle waves-effect"
                    type="button"
                    aria-label="Alternar tela cheia">
                <i class="ion-arrow-expand" id="fullscreenIcon" aria-hidden="true"></i>
            </button>

            <div role="region" aria-label="Menu do usuário">
                <?php echo $__env->make('partials._user_menu', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </div>
    </div>
</nav>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // ===== TEMA =====
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const body = document.body;

        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            body.classList.add('theme-dark');
            themeIcon.className = 'fa fa-sun-o';
        } else {
            body.classList.remove('theme-dark');
            themeIcon.className = 'fa fa-moon-o';
        }

        themeToggle.addEventListener('click', function() {
            if (body.classList.contains('theme-dark')) {
                body.classList.remove('theme-dark');
                themeIcon.className = 'fa fa-moon-o';
                localStorage.setItem('theme', 'light');
            } else {
                body.classList.add('theme-dark');
                themeIcon.className = 'fa fa-sun-o';
                localStorage.setItem('theme', 'dark');
            }
        });

        // ===== FULLSCREEN =====
        const fullscreenToggle = document.getElementById('fullscreenToggle');
        const fullscreenIcon = document.getElementById('fullscreenIcon');

        function updateFullscreenIcon() {
            if (document.fullscreenElement) {
                fullscreenIcon.className = 'ion-arrow-shrink';
            } else {
                fullscreenIcon.className = 'ion-arrow-expand';
            }
        }

        fullscreenToggle.addEventListener('click', function() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
            } else {
                document.exitFullscreen();
            }
        });

        document.addEventListener('fullscreenchange', updateFullscreenIcon);
    });
</script>
<?php /**PATH /var/www/resources/views/partials/navbar.blade.php ENDPATH**/ ?>