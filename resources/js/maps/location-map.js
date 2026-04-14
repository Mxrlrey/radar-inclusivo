import { BaseMap, MapUtils } from './base-map.js';

/**
 * location-map.js
 * Mapa de localizações do campus: plota sede (vermelho) + localizações
 * existentes (cinza) + localização sendo criada/editada (azul).
 * Depende de base-map.js (deve ser carregado antes).
 */

class LocationMap extends BaseMap {
    constructor(config = {}) {
        super(config);

        this.institutionMarker  = null;
        this.existingLayer      = L.layerGroup();  // marcadores cinzas (outras localizações)
        this.institutionSelect  = MapUtils.$('institution_select');

        if (!this._initMap()) return;

        // Adiciona camada de marcadores existentes ao mapa
        this.existingLayer.addTo(this.map);

        this._setupManualInputEvents();

        // Plota dados iniciais
        if (this.config.institution) {
            this.plotInstitutionAndLocations(this.config.institution);
        }

        if (this.config.isEditMode && this.config.location) {
            this._plotCurrentLocation(this.config.location);
        } else {
            const { lat, lng } = this._resolveInitialCoords();
            this._setupMainMarker(lat, lng);
        }
    }

    /* ---------------------------
       Coordenadas iniciais
    --------------------------- */

    _resolveInitialCoords() {
        // Em modo edição, prioriza a localização sendo editada
        if (this.config.isEditMode && this.config.location) {
            return {
                lat: this.config.location.latitude,
                lng: this.config.location.longitude,
            };
        }
        return super._resolveInitialCoords();
    }

    /* ---------------------------
       Clique no mapa (exige instituição selecionada)
    --------------------------- */

    _onMapClick(e) {
        if (!this.institutionSelect?.value) {
            alert('Por favor, selecione uma instituição base primeiro.');
            return;
        }
        this.updateLocation(e.latlng.lat, e.latlng.lng);
    }

    /* ---------------------------
       Plotagem da instituição + localizações existentes
    --------------------------- */

    /**
     * @param {object} institution  - objeto com latitude, longitude, name, locations[]
     */
    plotInstitutionAndLocations(institution, forceMove = false) {
        if (!institution || !MapUtils.isValidCoord(institution.latitude) || !MapUtils.isValidCoord(institution.longitude)) {
            console.error('[LocationMap] Instituição sem coordenadas válidas.');
            return;
        }

        // Limpa camadas anteriores
        if (this.institutionMarker) this.map.removeLayer(this.institutionMarker);
        this.existingLayer.clearLayers();

        // Marcador da sede (vermelho)
        this.institutionMarker = L.marker(
            [institution.latitude, institution.longitude],
            { icon: MapUtils.createIcon('red') },
        ).addTo(this.map);

        this.institutionMarker.bindTooltip(`Sede: ${institution.name}`, {
            permanent:  false,
            direction:  'top',
            className:  'bg-danger text-white border-0 fw-bold rounded shadow-sm px-2 py-1',
            offset:     [0, -35],
        });

        // Marcadores das demais localizações (cinza)
        const greyIcon = MapUtils.createIcon('grey', [20, 32], [10, 32]);

        for (const loc of institution.locations ?? []) {
            // Pula a localização em edição (já representada pelo marcador azul)
            if (this.config.isEditMode && this.config.location?.id === loc.id) continue;
            if (!MapUtils.isValidCoord(loc.latitude) || !MapUtils.isValidCoord(loc.longitude)) continue;

            const m = L.marker([loc.latitude, loc.longitude], { icon: greyIcon })
                .addTo(this.existingLayer);

            m.bindTooltip(`${loc.name} (${loc.type ?? 'Sem tipo'})`, {
                permanent:  false,
                direction:  'top',
                className:  'bg-secondary text-white border-0 small rounded shadow-sm px-2 py-1',
                offset:     [0, -32],
            });
        }

        // Move câmera: sempre que forceMove, ou no modo criação
        if (forceMove || !this.config.isEditMode) {
            this.map.flyTo(
                [institution.latitude, institution.longitude],
                institution.default_zoom ?? 16,
                { animate: true, duration: 2 },
            );
            this.updateLocation(institution.latitude, institution.longitude);
        }
    }

    /* ---------------------------
       Plotagem da localização atual (modo edição)
    --------------------------- */

    _plotCurrentLocation(location) {
        this.updateLocation(location.latitude, location.longitude);
        this.map.flyTo([location.latitude, location.longitude], 18, { animate: true, duration: 2 });
    }
}

/* ---------------------------
   Bootstrap
--------------------------- */
document.addEventListener('DOMContentLoaded', () => {
    if (!MapUtils.$('map-location') || !window.locationMapConfig || typeof L === 'undefined') return;

    try {
        const instance = new LocationMap(window.locationMapConfig);
        window.locationMapInstance = instance;
        window.LocationMap = LocationMap;

        // Troca de instituição pelo select
        const sel = MapUtils.$('institution_select');
        MapUtils.on(sel, 'change', function () {
            const inst = (window.institutionsData ?? []).find(i => String(i.id) === String(this.value));
            if (inst) instance.plotInstitutionAndLocations(inst, true);
        });
    } catch (err) {
        console.error('[LocationMap] Erro na inicialização:', err);
    }
});
