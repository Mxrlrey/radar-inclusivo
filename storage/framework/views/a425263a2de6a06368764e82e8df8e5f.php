
<?php if(auth()->guard()->check()): ?>
    <?php
        $unreadCount = Auth::user()->unreadNotifications()->count();
        $displayCount = $unreadCount > 99 ? '99+' : $unreadCount;
        $notifications = Auth::user()->unreadNotifications()->take(5)->get();
    ?>

    <div class="dropdown me-3">
        <button class="btn btn-notif-circle position-relative"
                type="button"
                id="dropdownNotif"
                data-bs-toggle="dropdown"
                aria-expanded="false"
                aria-label="<?php echo e($unreadCount > 0 ? 'Ver ' . $unreadCount . ' notificações não lidas' : 'Ver notificações (Nenhuma nova)'); ?>">

            <i class="fa fa-regular fa-bell" style="color: #4D44B5" aria-hidden="true"></i>

            <?php if($unreadCount > 0): ?>
                <span id="notif-count"
                      class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-2 border-white notif-badge-custom">
                <?php echo e($displayCount); ?>

                <span class="visually-hidden">notificações não lidas</span>
            </span>
            <?php endif; ?>
        </button>

        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-3" style="width: 320px; border-radius: 15px; overflow: hidden;" aria-labelledby="dropdownNotif">
            <li class="px-3 py-3 d-flex justify-content-between align-items-center bg-light">
                <h2 class="fw-bold text-dark h6 mb-0">Notificações</h2>
                <?php if($unreadCount > 0): ?>
                    <span class="badge bg-primary rounded-pill" aria-hidden="true"><?php echo e($unreadCount); ?> novas</span>
                <?php endif; ?>
            </li>

            <div class="notification-scroll" style="max-height: 350px; overflow-y: auto;">
                <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php $data = $notification->data; ?>
                    <li>
                        <a href="<?php echo $data['url'] ?? '#'; ?>"
                           class="dropdown-item py-3 border-bottom text-wrap notify-item"
                           style="transition: background 0.2s;">
                            <div class="d-flex flex-column">
                            <span class="fw-semibold text-dark" style="font-size: 0.9rem;">
                                <?php echo e($data['title'] ?? 'Nova atualização'); ?>

                            </span>
                                <small class="text-muted mb-1" style="font-size: 0.8rem;">
                                    <?php echo e($data['message'] ?? ''); ?>

                                </small>
                                <small class="text-primary fw-bold" style="font-size: 0.7rem;">
                                    <i class="bi bi-clock me-1" aria-hidden="true"></i>
                                    <?php echo e(\Carbon\Carbon::parse($data['created_at'] ?? now())->diffForHumans()); ?>

                                </small>
                            </div>
                        </a>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <li class="py-5 text-center">
                        <i class="bi bi-bell-slash text-muted d-block mb-2" style="font-size: 1.5rem;" aria-hidden="true"></i>
                        <span class="text-muted small">Você está em dia! Nenhuma notificação.</span>
                    </li>
                <?php endif; ?>
            </div>

            <li>
                <a class="dropdown-item text-center small fw-bold py-3 text-primary bg-light" href="<?php echo e(route('notifications.index')); ?>">
                    Ver todas as notificações
                </a>
            </li>
        </ul>
    </div>
<?php endif; ?>
<?php /**PATH /var/www/resources/views/partials/_notifications.blade.php ENDPATH**/ ?>