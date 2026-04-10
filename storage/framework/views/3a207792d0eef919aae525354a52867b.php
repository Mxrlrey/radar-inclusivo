<aside class="sidebar">
    <div class="sidebar-header">
        <img src="<?php echo e(asset('images/logo2.png')); ?>" class="sidebar-logo" alt="Logo">
        <span class="sidebar-title">NAI</span>
    </div>

    <ul class="sidebar-menu">

        <li>
            <a href="<?php echo e(route('dashboard')); ?>"
               class="<?php echo e(request()->is('auth/dashboard') ? 'active' : ''); ?>">
                <span class="icon"><i class="bi bi-speedometer2"></i></span>
                <span class="text">Dashboard</span>
            </a>
        </li>

        <li>
            <a href="<?php echo e(url('/inicio')); ?>"
               class="<?php echo e(request()->is('inicio') ? 'active' : ''); ?>">
                <span class="icon"><i class="bi bi-house-door"></i></span>
                <span class="text">Início</span>
            </a>
        </li>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('report.reports.index')): ?>
        <li>
            <a href="<?php echo e(route('reports.index')); ?>"
            class="<?php echo e(request()->routeIs('reports.*') ? 'active' : ''); ?>">
                <span class="icon"><i class="bi bi-bar-chart"></i></span>
                <span class="text">Relatórios</span>
            </a>
        </li>
        <?php endif; ?>

        <li>
            <a href="<?php echo e(route('notifications.index')); ?>"
                class="<?php echo e(request()->routeIs('notifications.*') ? 'active' : ''); ?>">
                <span class="icon"><i class="fa fa-regular fa-bell"></i></span>
                <span class="text">Notificações</span>
            </a>
        </li>

        <li>
            <a href="<?php echo e(route('backups.index')); ?>"
                class="<?php echo e(request()->routeIs('backups.*') ? 'active' : ''); ?>">
                <span class="icon"><i class="fas fa-cloud-download"></i></span>
                <span class="text">Backups</span>
            </a>
        </li>

        <?php if(auth()->guard()->check()): ?>
            <?php if(auth()->user()->is_admin): ?>

                <li class="menu-divider">Configurações do Sistema</li>

                
                <li>
                    <a href="<?php echo e(route('deficiencies.index')); ?>"
                       class="<?php echo e(request()->routeIs('deficiencies.*') ? 'active' : ''); ?>">
                        <span class="icon"><i class="bi bi-heart-pulse"></i></span>
                        <span class="text">Deficiências</span>
                    </a>
                </li>

                <li>
                    <a href="<?php echo e(route('positions.index')); ?>"
                       class="<?php echo e(request()->routeIs('positions.*') ? 'active' : ''); ?>">
                        <span class="icon"><i class="bi bi-briefcase"></i></span>
                        <span class="text">Cargos</span>
                    </a>
                </li>

                <li>
                    <a href="<?php echo e(route('accessibility-features.index')); ?>"
                       class="<?php echo e(request()->routeIs('accessibility-features.*') ? 'active' : ''); ?>">
                        <span class="icon"><i class="bi bi-universal-access"></i></span>
                        <span class="text">Recursos de Acessibilidade</span>
                    </a>
                </li>

                <li>
                    <a href="<?php echo e(route('barrier-categories.index')); ?>"
                       class="<?php echo e(request()->routeIs('barrier-categories.*') ? 'active' : ''); ?>">
                        <span class="icon"><i class="bi bi-grid"></i></span>
                        <span class="text">Categorias de Barreiras</span>
                    </a>
                </li>

                <li>
                    <a href="<?php echo e(route('institutions.index')); ?>"
                       class="<?php echo e(request()->routeIs('institutions.*') ? 'active' : ''); ?>">
                        <span class="icon"><i class="bi bi-building-fill"></i></span>
                        <span class="text">Instituições</span>
                    </a>
                </li>

                <li>
                    <a href="<?php echo e(route('locations.index')); ?>"
                       class="<?php echo e(request()->routeIs('locations.*') ? 'active' : ''); ?>">
                        <span class="icon"><i class="bi bi-geo-alt"></i></span>
                        <span class="text">Localizações</span>
                    </a>
                </li>

            <?php endif; ?>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('student.view')): ?>
        <li class="nav-item">
            <a href="<?php echo e(route('students.index')); ?>"
                class="<?php echo e(request()->routeIs('students.*') ? 'active' : ''); ?>">
                <span class="icon"><i class="bi bi-people"></i></span>
                <span class="text">Alunos</span>
            </a>
        </li>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('professional.view')): ?>
        <li>
            <a href="<?php echo e(route('professionals.index')); ?>"
            class="<?php echo e(request()->routeIs('professionals.*') ? 'active' : ''); ?>">
                <span class="icon"><i class="bi bi-person-badge"></i></span>
                <span class="text">Equipe</span>
            </a>
        </li>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('assistive-technology.index')): ?>
        <li>
            <a href="<?php echo e(route('assistive-technologies.index')); ?>"
               class="<?php echo e(request()->routeIs('assistive-technologies.*') ? 'active' : ''); ?>">
                <span class="icon"><i class="bi bi-cpu"></i></span>
                <span class="text">Tecnologias Assistivas</span>
            </a>
        </li>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('material.index')): ?>
            <li>
                <a href="<?php echo e(route('accessible-educational-materials.index')); ?>"
                   class="<?php echo e(request()->routeIs('accessible-educational-materials.*') ? 'active' : ''); ?>">
                    <span class="icon"><i class="bi bi-book"></i></span>
                    <span class="text">Materiais Pedagógicos</span>
                </a>
            </li>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('barriers.index')): ?>
        <li>
            <a href="<?php echo e(route('barriers.index')); ?>"
               class="<?php echo e(request()->routeIs('barriers.*') ? 'active' : ''); ?>">
                <span class="icon"><i class="bi bi-slash-circle"></i></span>
                <span class="text">Barreiras</span>
            </a>
        </li>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('loan.index')): ?>
        <li>
            <a href="<?php echo e(route('loans.index')); ?>"
               class="<?php echo e(request()->routeIs('loans.*') ? 'active' : ''); ?>">
                <span class="icon"><i class="bi bi-arrow-left-right"></i></span>
                <span class="text">Empréstimos</span>
            </a>
        </li>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('waitlist.index')): ?>
            <li>
                <a href="<?php echo e(route('waitlists.index')); ?>"
                   class="<?php echo e(request()->routeIs('waitlists.*') ? 'active' : ''); ?>">
                    <span class="icon"><i class="bi bi-hourglass-split"></i></span>
                    <span class="text">Fila de Espera</span>
                </a>
            </li>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('institutional-event.index')): ?>
            <li>
                <a href="<?php echo e(route('institutional-events.index')); ?>"
                   class="<?php echo e(request()->routeIs('inclusive-radar.institutional-events.*') ? 'active' : ''); ?>">
                    <span class="icon"><i class="fa-solid fa-calendar-day"></i></span>
                    <span class="text">Agenda Institucional</span>
                </a>
            </li>
        <?php endif; ?>


















        <br>
        <br>
        <br>
    </ul>
</aside>
<?php /**PATH /var/www/resources/views/partials/sidebar.blade.php ENDPATH**/ ?>