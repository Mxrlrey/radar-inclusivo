import { BaseMap, MapUtils } from './base-map.js';

/**
 * barrier-map.js
 * Mapa de barreiras: plota sede (vermelho), localizações (cinza),
 * outras barreiras (amarelo) e a barreira em edição (azul).
 * Inclui FormManager para lógica do formulário.
 * Depende de base-map.js (deve ser carregado antes).
 */

/* ============================
   Labels de status
   ============================ */
const BARRIER_STATUS_LABELS = {
    identified:     'Identificada',
    under_analysis: 'Em Análise',
    in_progress:    'Em Tratamento',
    resolved:       'Resolvida',
    not_applicable: 'Não Aplicável',
};

/* ============================
   BarrierMap
   ============================ */
class BarrierMap extends BaseMap {
    constructor(config = {}) {
        super(config);

        this.institutionMarker    = null;
        this.existingLocLayer     = L.layerGroup();  // localizações cinzas
        this.existingBarriersLayer = L.layerGroup(); // outras barreiras amarelas

        // Overlay de bloqueio (categoria sem mapa)
        this.blockedOverlay   = MapUtils.$('map-blocked-overlay');
        this.blockedTextSpan  = MapUtils.$('map-blocked-text');
        this.isBlocked        = false;

        this.institutionSelect = MapUtils.$('institution_select');

        if (!this._initMap()) return;

        this.existingLocLayer.addTo(this.map);
        this.existingBarriersLayer.addTo(this.map);

        // Dados iniciais
        if (this.config.isEditMode && this.config.barrier) {
            if (this.config.barrier.institution) {
                this.plotInstitutionAndData(this.config.barrier.institution);
            }
            this._plotCurrentBarrier(this.config.barrier);
        } else if (this.config.institution) {
            this.plotInstitutionAndData(this.config.institution);
        }

        // Cria marcador editável se necessário
        const barrier = this.config.barrier;
        const needsNewMarker = !this.config.isEditMode
            || (this.config.isEditMode && barrier
                && (!MapUtils.isValidCoord(barrier.latitude) || !MapUtils.isValidCoord(barrier.longitude)));

        if (needsNewMarker) {
            const { lat, lng } = this._resolveInitialCoords();
            this._setupMainMarker(lat, lng);
        }
    }

    /* ---------------------------
       Coordenadas iniciais
    --------------------------- */

    _resolveInitialCoords() {
        const b = this.config.barrier;
        if (this.config.isEditMode && b
            && MapUtils.isValidCoord(b.latitude)
            && MapUtils.isValidCoord(b.longitude)) {
            return { lat: b.latitude, lng: b.longitude };
        }
        return super._resolveInitialCoords();
    }

    /* ---------------------------
       Clique no mapa
    --------------------------- */

    _onMapClick(e) {
        if (this.isBlocked) return;
        if (!this.institutionSelect?.value) {
            alert('Por favor, selecione uma instituição base primeiro.');
            return;
        }
        this.updateLocation(e.latlng.lat, e.latlng.lng);
    }

    /* ---------------------------
       updateLocation com suporte a bloqueio
    --------------------------- */

    updateLocation(lat, lng, flyTo = false, zoom = 18) {
        if (this.isBlocked) return;
        super.updateLocation(lat, lng, flyTo, zoom);
    }

    /* ---------------------------
       Plotagem da instituição + dados
    --------------------------- */

