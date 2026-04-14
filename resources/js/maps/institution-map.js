import { BaseMap, MapUtils } from './base-map.js';

/**
 * institution-map.js
 * Mapa da sede da instituição: busca de endereço, zoom range, inputs manuais.
 * Depende de base-map.js (deve ser carregado antes).
 */

class InstitutionMap extends BaseMap {
    constructor(config = {}) {
        super(config);

        // Elementos específicos deste mapa
        this.zoomRange    = MapUtils.$('default_zoom');  // id no Blade é "default_zoom"
        this.zoomBadge    = MapUtils.$('zoom_val');

        // Inputs manuais de coordenadas — no Blade são "latitude"/"longitude"
        this.latManual    = MapUtils.$('latitude');
        this.lngManual    = MapUtils.$('longitude');

        // Inputs de endereço
        this.cityInput     = this._getRealInput('city_search');
        this.stateInput    = this._getRealInput('state_search');
        this.districtInput = this._getRealInput('district_search');
        this.addressInput  = this._getRealInput('address_search');

        if (!this._initMap()) return;

        this._setupMainMarker(
            this.map.getCenter().lat,
            this.map.getCenter().lng,
        );

        this._setupManualInputEvents();
        this._setupAddressEvents();
        this._setupZoomEvents();
    }

    _setupAddressEvents() {
        const inputs = [
            this.cityInput,
            this.stateInput,
            this.districtInput,
            this.addressInput,
        ];

        for (const input of inputs) {
            if (!input) continue;
            MapUtils.on(input, 'input',    () => this._debounce(() => this._triggerSearch(), 500));
            MapUtils.on(input, 'keypress', (e) => {
                if (e.key === 'Enter') { e.preventDefault(); this._triggerSearch(); }
            });
        }
    }

    _triggerSearch() {
        this.searchAddress({
            city:     this.cityInput?.value.trim()     ?? '',
            state:    this.stateInput?.value.trim()    ?? '',
            district: this.districtInput?.value.trim() ?? '',
            street:   this.addressInput?.value.trim()  ?? '',
        });
    }

    _fillAddressFields({ city, district, state, street }) {
        if (this.cityInput     && city)     this.cityInput.value     = city;
        if (this.districtInput && district) this.districtInput.value = district;
        if (this.stateInput    && state)    this.stateInput.value    = state;
        if (this.addressInput  && street)   this.addressInput.value  = street;
    }

    _onMapClick(e) {
        this.updateLocation(e.latlng.lat, e.latlng.lng);
        this.reverseGeocode(e.latlng.lat, e.latlng.lng);
    }

    _setupZoomEvents() {
        if (!this.zoomRange) return;
        MapUtils.on(this.zoomRange, 'input', (e) => {
            const zoom = parseInt(e.target.value);
            if (this.zoomBadge) this.zoomBadge.innerText = zoom;
            this.map?.setZoom(zoom);
        });
    }

    _getRealInput(id) {
        const el = MapUtils.$(id);
        if (!el) return null;
        return el.tagName === 'INPUT' ? el : el.querySelector('input');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    if (!MapUtils.$('map-institution') || !window.institutionMapConfig || typeof L === 'undefined') return;
    try {
        window.institutionMapInstance = new InstitutionMap(window.institutionMapConfig);
        window.InstitutionMap = InstitutionMap;
    } catch (err) {
        console.error('[InstitutionMap] Erro na inicialização:', err);
    }
});
