<?php if(session('success') || session('error') || session('info') || $errors->any()): ?>
    <div id="toast-container">
        <?php
            $type = 'info';
            $icon = 'fa-info-circle';
            $message = '';

            if (session('success')) {
                $type = 'success';
                $icon = 'fa-check-circle';
                $message = session('success');
            } elseif (session('error')) {
                $type = 'danger';
                $icon = 'fa-exclamation-circle';
                $message = session('error');
            } elseif ($errors->any()) {
                // Captura erros do Request Validation
                $type = 'danger';
                $icon = 'fa-exclamation-triangle';
                // Pega apenas o primeiro erro para não quebrar o layout do toast
                $message = $errors->first();
            } elseif (session('info')) {
                $message = session('info');
            }
        ?>

        <div class="toast-custom <?php echo e($type); ?>" id="toast-element">
            <div class="toast-content">
                <i class="fas <?php echo e($icon); ?> fa-lg"></i>
                <div class="toast-body-text">
                    <?php echo e($message); ?>

                </div>
                <button type="button" class="btn-close-toast" onclick="window.closeToast()">×</button>
            </div>
            <div class="toast-progress"></div>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH /var/www/resources/views/components/messages/toast.blade.php ENDPATH**/ ?>