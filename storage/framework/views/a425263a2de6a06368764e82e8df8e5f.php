
<?php if(auth()->guard()->check()): ?>
    <?php
        $unreadCount = Auth::user()->unreadNotifications()->count();
        $displayCount = $unreadCount > 99 ? '99+' : $unreadCount;
        $notifications = Auth::user()->unreadNotifications()->take(5)->get();
    ?>

    <div class="dropdown">
        <button class="btn-notif-circle position-relative waves-effect"
                type="button"
                id="dropdownNotif"
                data-bs-toggle="dropdown"
                aria-expanded="false"
                title="<?php echo e($unreadCount > 0 ? 'Ver ' . $unreadCount . ' notificações não lidas' : 'Nenhuma notificação nova'); ?>">
            <i class="fa fa-bell" aria-hidden="true"></i>
            <?php if($unreadCount > 0): ?>
                <span id="notif-count" class="notification-badge">
            <?php echo e($displayCount); ?>

            <span class="visually-hidden">notificações não lidas</span>
        </span>
            <?php endif; ?>
        </button>

        <div class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="dropdownNotif">
            <div class="notification-header">
                Notificações
                <?php if($unreadCount > 0): ?>
                    <span class="badge bg-primary ms-2"><?php echo e($unreadCount); ?> novas</span>
                <?php endif; ?>
            </div>

            
            <div class="notification-scroll">
                <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php $data = $notification->data; ?>
                    <a href="<?php echo $data['url'] ?? '#'; ?>" class="notification-item <?php echo e(!$notification->read_at ? 'unread' : ''); ?>">
                        <div class="notification-icon">
                            <i class="ion-information"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title"><?php echo e($data['title'] ?? 'Nova atualização'); ?></div>
                            <div class="notification-text"><?php echo e($data['message'] ?? ''); ?></div>
                            <div class="notification-time">
                                <i class="fa fa-clock-o me-1"></i>
                                <?php echo e(\Carbon\Carbon::parse($data['created_at'] ?? now())->diffForHumans()); ?>

                            </div>
                        </div>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-5">
                        <i class="fa fa-bell-slash text-muted d-block mb-2 notif-empty-icon"></i>
                        <span class="text-muted small">Você está em dia! Nenhuma notificação.</span>
                    </div>
                <?php endif; ?>
            </div>

            
            <div class="notification-footer">
                <a href="<?php echo e(route('notifications.index')); ?>">Ver todas as notificações</a>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH /var/www/resources/views/partials/_notifications.blade.php ENDPATH**/ ?>