    /**
     * @param {object} institution - { latitude, longitude, name, locations[], barriers[] }
     */
    plotInstitutionAndData(institution) {
        if (!institution || !MapUtils.isValidCoord(institution.latitude) || !MapUtils.isValidCoord(institution.longitude)) {
            console.error('[BarrierMap] Instituição sem coordenadas válidas.');
            return;
        }

        if (this.institutionMarker) this.map.removeLayer(this.institutionMarker);
        this.existingLocLayer.clearLayers();
        this.existingBarriersLayer.clearLayers();

        // Sede (vermelho)
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

        // Localizações existentes (cinza)
        const greyIcon = MapUtils.createIcon('grey', [20, 32], [10, 32]);
        for (const loc of institution.locations ?? []) {
            if (!MapUtils.isValidCoord(loc.latitude) || !MapUtils.isValidCoord(loc.longitude)) continue;
            const m = L.marker([loc.latitude, loc.longitude], { icon: greyIcon }).addTo(this.existingLocLayer);
            m.bindTooltip(`${loc.name} (${loc.type ?? 'Local'})`, {
                permanent:  false,
                direction:  'top',
                className:  'bg-secondary text-white border-0 small rounded shadow-sm px-2 py-1',
                offset:     [0, -32],
            });
        }

        // Outras barreiras (amarelo)
        const yellowIcon = MapUtils.createIcon('yellow', [20, 32], [10, 32]);
        for (const b of institution.barriers ?? []) {
            if (this.config.isEditMode && this.config.barrier?.id === b.id) continue;
            if (!MapUtils.isValidCoord(b.latitude) || !MapUtils.isValidCoord(b.longitude)) continue;
            const m = L.marker([b.latitude, b.longitude], { icon: yellowIcon }).addTo(this.existingBarriersLayer);
            m.bindTooltip(`${b.name} (${this._getBarrierStatus(b)})`, {
                permanent:  false,
                direction:  'top',
                className:  'bg-warning text-dark border-0 small rounded shadow-sm px-2 py-1',
                offset:     [0, -32],
            });
        }

        // Move câmera se necessário
        const hasValidBarrier = this.config.isEditMode
            && this.config.barrier
            && MapUtils.isValidCoord(this.config.barrier.latitude)
            && MapUtils.isValidCoord(this.config.barrier.longitude);

        if (!this.isBlocked && !hasValidBarrier) {
            this.updateLocation(institution.latitude, institution.longitude, true, institution.default_zoom ?? 16);
        } else if (!this.isBlocked && hasValidBarrier) {
            // mantém câmera na barreira
        } else if (this.isBlocked && this.mainMarker) {
            this.mainMarker.dragging?.disable();
        }
    }

    /* ---------------------------
       Barreira atual (modo edição)
    --------------------------- */

    _plotCurrentBarrier(barrier) {
        if (!MapUtils.isValidCoord(barrier.latitude) || !MapUtils.isValidCoord(barrier.longitude)) return;
        this.updateLocation(barrier.latitude, barrier.longitude);
        this.map.flyTo([barrier.latitude, barrier.longitude], 18, { animate: true, duration: 2 });
    }

    /* ---------------------------
       Toggle visibilidade das localizações cinzas
    --------------------------- */

    toggleGreyMarkers() {
        if (!this.map) return;
        if (this.map.hasLayer(this.existingLocLayer)) {
            this.map.removeLayer(this.existingLocLayer);
        } else {
            this.map.addLayer(this.existingLocLayer);
        }
    }

    /* ---------------------------
       Bloqueio de mapa (por categoria)
    --------------------------- */

    setBlocked(blocked, categoryName = '') {
        if (!this.map) return;
        this.isBlocked = Boolean(blocked);

        const mapWrapper = MapUtils.$('mapWrapper');
        const blockedTextSpan = MapUtils.$('blocked-message');

        if (this.isBlocked) {
            mapWrapper?.classList.add('is-blocked');
            if (blockedTextSpan) {
                blockedTextSpan.textContent = (categoryName === 'Selecione uma categoria' || !categoryName)
                    ? 'Selecione uma categoria e uma instituição para liberar o mapa.'
                    : `Esta categoria (${categoryName}) não requer marcação no mapa.`;
            }
        } else {
            mapWrapper?.classList.remove('is-blocked');
        }
    }

    /* ---------------------------
       Status da barreira
    --------------------------- */

    _getBarrierStatus(barrier) {
        if (!barrier) return 'Sem status';

        if (Array.isArray(barrier.inspections) && barrier.inspections.length > 0) {
            const latest = [...barrier.inspections].sort((a, b) => {
                return new Date(b.inspection_date ?? b.created_at) - new Date(a.inspection_date ?? a.created_at);
            })[0];
            if (latest?.status) return BARRIER_STATUS_LABELS[latest.status] ?? 'Sem status';
        }

        const key = barrier.status?.value ?? barrier.status;
        return BARRIER_STATUS_LABELS[key] ?? 'Identificada';
    }
}

/* ============================
   FormManager
   ============================ */
