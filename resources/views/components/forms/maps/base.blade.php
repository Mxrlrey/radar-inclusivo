{{-- resources/views/components/maps/base.blade.php --}}
@props([
    'mapId' => 'map',
    'lat' => -14.2350,
    'lng' => -51.9253,
    'zoom' => 16,
    'height' => '500px',
    'latId' => 'lat',
    'lngId' => 'lng',
    'label' => 'Localização no Mapa',
    'showLegend' => true,
    'helpText' => null,
    'interactive' => true,
    'showInputs' => true,
    'summaryText' => null,
])

@php
    $helpMessage = $helpText ?? ($interactive ? 'Clique no mapa para definir o ponto' : 'Mapa apenas para visualização.');
    $summaryId = $summaryText ? "map-summary-{$mapId}" : null;
    $describedBy = trim(implode(' ', array_filter([
        "map-help-{$mapId}",
        $summaryId,
    ])));
@endphp

<div {{ $attributes->merge(['class' => 'leaflet-map-container']) }}
     id="leaflet-container-{{ $mapId }}"
     data-lat="{{ old('latitude', $lat) }}"
     data-lng="{{ old('longitude', $lng) }}"
     data-zoom="{{ old('default_zoom', $zoom) }}"
     data-lat-id="{{ $latId }}"
     data-lng-id="{{ $lngId }}"
     data-interactive="{{ $interactive ? 'true' : 'false' }}">

    {{-- Título --}}
    <div class="d-flex justify-content-between align-items-center mb-1">
        <span
            id="map-label-{{ $mapId }}"
            class="form-label fw-bold text-primary mb-0"
        >
            {{ $label }}
        </span>
        <small
            id="map-help-{{ $mapId }}"
            class="text-muted italic"
            style="font-size: var(--font-size-sm);"
        >
            {{ $helpMessage }}
        </small>
    </div>

    @if($summaryText)
        <div id="{{ $summaryId }}" class="visually-hidden">{{ $summaryText }}</div>
    @endif

    {{-- Mapa --}}
    <div class="map-wrapper">
        <div
            id="{{ $mapId }}"
            style="height: {{ $height }};"
            aria-labelledby="map-label-{{ $mapId }}"
            aria-describedby="{{ $describedBy }}"
            @if(!$interactive) aria-readonly="true" tabindex="-1" @endif>
        </div>
    </div>

    {{-- Display de coordenadas --}}
    <div class="d-flex gap-3 mt-2">
        <div class="small text-muted">
            <span class="fw-bold text-primary">LAT:</span>
            <span id="display-{{ $mapId }}-lat">{{ old('latitude', $lat) }}</span>
        </div>
        <div class="small text-muted">
            <span class="fw-bold text-primary">LNG:</span>
            <span id="display-{{ $mapId }}-lng">{{ old('longitude', $lng) }}</span>
        </div>
    </div>

    {{-- Inputs ocultos --}}
    @if($showInputs)
        <input type="hidden" name="latitude" id="{{ $latId }}" value="{{ old('latitude', $lat) }}">
        <input type="hidden" name="longitude" id="{{ $lngId }}" value="{{ old('longitude', $lng) }}">
    @endif

    {{-- Legenda --}}
    @if($showLegend)
        <div id="map-legend-{{ $mapId }}" class="map-legend-container d-none mb-3">
            {{-- A legenda será preenchida pelo JavaScript --}}
        </div>
    @endif
</div>

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@endpush
