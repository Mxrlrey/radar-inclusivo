<div class="dropdown">
    <button class="nav-user-dropdown dropdown-toggle waves-effect"
            type="button"
            data-bs-toggle="dropdown"
            aria-expanded="false"
            id="userMenuButton"
            aria-label="Menu do usuário: <?php echo e(Auth::user()->name); ?>">

        <div class="user-info-text me-2 d-none d-md-block">
            <span class="user-name">
                <?php echo e(Str::words(Auth::user()?->name ?? 'Convidado', 1, '')); ?>

            </span>
            <span class="user-role">
                <?php if(Auth::user()->professional): ?>
                    <?php echo e(Auth::user()->professional?->position?->name); ?>

                <?php else: ?>
                    Admin
                <?php endif; ?>
            </span>
        </div>

        <i class="ion-android-user-menu"></i>
    </button>

    <ul class="dropdown-menu" aria-labelledby="userMenuButton">
        <li>
            <a class="dropdown-item waves-effect" href="<?php echo e(route('profile.edit')); ?>">
                <i class="ion-ios7-person"></i> Perfil
            </a>
        </li>
        <li>
            <a class="dropdown-item text-danger waves-effect" href="<?php echo e(route('logout')); ?>"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="ion-log-out"></i> Sair
                <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
                    <?php echo csrf_field(); ?>
                </form>
            </a>
        </li>
    </ul>
</div>
<?php /**PATH /var/www/resources/views/partials/_user_menu.blade.php ENDPATH**/ ?>