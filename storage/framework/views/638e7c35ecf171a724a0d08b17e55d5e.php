
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'mapId' => 'map',
    'lat' => -14.2350,
    'lng' => -51.9253,
    'zoom' => 16,
    'height' => '500px',
    'latId' => 'lat',
    'lngId' => 'lng',
    'label' => 'Localização no Mapa',
    'showLegend' => true,
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
    'mapId' => 'map',
    'lat' => -14.2350,
    'lng' => -51.9253,
    'zoom' => 16,
    'height' => '500px',
    'latId' => 'lat',
    'lngId' => 'lng',
    'label' => 'Localização no Mapa',
    'showLegend' => true,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div <?php echo e($attributes->merge(['class' => 'leaflet-map-container'])); ?>

     id="leaflet-container-<?php echo e($mapId); ?>"
     data-lat="<?php echo e(old('latitude', $lat)); ?>"
     data-lng="<?php echo e(old('longitude', $lng)); ?>"
     data-zoom="<?php echo e(old('default_zoom', $zoom)); ?>"
     data-lat-id="<?php echo e($latId); ?>"
     data-lng-id="<?php echo e($lngId); ?>">

    
    <div class="d-flex justify-content-between align-items-center mb-1">
        <span
            id="map-label-<?php echo e($mapId); ?>"
            class="form-label fw-bold text-primary mb-0"
        >
            <?php echo e($label); ?>

        </span>
        <small
            id="map-help-<?php echo e($mapId); ?>"
            class="text-muted italic"
            style="font-size: var(--font-size-sm);"
        >
            Clique no mapa para definir o ponto
        </small>
    </div>

    
    <div class="map-wrapper">
        <div
            id="<?php echo e($mapId); ?>"
            style="height: <?php echo e($height); ?>;"
            role="application"
            aria-labelledby="map-label-<?php echo e($mapId); ?>"
            aria-describedby="map-help-<?php echo e($mapId); ?>">
        </div>
    </div>

    
    <div class="d-flex gap-3 mt-2">
        <div class="small text-muted">
            <span class="fw-bold text-primary">LAT:</span>
            <span id="display-<?php echo e($mapId); ?>-lat"><?php echo e(old('latitude', $lat)); ?></span>
        </div>
        <div class="small text-muted">
            <span class="fw-bold text-primary">LNG:</span>
            <span id="display-<?php echo e($mapId); ?>-lng"><?php echo e(old('longitude', $lng)); ?></span>
        </div>
    </div>

    
    <input type="hidden" name="latitude" id="<?php echo e($latId); ?>" value="<?php echo e(old('latitude', $lat)); ?>">
    <input type="hidden" name="longitude" id="<?php echo e($lngId); ?>" value="<?php echo e(old('longitude', $lng)); ?>">

    
    <?php if($showLegend): ?>
        <div id="map-legend-<?php echo e($mapId); ?>" class="map-legend-container d-none mb-3">
            
        </div>
    <?php endif; ?>
</div>

<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<?php $__env->stopPush(); ?>
<?php /**PATH /var/www/resources/views/components/forms/maps/base.blade.php ENDPATH**/ ?>