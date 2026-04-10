<div class="card-custom overflow-hidden">
    <?php
        $method = strtoupper($attributes->get('method', 'POST'));
        $formMethod = in_array($method, ['GET', 'POST']) ? $method : 'POST';
    ?>

    <form <?php echo e($attributes->merge(['method' => $formMethod])); ?> class="p-0">

        <?php if($formMethod !== 'GET'): ?>
            <?php echo csrf_field(); ?>
        <?php endif; ?>

        <?php if(! in_array($method, ['GET','POST'])): ?>
            <?php echo method_field($method); ?>
        <?php endif; ?>

        <div class="row g-0">
            <?php echo e($slot); ?>

        </div>
    </form>
</div>
<?php /**PATH /var/www/resources/views/components/forms/form-card.blade.php ENDPATH**/ ?>