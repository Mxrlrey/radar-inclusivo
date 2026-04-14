/**
 * base-map.js
 * Classe base unificada para todos os mapas Leaflet do sistema.
 * Contém toda lógica comum: criação do mapa, marcadores, ícones,
 * inputs hidden, displays de coordenadas e geocodificação.
 */

/* ============================
   Utilitários globais
   ============================ */
const MapUtils = {
    /** Busca elemento pelo ID */
    $: (id) => document.getElementById(id),

    /** Registra listener apenas se o elemento existir */
    on: (el, ev, fn) => { if (el) el.addEventListener(ev, fn); },

    /** Valida coordenada numérica */
    isValidCoord: (v) => v !== null && v !== undefined && Number.isFinite(Number(v)),

    /** Converte valor para float seguro, ou null */
    safeParse: (v) => (MapUtils.isValidCoord(v) ? parseFloat(v) : null),

    /** URLs dos ícones coloridos */
    ICON_URLS: {
        blue:   'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
        red:    'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
        grey:   'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-grey.png',
        yellow: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-yellow.png',
    },

    SHADOW_URL: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',

    /**
     * Cria um ícone Leaflet colorido.
     * @param {'blue'|'red'|'grey'|'yellow'} color
     * @param {[number,number]} size       - [width, height]
     * @param {[number,number]} anchor     - [x, y]
     */
    createIcon(color = 'blue', size = [25, 41], anchor = [12, 41]) {
        const shadowSize   = [size[1], size[1]];
        const shadowAnchor = [Math.floor(size[1] / 3), size[1]];

        return L.icon({
            iconUrl:      MapUtils.ICON_URLS[color] ?? MapUtils.ICON_URLS.blue,
            shadowUrl:    MapUtils.SHADOW_URL,
            iconSize:     size,
            iconAnchor:   anchor,
            shadowSize,
            shadowAnchor,
            popupAnchor:  [1, -34],
        });
    },
};

/* ============================
   BaseMap
   ============================ */
class BaseMap {
    /**
     * @param {object} config  - Objeto de configuração passado pela view Blade
     * @param {string} config.mapId
     * @param {number} config.lat
     * @param {number} config.lng
     * @param {number} [config.zoom=16]
     * @param {boolean} [config.isEditMode=false]
     */
    constructor(config = {}) {
        this.config      = config;
        this.mapId       = config.mapId;
        this.map         = null;
        this.mainMarker  = null;   // marcador principal (azul, editável)
        this.initialized = false;
        this._debounceTimer = null;

        // Inputs e displays — convenção de IDs definida pelo componente Blade base
        this.latInput   = MapUtils.$('lat');
        this.lngInput   = MapUtils.$('lng');
        this.displayLat = MapUtils.$(`display-${this.mapId}-lat`);
        this.displayLng = MapUtils.$(`display-${this.mapId}-lng`);

        // Inputs manuais (opcionais, presentes em alguns formulários)
        this.latManual  = MapUtils.$('lat_manual');
        this.lngManual  = MapUtils.$('lng_manual');
    }

    /* ---------------------------
       Inicialização
    --------------------------- */

    /**
     * Inicializa o mapa. Deve ser chamado pelo construtor da subclasse
     * após configurar os atributos específicos.
     */
    _initMap() {
        if (this.initialized) return;

        this.mapContainer = MapUtils.$(this.mapId);
        this.container    = MapUtils.$(`leaflet-container-${this.mapId}`);

        if (!this.mapContainer || !this.container) {
            console.error(`[BaseMap] Elementos do mapa não encontrados para "${this.mapId}"`);
            return false;
        }

        const { lat, lng } = this._resolveInitialCoords();
        const zoom = this.config.zoom ?? 16;

        this._createLeafletMap(lat, lng, zoom);
        this._setupBaseMapEvents();
        this._syncInputs(lat, lng);

        // Garante redimensionamento correto após render
        setTimeout(() => this.map?.invalidateSize(), 100);

        this.initialized = true;
        return true;
    }

