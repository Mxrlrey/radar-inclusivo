<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'barrier' => null,
    'institution' => null,
    'lat' => null,
    'lng' => null,
    'zoom' => 16,
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'barrier' => null,
    'institution' => null,
    'lat' => null,
    'lng' => null,
    'zoom' => 16,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    if ($lat === null || $lat == 0) {
        if ($barrier) {
            $lat = $barrier->latitude;
            $lng = $barrier->longitude;
        } else if ($institution) {
            $lat = $institution->latitude;
            $lng = $institution->longitude;
        } else {
            $lat = old('latitude', -14.2350);
            $lng = old('longitude', -51.9253);
        }
    }

    if ($lng === null || $lng == 0) {
        if ($barrier) {
            $lng = $barrier->longitude;
        } else if ($institution) {
            $lng = $institution->longitude;
        } else {
            $lng = old('longitude', -51.9253);
        }
    }

    $lat = is_numeric($lat) && $lat != 0 ? $lat : -14.2350;
    $lng = is_numeric($lng) && $lng != 0 ? $lng : -51.9253;
    $zoom = $institution->default_zoom ?? $zoom;
?>

<?php if (isset($component)) { $__componentOriginal5f254904de3a56f0d40440e464f52aa1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5f254904de3a56f0d40440e464f52aa1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.forms.maps.base','data' => ['mapId' => 'map-barrier','lat' => $lat,'lng' => $lng,'zoom' => $zoom,'height' => $attributes->get('height', '450px'),'label' => $attributes->get('label', 'Localização da Barreira'),'latId' => 'lat','lngId' => 'lng','attributes' => $attributes]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('forms.maps.base'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['mapId' => 'map-barrier','lat' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($lat),'lng' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($lng),'zoom' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($zoom),'height' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($attributes->get('height', '450px')),'label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($attributes->get('label', 'Localização da Barreira')),'latId' => 'lat','lngId' => 'lng','attributes' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($attributes)]); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5f254904de3a56f0d40440e464f52aa1)): ?>
<?php $attributes = $__attributesOriginal5f254904de3a56f0d40440e464f52aa1; ?>
<?php unset($__attributesOriginal5f254904de3a56f0d40440e464f52aa1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5f254904de3a56f0d40440e464f52aa1)): ?>
<?php $component = $__componentOriginal5f254904de3a56f0d40440e464f52aa1; ?>
<?php unset($__componentOriginal5f254904de3a56f0d40440e464f52aa1); ?>
<?php endif; ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        window.barrierMapConfig = {
            mapId: 'map-barrier',
            lat: <?php echo e($lat); ?>,
            lng: <?php echo e($lng); ?>,
            zoom: <?php echo e($zoom); ?>,
            barrier: <?php echo json_encode($barrier ?? null, 15, 512) ?>,
            institution: <?php echo json_encode($institution ?? null, 15, 512) ?>,
            isEditMode: <?php echo e($barrier ? 'true' : 'false'); ?>

        };
    </script>
    <?php echo app('Illuminate\Foundation\Vite')('resources/js/pages/inclusive-radar/barriers.js'); ?>
<?php $__env->stopPush(); ?>
<?php /**PATH /var/www/resources/views/components/forms/maps/barrier.blade.php ENDPATH**/ ?>