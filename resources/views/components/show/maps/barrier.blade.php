@props([
    'barrier' => null,
    'institution' => null,
    'height' => '400px',
    'label' => 'Localização da Barreira',
])

@php
    $lat = $barrier->latitude ?? $institution->latitude ?? -14.2350;
    $lng = $barrier->longitude ?? $institution->longitude ?? -51.9253;
    $zoom = $institution->default_zoom ?? 16;
    $summaryText = "{$label}. Latitude {$lat} e longitude {$lng}.";
@endphp

<x-forms.maps.base
    mapId="map-barrier-show"
    :lat="$lat"
    :lng="$lng"
    :zoom="$zoom"
    :height="$height"
    :label="$label"
    :showLegend="false"
    :interactive="false"
    :showInputs="false"
    helpText="Mapa apenas para visualização da barreira."
    :summaryText="$summaryText"
/>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('leaflet-container-map-barrier-show');
            if (!container) return;

            const lat = parseFloat(container.dataset.lat);
            const lng = parseFloat(container.dataset.lng);
            const zoom = parseInt(container.dataset.zoom);
            const interactive = container.dataset.interactive === 'true';

            const streetLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap',
                maxZoom: 19
            });

            const satelliteLayer = L.tileLayer(
                'https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}',
                {
                    attribution: '© Google',
                    maxZoom: 21
                }
            );

            const map = L.map('map-barrier-show', {
                center: [lat, lng],
                zoom: zoom,
                layers: [streetLayer],
                dragging: interactive,
                touchZoom: interactive,
                doubleClickZoom: interactive,
                scrollWheelZoom: interactive,
                boxZoom: interactive,
                keyboard: interactive,
                tap: interactive
            });

            const baseMaps = {
                "Mapa de Ruas": streetLayer,
                "Satélite": satelliteLayer
            };

            const disableControl = (control) => {
                const controlContainer = control?.getContainer?.();
                if (controlContainer && !interactive) {
                    controlContainer.setAttribute('aria-hidden', 'true');
                    controlContainer.style.pointerEvents = 'none';
                    controlContainer.querySelectorAll('a, button, input, select').forEach(element => {
                        element.setAttribute('tabindex', '-1');
                    });
                }
            };

            disableControl(map.zoomControl);
            const layersControl = L.control.layers(baseMaps).addTo(map);
            disableControl(layersControl);

            L.marker([lat, lng], { keyboard: interactive, interactive: interactive })
                .addTo(map)
                .bindTooltip('{{ $label }}', {
                    permanent: true,
                    direction: 'top',
                    offset: [-15, -7],
                    className: 'bg-primary text-white fw-bold px-2 rounded shadow-sm'
                });
        });
    </script>
@endpush