class FormManager {
    constructor(config = {}) {
        this.config = config;

        this.institutionSelect      = MapUtils.$('institution_select');
        this.locationSelect         = MapUtils.$('location_select');
        this.locationWrapper        = MapUtils.$('location_wrapper');
        this.isAnonymousCheck       = MapUtils.$('is_anonymous');
        this.notApplicableCheck     = MapUtils.$('not_applicable');
        this.wrapperNotApplicable   = MapUtils.$('wrapper_not_applicable');
        this.identificationFields   = MapUtils.$('identification_fields');
        this.personSelects          = MapUtils.$('person_selects');
        this.manualPersonData       = MapUtils.$('manual_person_data');
        this.affectedPersonName     = document.querySelector('input[name="affected_person_name"]');
        this.affectedPersonRole     = document.querySelector('input[name="affected_person_role"]');

        this._init();
    }

    _init() {
        this._setupListeners();
        this._applyInitialState();
    }

    _setupListeners() {
        MapUtils.on(this.institutionSelect, 'change', () => {this.handleInstitutionChange();this._validateMapLock();});
        MapUtils.on(this.locationSelect, 'change', () => this._handleLocationSelectChange());
        MapUtils.on(this.isAnonymousCheck, 'change', () => this.togglePersonFields());
        MapUtils.on(this.notApplicableCheck, 'change', () => this.togglePersonFields());
        const catSelect = MapUtils.$('barrier_category_id');
        MapUtils.on(catSelect, 'change', () => {this._handleCategoryChange();this._validateMapLock();});
    }

    _applyInitialState() {
        if (this.config.isEditMode && this.config.barrier) {
            const b = this.config.barrier;
            if (this.isAnonymousCheck)   this.isAnonymousCheck.checked   = !!b.is_anonymous;
            if (this.notApplicableCheck) this.notApplicableCheck.checked = !!b.not_applicable;

            if (this.institutionSelect && b.institution_id) {
                this.institutionSelect.value = b.institution_id;
                setTimeout(() => this.handleInstitutionChange(), 80);
            }
        }

        this.togglePersonFields();

        if (window.oldLocationId && this.locationSelect) {
            setTimeout(() => {
                this.locationSelect.value = window.oldLocationId;
                if (this.locationSelect.value === window.oldLocationId) {
                    this._handleLocationSelectChange();
                }
            }, 120);
        }

        setTimeout(() => this._handleCategoryChange(), 80);
    }

    _validateMapLock() {
        const catSelect = MapUtils.$('barrier_category_id');
        const instSelect = MapUtils.$('institution_select');

        const categoryId = catSelect?.value;
        const institutionId = instSelect?.value;

        if (!categoryId) {
            window.barrierMapInstance?.setBlocked(true, 'Selecione uma categoria');
            return;
        }

        const categoryBlocks = Boolean(window.categoriesData?.[categoryId]);
        const categoryName = catSelect.options[catSelect.selectedIndex]?.textContent ?? '';

        if (categoryBlocks || !institutionId) {
            window.barrierMapInstance?.setBlocked(true, categoryBlocks ? categoryName : '');
        } else {
            window.barrierMapInstance?.setBlocked(false);
        }
    }

    /* ---------------------------
       Instituição → carrega localizações
    --------------------------- */

    handleInstitutionChange() {
        const val = this.institutionSelect?.value;

        if (!val) {
            this.locationWrapper?.classList.add('d-none');
            if (this.locationSelect) this.locationSelect.innerHTML = '<option value="">Selecione um local...</option>';
            this._validateMapLock();
            return;
        }

        this.locationWrapper?.classList.remove('d-none');
        this._loadInstitutionLocations(val);

        if (window.barrierMapInstance && Array.isArray(window.institutionsData)) {
            const inst = window.institutionsData.find(i => String(i.id) === String(val));
            if (inst) window.barrierMapInstance.plotInstitutionAndData(inst);
        }

        this._validateMapLock();
    }

    _loadInstitutionLocations(institutionId) {
        if (!institutionId || !Array.isArray(window.institutionsData) || !this.locationSelect) return;

        const inst = window.institutionsData.find(i => String(i.id) === String(institutionId));
        if (!inst) return;

        this.locationSelect.innerHTML = '';
        const frag = document.createDocumentFragment();

        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = 'Selecione um local...';
        frag.appendChild(placeholder);

        for (const loc of inst.locations ?? []) {
            const opt = document.createElement('option');
            opt.value = loc.id ?? '';
            opt.textContent = loc.name ?? '';
            if (loc.latitude  != null) opt.setAttribute('data-lat', loc.latitude);
            if (loc.longitude != null) opt.setAttribute('data-lng', loc.longitude);
            frag.appendChild(opt);
        }

        this.locationSelect.appendChild(frag);

        // Restaura localização em edição
        if (this.config.isEditMode && this.config.barrier?.location_id) {
            setTimeout(() => {
                this.locationSelect.value = this.config.barrier.location_id;
                if (this.locationSelect.value == this.config.barrier.location_id) {
                    this._handleLocationSelectChange();
                }
            }, 80);
        }
    }