    /**
     * Determina as coordenadas iniciais com base no config.
     * Subclasses podem sobrescrever para lógica específica.
     */
    _resolveInitialCoords() {
        let lat = this.config.lat;
        let lng = this.config.lng;

        // Prioriza valores já salvos nos inputs hidden (ao reabrir form com erros de validação)
        const hiddenLat = MapUtils.safeParse(this.latInput?.value);
        const hiddenLng = MapUtils.safeParse(this.lngInput?.value);
        if (MapUtils.isValidCoord(hiddenLat) && MapUtils.isValidCoord(hiddenLng)) {
            lat = hiddenLat;
            lng = hiddenLng;
        }

        return { lat: lat ?? -14.2350, lng: lng ?? -51.9253 };
    }

    /* ---------------------------
       Criação do mapa Leaflet
    --------------------------- */

    _createLeafletMap(lat, lng, zoom) {
        const streetLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap',
            maxZoom: 19,
        });

        const googleSat = L.tileLayer('https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
            attribution: '© Google Maps',
            maxZoom: 21,
        });

        this.map = L.map(this.mapId, {
            center:          [lat, lng],
            zoom,
            layers:          [streetLayer],
            zoomControl:     true,
            scrollWheelZoom: true,
        });

        L.control.layers({
            'Mapa de Ruas (OSM)': streetLayer,
            'Satélite (Google)':  googleSat,
        }).addTo(this.map);
    }

    /* ---------------------------
       Marcador principal (azul)
    --------------------------- */

    /**
     * Cria ou move o marcador principal (azul, draggable).
     * @param {number} lat
     * @param {number} lng
     * @param {boolean} [draggable=true]
     */
    _setupMainMarker(lat, lng, draggable = true) {
        if (this.mainMarker) {
            this.mainMarker.setLatLng([lat, lng]);
            draggable
                ? this.mainMarker.dragging?.enable()
                : this.mainMarker.dragging?.disable();
            return;
        }

        this.mainMarker = L.marker([lat, lng], {
            draggable,
            icon: MapUtils.createIcon('blue'),
        }).addTo(this.map);

        this.mainMarker.bindTooltip('Localização Selecionada', {
            permanent:  true,
            direction:  'top',
            offset:     [0, -35],
            className:  'bg-primary text-white border-0 fw-bold rounded shadow-sm px-2 py-1',
        });

        this.mainMarker.on('dragend', () => {
            const { lat, lng } = this.mainMarker.getLatLng();
            this.updateLocation(lat, lng);
        });
    }

    /* ---------------------------
       Eventos base do mapa
    --------------------------- */

    _setupBaseMapEvents() {
        // Clique no mapa → move marcador (subclasses podem sobrescrever para adicionar validações)
        this.map.on('click', (e) => this._onMapClick(e));

        // Zoom do mapa → sincroniza range se existir
        this.map.on('zoomend', () => {
            const zoomRange = MapUtils.$('zoom_range');
            const zoomBadge = MapUtils.$('zoom_val');
            if (zoomRange) zoomRange.value = this.map.getZoom();
            if (zoomBadge) zoomBadge.innerText = this.map.getZoom();
        });
    }

    /**
     * Callback do clique no mapa. Subclasses podem sobrescrever.
     */
    _onMapClick(e) {
        this.updateLocation(e.latlng.lat, e.latlng.lng);
    }

    /* ---------------------------
       Inputs manuais (lat_manual / lng_manual)
    --------------------------- */

    _setupManualInputEvents() {
        if (this.latManual) {
            MapUtils.on(this.latManual, 'change', () => {
                const lat = MapUtils.safeParse(this.latManual.value);
                if (MapUtils.isValidCoord(lat)) {
                    const lng = this.mainMarker
                        ? this.mainMarker.getLatLng().lng
                        : MapUtils.safeParse(this.lngManual?.value);
                    this.updateLocation(lat, lng, true);
                }
            });

            // Mantém input hidden sincronizado enquanto o usuário digita
            MapUtils.on(this.latManual, 'input', () => {
                if (this.latInput) this.latInput.value = this.latManual.value;
            });
        }

        if (this.lngManual) {
            MapUtils.on(this.lngManual, 'change', () => {
                const lng = MapUtils.safeParse(this.lngManual.value);
                if (MapUtils.isValidCoord(lng)) {
                    const lat = this.mainMarker
                        ? this.mainMarker.getLatLng().lat
                        : MapUtils.safeParse(this.latManual?.value);
                    this.updateLocation(lat, lng, true);
                }
            });

            MapUtils.on(this.lngManual, 'input', () => {
                if (this.lngInput) this.lngInput.value = this.lngManual.value;
            });
        }
    }

    /* ---------------------------
       Atualização de localização
    --------------------------- */

    /**
     * Ponto central de atualização: move marcador, atualiza inputs e displays.
     * @param {number}  lat
     * @param {number}  lng
     * @param {boolean} [flyTo=false]  - Se true, anima a câmera até o ponto
     * @param {number}  [zoom]         - Zoom a usar no flyTo (padrão: 18)
     */
    updateLocation(lat, lng, flyTo = false, zoom = 18) {
        if (!MapUtils.isValidCoord(lat) || !MapUtils.isValidCoord(lng)) return;

        this._syncInputs(lat, lng);
        this._setupMainMarker(lat, lng);

        if (flyTo && this.map) {
            this.map.flyTo([lat, lng], zoom, { animate: true, duration: 1.5 });
        }
    }

    /**
     * Sincroniza inputs hidden, inputs manuais e displays de coordenadas.
     */
    _syncInputs(lat, lng) {
        const fLat = parseFloat(lat).toFixed(8);
        const fLng = parseFloat(lng).toFixed(8);

        if (this.latInput)   this.latInput.value    = fLat;
        if (this.lngInput)   this.lngInput.value    = fLng;
        if (this.latManual)  this.latManual.value   = fLat;
        if (this.lngManual)  this.lngManual.value   = fLng;
        if (this.displayLat) this.displayLat.innerText = fLat;
        if (this.displayLng) this.displayLng.innerText = fLng;
    }

    /* ---------------------------
       Geocodificação (Nominatim)
    --------------------------- */

    /**
     * Geocodificação reversa: coordenada → campos de endereço.
     * @param {number} lat
     * @param {number} lng
     */
    async reverseGeocode(lat, lng) {
        try {
            const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1&zoom=18&accept-language=pt-BR`;
            const res = await fetch(url);
            if (!res.ok) return;

            const data = await res.json();
            if (!data?.address) return;

            const addr = data.address;
            this._fillAddressFields({
                city:     addr.city || addr.town || addr.municipality || addr.village || '',
                district: addr.hamlet || addr.suburb || addr.neighbourhood || addr.city_district || addr.village || '',
                state:    addr.state || '',
                street:   addr.road || addr.pedestrian || addr.path || '',
            });
        } catch (_) { /* silencioso em produção */ }
    }

    /**
     * Geocodificação direta: campos de endereço → coordenada.
     * @param {object} fields  - { city, state, district, street }
     * @param {function} [onSuccess]  - callback(lat, lng, zoom)
     */
    searchAddress(fields = {}, onSuccess) {
        const { city = '', state = '', district = '', street = '' } = fields;
        const hasData = [city, state, district, street].some(v => v.trim().length >= 2);
        if (!hasData) return;

        const parts = [street, district, city, state, 'Brasil'].filter(Boolean);
        const query = encodeURIComponent(parts.join(', '));

        const controller = new AbortController();
        const timeout    = setTimeout(() => controller.abort(), 10000);

        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${query}&addressdetails=1&limit=1&accept-language=pt-BR`, {
            signal: controller.signal,
        })
            .then(r => { clearTimeout(timeout); return r.json(); })
            .then(data => {
                if (!data?.length) return;

                // Valida se cidade retornada bate com a buscada
                if (city) {
                    const found  = (data[0].address?.city || data[0].address?.town || '').toLowerCase();
                    const search = city.toLowerCase();
                    if (found && !found.includes(search) && !search.includes(found)) return;
                }

                const zoom = street ? 18 : district ? 16 : city ? 14 : 6;
                const lat  = parseFloat(data[0].lat);
                const lng  = parseFloat(data[0].lon);

                if (typeof onSuccess === 'function') {
                    onSuccess(lat, lng, zoom);
                } else {
                    this.updateLocation(lat, lng, true, zoom);
                }
            })
            .catch(() => { clearTimeout(timeout); });
    }

    /**
     * Preenche campos de endereço no DOM. Subclasses sobrescrevem para
     * apontar para os inputs corretos.
     */
    _fillAddressFields({ city, district, state, street }) {
        // Hook para subclasses. BaseMap não sabe quais inputs existem.
    }

    /* ---------------------------
       Utilitário de debounce
    --------------------------- */

    _debounce(fn, delay = 500) {
        clearTimeout(this._debounceTimer);
        this._debounceTimer = setTimeout(fn, delay);
    }
}

export { BaseMap, MapUtils };