    _handleLocationSelectChange() {
        if (!this.locationSelect) return;
        const opt = this.locationSelect.selectedOptions?.[0];
        if (!opt) return;

        const lat = opt.getAttribute('data-lat');
        const lng = opt.getAttribute('data-lng');

        if (MapUtils.isValidCoord(lat) && MapUtils.isValidCoord(lng) && window.barrierMapInstance) {
            window.barrierMapInstance.updateLocation(Number(lat), Number(lng), true);
        }
    }

    /* ---------------------------
       Categoria → bloqueia mapa se aplicável
    --------------------------- */

    _handleCategoryChange() {
        const select = MapUtils.$('barrier_category_id');
        const institutionSelect = MapUtils.$('institution_select');
        const categoryId = select?.value;
        const institutionId = institutionSelect?.value;

        if (!categoryId) {
            window.barrierMapInstance?.setBlocked(true, 'Selecione uma categoria');
            return;
        }

        const categoryBlocks = Boolean(window.categoriesData?.[categoryId]);
        const categoryName = select.options[select.selectedIndex]?.textContent ?? '';

        if (categoryBlocks || !institutionId) {
            window.barrierMapInstance?.setBlocked(true, categoryBlocks ? categoryName : '');
        } else {
            window.barrierMapInstance?.setBlocked(false);
        }
    }

    /* ---------------------------
       Campos de pessoa (anônimo / não aplicável)
    --------------------------- */

    togglePersonFields() {
        const isAnonymous   = this.isAnonymousCheck?.checked   ?? false;
        const notApplicable = this.notApplicableCheck?.checked ?? false;

        if (isAnonymous) {
            this.wrapperNotApplicable?.classList.add('d-none');
            this.identificationFields?.classList.add('d-none');
            if (this.notApplicableCheck) this.notApplicableCheck.checked = false;
            this._clearPersonFields();
            return;
        }

        this.wrapperNotApplicable?.classList.remove('d-none');
        this.identificationFields?.classList.remove('d-none');

        if (notApplicable) {
            this.personSelects?.classList.add('d-none');
            this.manualPersonData?.classList.remove('d-none');
        } else {
            this.personSelects?.classList.remove('d-none');
            this.manualPersonData?.classList.add('d-none');
            this._clearPersonFields();
        }
    }

    _clearPersonFields() {
        if (this.affectedPersonName) this.affectedPersonName.value = '';
        if (this.affectedPersonRole) this.affectedPersonRole.value = '';
    }
}

/* ============================
   Bootstrap
   ============================ */
document.addEventListener('DOMContentLoaded', () => {
    if (!MapUtils.$('map-barrier') || !window.barrierMapConfig || typeof L === 'undefined') {
        console.error('[BarrierMap] Dependências não encontradas.');
        return;
    }

    try {
        const map = new BarrierMap(window.barrierMapConfig);
        window.barrierMapInstance = map;
        window.BarrierMap = BarrierMap;

        const fm = new FormManager(window.barrierMapConfig);
        window.formManagerInstance = fm;
        window.FormManager = FormManager;

        // Toggle de localizações cinzas
        const toggleBtn = MapUtils.$('btn-toggle-locations');
        MapUtils.on(toggleBtn, 'change', () => map.toggleGreyMarkers());

        // Select de instituição (listener externo, além do FormManager interno)
        const instSel = MapUtils.$('institution_select');
        MapUtils.on(instSel, 'change', function () {
            fm.handleInstitutionChange();
            if (toggleBtn) toggleBtn.checked = true;
        });

        // Carrega localizações iniciais
        if (window.initialInstitutionId) {
            setTimeout(() => fm._loadInstitutionLocations(window.initialInstitutionId), 120);
        }

    } catch (err) {
        console.error('[BarrierMap] Erro na inicialização:', err);
    }
});
