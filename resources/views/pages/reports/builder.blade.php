@extends('layouts.master')

@section('title', 'Gerador de Relatórios')

@section('content')

    <div class="report-builder-page">
        <div class="mb-5">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Relatórios' => null,
            ]" />
        </div>

        <div class="d-flex justify-content-between align-items-start mb-4">
            <div>
                <h2 class="text-title">Gerador de Relatórios e Consultas</h2>
                <p class="text-muted mb-0">
                    Monte seu relatório de forma simples, escolhendo os dados, os campos e os filtros que deseja visualizar.
                </p>
            </div>
        </div>

        <div class="card-custom report-builder-card overflow-hidden">
            <div class="row g-0">

            <x-forms.section class="col-12 report-builder-section" title="Escolha o tipo de relatório" />
            <div class="col-12 p-4 border-bottom report-builder-panel">
                <div class="report-builder-banner d-flex gap-3 align-items-start mb-0">
                    <i class="fas fa-circle-info mt-1 fs-5"></i>
                    <div>
                        <strong class="d-block mb-1">Comece por aqui</strong>
                        <small>
                            Selecione o tipo de dado principal que deseja consultar. Depois disso, os campos aparecerão automaticamente.
                        </small>
                    </div>
                </div>

                <div class="row g-3 align-items-end mt-3">
                    <div class="col-12 col-lg-7">
                        <x-forms.select
                            name="model-select"
                            label="Tipo de dado"
                            id="model-select"
                            :options="[]"
                            required
                        />
                    </div>
                </div>
            </div>

            <x-forms.section class="col-12 report-builder-section" title="Selecione os campos que deseja exibir" />
            <div class="col-12 p-4 border-bottom report-builder-panel">
                <div class="report-builder-banner d-flex gap-3 align-items-start mb-4">
                    <i class="fas fa-lightbulb mt-1 fs-5"></i>
                    <div>
                        <strong class="d-block mb-1">Dica rápida</strong>
                        <small>
                            Marque apenas os campos que realmente importam. Quanto mais objetivo for o relatório, mais fácil será a leitura.
                        </small>
                    </div>
                </div>

                <div class="mb-4">
                    <p class="small fw-semibold text-muted mb-2">Dados principais</p>
                    <div id="columns-container" class="report-builder-option-grid">
                        <span class="text-muted small fst-italic">
                            Selecione o tipo de dado acima para ver as opções.
                        </span>
                    </div>
                </div>

                <div class="border-top my-4"></div>

                <div id="relations-section" class="d-none">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <p class="small fw-semibold text-muted mb-0">Dados relacionados</p>
                        <span class="badge bg-secondary">opcional</span>
                    </div>

                    <div class="report-builder-banner warning d-flex gap-3 align-items-start mb-4">
                        <i class="fas fa-triangle-exclamation mt-1 fs-5"></i>
                        <div>
                            <strong class="d-block mb-1">Use somente se precisar</strong>
                            <small>
                                Esta parte adiciona informações ligadas ao cadastro principal, como vínculos e dados complementares.
                            </small>
                        </div>
                    </div>

                    <div class="row g-3 align-items-end mb-3" style="max-width: 760px;">
                        <div class="col-12 col-md-8">
                            <x-forms.select
                                name="relation-select"
                                label="Adicionar dado relacionado"
                                id="relation-select"
                                :options="[]"
                            />
                        </div>

                        <div class="col-12 col-md-4 ">
                            <div class="w-100">
                                <x-buttons.submit-button
                                    type="button"
                                    id="btn-add-relation"
                                    variant="new"
                                    class=" mb-4"
                                >
                                    <span class="btn-label"><i class="fa fa-plus"></i></span>
                                    Adicionar
                                </x-buttons.submit-button>
                            </div>
                        </div>
                    </div>

                    <div id="added-relations-area"></div>
                </div>
            </div>

            <x-forms.section class="col-12 report-builder-section" title="Refine os resultados com filtros" />
            <div class="col-12 p-4 border-bottom report-builder-panel">
                <div class="report-builder-banner success d-flex gap-3 align-items-start mb-4">
                    <i class="fa fa-filter mt-1 fs-5"></i>
                    <div>
                        <strong class="d-block mb-1">Filtragem opcional</strong>
                        <small>
                            Adicione filtros somente se quiser limitar o relatório por nome, data, status ou outro campo disponível.
                        </small>
                    </div>
                </div>

                <div id="filters-list" class="d-flex flex-column gap-3"></div>

                <div class="mt-3">
                    <x-buttons.submit-button type="button" variant="secondary" onclick="addFilterRow()">
                        <span class="btn-label"><i class="fa fa-plus"></i></span>
                        Adicionar filtro
                    </x-buttons.submit-button>
                </div>
            </div>

            <div class="col-12 p-4 border-bottom report-builder-actions">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div>
                        <p class="mb-0 fw-semibold">Pronto para visualizar?</p>
                        <small class="text-muted">
                            Gere a prévia antes de exportar para conferir se o resultado está correto.
                        </small>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <x-buttons.submit-button type="button" id="btn-run" variant="new" onclick="runReport()" disabled>
                            <span class="btn-label"><i class="fa fa-table"></i></span>
                            Gerar prévia
                        </x-buttons.submit-button>
                    </div>
                </div>
            </div>

            <x-forms.section class="col-12 report-builder-section" title="Prévia do relatório" />
            <div class="col-12 p-4 report-builder-panel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <p class="fw-semibold mb-1">Resultado gerado</p>
                        <small class="text-muted">
                            Confira o conteúdo antes de seguir com a exportação.
                        </small>
                    </div>
                    <span id="preview-count-badge" class="badge bg-secondary d-none"></span>
                </div>

                <div id="preview-empty" class="report-builder-empty text-center py-5 px-4">
                    <i class="fas fa-chart-bar text-muted mb-3 d-block" style="font-size:2.5rem;opacity:.2"></i>
                    <p class="text-muted mb-0">Nenhum relatório gerado ainda.</p>
                    <small class="text-muted">Escolha as opções acima e clique em “Gerar prévia”.</small>
                </div>

                <div id="preview-loading" class="d-none text-center py-5">
                    <div class="spinner-border text-secondary mb-3" role="status"></div>
                    <p class="text-muted small mb-0">Buscando dados...</p>
                </div>

                <div id="preview-error" class="d-none report-builder-error">
                    <div class="alert alert-danger mb-0">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <span id="preview-error-msg"></span>
                    </div>
                </div>

                <div id="preview-section" class="d-none report-builder-preview overflow-hidden">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0 report-builder-preview-table" style="font-size:13px">
                            <thead class="table-light" id="preview-head"></thead>
                            <tbody id="preview-body"></tbody>
                        </table>
                    </div>

                    <div class="report-builder-preview-footer">
                        <small class="text-muted" id="preview-footer-info"></small>
                        <x-buttons.submit-button type="button" variant="secondary" onclick="copyTable()">
                            <span class="btn-label"><i class="fa fa-copy"></i></span>
                            Copiar
                        </x-buttons.submit-button>
                    </div>
                </div>
            </div>

            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .report-builder-page {
                width: 100%;
                min-width: 0;
            }

            .report-builder-card {
                width: 100%;
                max-width: 100%;
                background: var(--card-bg);
                border-color: var(--card-border) !important;
                margin-bottom: 0;
            }

            .report-builder-card .row {
                margin-left: 0;
                margin-right: 0;
            }

            .report-builder-section {
                padding: 1.5rem 1.5rem 0;
                margin-top: 0;
                background: var(--card-bg);
                border-top: 1px solid var(--border-color-light);
            }

            .report-builder-section .form-section-title,
            .report-builder-section .form-section-description {
                padding-left: 0;
                padding-right: 0;
            }

            .report-builder-section .form-section-title {
                margin-bottom: .5rem;
            }

            .report-builder-panel {
                background: var(--card-bg);
            }

            .report-builder-banner {
                padding: 1rem 1.25rem;
                border-left: 4px solid var(--color-primary);
                background: var(--bg-surface-secondary);
            }

            .report-builder-banner.warning {
                border-left-color: var(--color-warning);
            }

            .report-builder-banner.success {
                border-left-color: var(--color-success);
            }

            .report-builder-option-grid {
                display: flex;
                flex-wrap: wrap;
                gap: .75rem 1rem;
                min-width: 0;
            }

            .report-builder-check {
                display: flex;
                align-items: flex-start;
                min-width: 220px;
                max-width: 100%;
                flex: 1 1 260px;
                padding: .75rem 1rem;
                border: 1px solid var(--border-color);
                background: var(--bg-surface);
            }

            .report-builder-check .custom-checkbox-wrapper {
                width: 100%;
                margin-bottom: 0;
                align-items: flex-start;
            }

            .report-builder-check .form-check-label {
                font-weight: var(--font-weight-medium);
                color: var(--text-primary);
            }

            .report-builder-relation {
                border: 1px solid var(--border-color);
                background: var(--bg-surface-secondary);
            }

            .report-builder-relation-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 1rem;
                padding: .9rem 1rem;
                border-bottom: 1px solid var(--border-color);
                background: var(--bg-surface);
            }

            .report-builder-relation-body {
                padding: 1rem;
            }

            .report-builder-filter-row {
                border: 1px solid var(--border-color);
                background: var(--bg-surface-secondary);
                padding: 1rem;
            }

            .report-builder-actions {
                background: var(--bg-surface-secondary) !important;
            }

            .report-builder-empty,
            .report-builder-preview,
            .report-builder-error {
                border: 1px solid var(--border-color);
                background: var(--bg-surface);
            }

            .report-builder-preview-table thead th {
                background: var(--table-header-bg);
                color: var(--table-header-color);
                white-space: nowrap;
            }

            .report-builder-preview-table tbody td {
                white-space: nowrap;
                vertical-align: middle;
            }

            .report-builder-preview-footer {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 1rem;
                padding: .9rem 1rem;
                border-top: 1px solid var(--border-color);
                background: var(--bg-surface-secondary);
            }

            .report-builder-card .alert,
            .report-builder-card .table-responsive,
            .report-builder-card #preview-section {
                max-width: 100%;
            }

            .report-builder-card #columns-container,
            .report-builder-card #added-relations-area,
            .report-builder-card #filters-list {
                min-width: 0;
            }

            .report-builder-card .table-responsive table {
                min-width: 100%;
            }

            @media (max-width: 768px) {
                .report-builder-section {
                    padding: 1.25rem 1rem 0;
                }

                .report-builder-check {
                    min-width: 100%;
                    flex-basis: 100%;
                }

                .report-builder-relation-header,
                .report-builder-preview-footer {
                    flex-direction: column;
                    align-items: flex-start;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            const MAX_COLUMNS = 5;

            document.addEventListener('change', function (e) {

                if (e.target.name !== 'cols') return;

                const selected = document.querySelectorAll(
                    'input[name="cols"]:checked'
                );

                if (selected.length > MAX_COLUMNS) {
                    e.target.checked = false;

                    toast(
                        `Você pode selecionar no máximo ${MAX_COLUMNS} colunas.`,
                        'warning'
                    );
                }

            });

            const csrf = document.querySelector('meta[name="csrf-token"]')?.content ?? ''

            let meta = null
            let addedRelations = []

            document.addEventListener('DOMContentLoaded', loadEntities)

            document.getElementById('btn-add-relation').addEventListener('click', () => {
                const sel = document.getElementById('relation-select')
                const relName = sel.value

                if (!relName) return toast('Selecione um dado relacionado.', 'warning')

                const rel = (meta.relations ?? []).find(r => r.name === relName)
                if (!rel) return

                if (addedRelations.find(r => r.name === relName)) {
                    return toast('Esse dado já foi adicionado.', 'info')
                }

                addedRelations.push(rel)
                renderAddedRelations()
                updateFilterOptions()
            })

            async function loadEntities () {
                try {
                    const list = await fetch('/relatorios/dados-disponiveis', { credentials: 'same-origin' }).then(r => r.json())
                    const sel = document.getElementById('model-select')

                    sel.innerHTML = '<option value="">-- selecione o tipo de dado --</option>'
                    list.forEach(e => {
                        let label = e.label
                        if (typeof label === 'string' && label.startsWith('database.models.')) {
                            label = e.class.split('\\').pop()
                        }
                        sel.innerHTML += `<option value="${e.class}">${label}</option>`
                    })

                    sel.addEventListener('change', () => {
                        resetAll()
                        if (sel.value) loadMeta(sel.value)
                    })
                } catch {
                    document.getElementById('model-select').innerHTML = '<option>Erro ao carregar</option>'
                }
            }

            async function loadMeta (modelClass) {
                try {
                    const res = await fetch(`/relatorios/metadados?model=${encodeURIComponent(modelClass)}`, {
                        credentials: 'same-origin'
                    })

                    if (!res.ok) throw new Error('HTTP ' + res.status)

                    meta = await res.json()
                    meta.class = modelClass

                    renderColumns()
                    populateRelationSelect()
                    document.getElementById('relations-section').classList.remove('d-none')
                    document.getElementById('btn-run').removeAttribute('disabled')
                    updateFilterOptions()
                } catch {
                    toast('Falha ao carregar os dados da entidade.', 'danger')
                }
            }

            function renderColumns () {
                const c = document.getElementById('columns-container')
                c.innerHTML = ''

                Object.entries(meta.columns ?? {}).forEach(([k, label]) => {
                    c.innerHTML += `
                        <div class="report-builder-check">
                            <div class="custom-checkbox-wrapper">
                                <input type="checkbox" name="cols" value="${k}" id="col-${sanitizeId(k)}" class="form-check-input custom-checkbox">
                                <label class="form-check-label" for="col-${sanitizeId(k)}">
                                    <span class="fw-bold">${label}</span>
                                </label>
                            </div>
                        </div>`
                })
            }

            function populateRelationSelect () {
                const sel = document.getElementById('relation-select')
                sel.innerHTML = '<option value="">-- selecione --</option>'

                ;(meta.relations ?? []).forEach(rel => {
                    sel.innerHTML += `<option value="${rel.name}">${rel.label}</option>`
                })
            }

            function renderAddedRelations () {
                const area = document.getElementById('added-relations-area')
                area.innerHTML = ''

                addedRelations.forEach(rel => {
                    const colsHtml = Object.entries(rel.columns ?? {}).map(([ck, cLabel]) => `
                        <div class="report-builder-check">
                            <div class="custom-checkbox-wrapper">
                                <input type="checkbox" name="cols" value="${rel.name}.${ck}" id="col-${sanitizeId(`${rel.name}.${ck}`)}" class="form-check-input custom-checkbox">
                                <label class="form-check-label" for="col-${sanitizeId(`${rel.name}.${ck}`)}">
                                    <span class="fw-bold">${cLabel}</span>
                                </label>
                            </div>
                        </div>`).join('')

                    const pivotHtml = rel.pivot?.columns
                        ? `<div class="mt-3 pt-3 border-top">
                               <small class="text-muted d-block mb-2">Campos do vínculo</small>
                               <div class="report-builder-option-grid">
                                   ${Object.entries(rel.pivot.columns).map(([pk, pl]) => `
                                       <div class="report-builder-check">
                                           <div class="custom-checkbox-wrapper">
                                               <input type="checkbox" name="cols" value="${rel.name}.pivot.${pk}" id="col-${sanitizeId(`${rel.name}.pivot.${pk}`)}" class="form-check-input custom-checkbox">
                                               <label class="form-check-label" for="col-${sanitizeId(`${rel.name}.pivot.${pk}`)}">
                                                   <span class="fw-bold">${pl}</span>
                                               </label>
                                           </div>
                                       </div>`).join('')}
                               </div>
                           </div>`
                        : ''

                    area.innerHTML += `
                        <div class="report-builder-relation overflow-hidden mb-3">
                            <div class="report-builder-relation-header">
                                <strong class="text-title mb-0" style="font-size:13px">${rel.label}</strong>
                                <button
                                    type="button"
                                    class="btn-action danger waves-effect waves-light"
                                    onclick="removeRelation('${rel.name}')"
                                >
                                    <span class="btn-label"><i class="fa fa-times"></i></span>
                                    Remover
                                </button>
                            </div>
                            <div class="report-builder-relation-body">
                                <div class="report-builder-option-grid">${colsHtml}</div>
                                ${pivotHtml}
                            </div>
                        </div>`
                })
            }

            function removeRelation (name) {
                addedRelations = addedRelations.filter(r => r.name !== name)
                renderAddedRelations()
                updateFilterOptions()
            }

            function updateFilterOptions () {
                const opts = []

                Object.entries(meta?.columns ?? {}).forEach(([k, v]) => {
                    opts.push({ value: k, label: v })
                })

                addedRelations.forEach(rel => {
                    Object.entries(rel.columns ?? {}).forEach(([k, v]) => {
                        opts.push({ value: `${rel.name}.${k}`, label: `${rel.label} › ${v}` })
                    })

                    if (rel.pivot?.columns) {
                        Object.entries(rel.pivot.columns).forEach(([k, v]) => {
                            opts.push({ value: `${rel.name}.pivot.${k}`, label: `${rel.label} (vínculo) › ${v}` })
                        })
                    }
                })

                document.querySelectorAll('.f-col').forEach(sel => {
                    const cur = sel.value
                    sel.innerHTML = opts.map(o => `<option value="${o.value}">${o.label}</option>`).join('')
                    if (cur) sel.value = cur
                })

                window.__filterOpts = opts
            }

            function addFilterRow () {
                if (!meta) return toast('Selecione o tipo de dado primeiro.', 'warning')

                const opts = window.__filterOpts ?? []

                const div = document.createElement('div')
                div.className = 'report-builder-filter-row'
                div.innerHTML = `
                    <div class="row g-2 align-items-end">
                        <div class="col-12 col-lg-5">
                            <label class="form-label small text-muted mb-1">Campo</label>
                            <select class="form-select form-select-sm f-col">
                                ${opts.map(o => `<option value="${o.value}">${o.label}</option>`).join('')}
                            </select>
                        </div>

                        <div class="col-6 col-lg-3">
                            <label class="form-label small text-muted mb-1">Condição</label>
                            <select class="form-select form-select-sm f-op">
                                <option value="=">é igual a</option>
                                <option value="like">contém</option>
                                <option value="!=">diferente de</option>
                                <option value=">">maior que</option>
                                <option value="<">menor que</option>
                            </select>
                        </div>

                        <div class="col-6 col-lg-3">
                            <label class="form-label small text-muted mb-1">Valor</label>
                            <input class="form-control form-control-sm f-val" placeholder="Digite o valor...">
                        </div>

                        <div class="col-12 col-lg-auto">
                            <button
                                type="button"
                                class="btn-action danger waves-effect waves-light"
                                onclick="this.closest('.report-builder-filter-row').remove()"
                            >
                                <span class="btn-label"><i class="fa fa-times"></i></span>
                                Remover
                            </button>
                        </div>
                    </div>`
                document.getElementById('filters-list').appendChild(div)
            }

            async function runReport () {
                const cols = Array.from(document.querySelectorAll('input[name="cols"]:checked')).map(i => i.value)

                if (!meta) return toast('Selecione o tipo de dado.', 'warning')
                if (!cols.length) return toast('Marque pelo menos uma coluna.', 'warning')

                const filters = Array.from(document.querySelectorAll('#filters-list .report-builder-filter-row')).map(row => ({
                    column: row.querySelector('.f-col').value,
                    operator: row.querySelector('.f-op').value,
                    value: row.querySelector('.f-val').value,
                })).filter(f => f.value !== '')

                showLoading()

                try {
                    const res = await fetch('/relatorios/gerar', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf
                        },
                        body: JSON.stringify({
                            model: meta.class,
                            columns: cols,
                            filters,
                            limit: 200,
                        }),
                    })

                    const data = await res.json()
                    if (!res.ok || data.error) throw new Error(data.error ?? 'Erro desconhecido')

                    const rows = data.rows ?? []
                    if (!rows.length) return showEmpty('Nenhum resultado encontrado para os filtros aplicados.')

                    const headers = Object.keys(rows[0]).map(k => getLabelForKey(k.replace(/__/g, '.')))
                    renderPreview(rows, headers)
                } catch (err) {
                    showError(err.message)
                }
            }

            function renderPreview (rows, headers) {
                hideStates()
                document.getElementById('preview-section').classList.remove('d-none')

                document.getElementById('preview-head').innerHTML =
                    '<tr>' + headers.map(h => `<th class="text-nowrap">${esc(h)}</th>`).join('') + '</tr>'

                document.getElementById('preview-body').innerHTML = rows.map(r =>
                    '<tr>' + Object.values(r).map(v => `<td>${esc(v ?? '')}</td>`).join('') + '</tr>'
                ).join('')

                const badge = document.getElementById('preview-count-badge')
                badge.textContent = `${rows.length} registro${rows.length !== 1 ? 's' : ''}`
                badge.classList.remove('d-none')

                document.getElementById('preview-footer-info').textContent =
                    `${rows.length} linha${rows.length !== 1 ? 's' : ''} · ${headers.length} coluna${headers.length !== 1 ? 's' : ''}`
            }

            function showLoading () {
                hideStates()
                document.getElementById('preview-loading').classList.remove('d-none')
            }

            function showEmpty (msg = 'Nenhum resultado encontrado.') {
                hideStates()
                const el = document.getElementById('preview-empty')
                el.querySelector('p').textContent = msg
                el.classList.remove('d-none')
            }

            function showError (msg) {
                hideStates()
                document.getElementById('preview-error').classList.remove('d-none')
                document.getElementById('preview-error-msg').textContent = msg
            }

            function hideStates () {
                ['preview-empty', 'preview-loading', 'preview-section', 'preview-error']
                    .forEach(id => document.getElementById(id).classList.add('d-none'))

                document.getElementById('preview-count-badge').classList.add('d-none')
            }

            function getLabelForKey (key) {
                if (!meta) return key

                const normalized = key.replace(/__/g, '.')

                // 1) se o backend já definiu o rótulo exato, usa ele
                if (meta.columns && meta.columns[normalized]) {
                    return meta.columns[normalized]
                }

                // 2) fallback para colunas simples
                if (!normalized.includes('.')) {
                    return meta.columns?.[normalized] ?? normalized
                }

                // 3) fallback para relações
                const [rel, ...rest] = normalized.split('.')
                const r = addedRelations.find(r => r.name === rel) ?? (meta.relations ?? []).find(r => r.name === rel)

                if (rest[0] === 'pivot') {
                    return `${r?.label ?? rel} (vínculo) › ${r?.pivot?.columns?.[rest[1]] ?? rest[1]}`
                }

                return `${r?.label ?? rel} › ${r?.columns?.[rest.join('.')] ?? r?.columns?.[rest[0]] ?? rest.join('.')}`
            }

            function resetAll () {
                addedRelations = []
                document.getElementById('columns-container').innerHTML =
                    '<span class="text-muted small fst-italic">Selecione o tipo de dado acima para ver as opções.</span>'
                document.getElementById('added-relations-area').innerHTML = ''
                document.getElementById('relations-section').classList.add('d-none')
                document.getElementById('filters-list').innerHTML = ''
                window.__filterOpts = []
                document.getElementById('btn-run').setAttribute('disabled', true)
                hideStates()
                document.getElementById('preview-empty').classList.remove('d-none')
            }

            function sanitizeId (value) {
                return String(value).replace(/[^a-zA-Z0-9_-]/g, '-')
            }

            async function copyTable () {
                try {
                    const headCells = Array.from(
                        document.querySelectorAll('#preview-head th')
                    ).map(th => th.textContent.trim())

                    const rows = Array.from(
                        document.querySelectorAll('#preview-body tr')
                    ).map(tr =>
                        Array.from(tr.querySelectorAll('td'))
                            .map(td => td.textContent.trim())
                    )

                    // TEXTO (fallback)
                    const textLines = [
                        headCells.join('\t'),
                        ...rows.map(r => r.join('\t'))
                    ].join('\n')

                    // HTML (para Docs / Excel / etc)
                    let html = '<table border="1" style="border-collapse:collapse;">'

                    html += '<thead><tr>'
                    headCells.forEach(h => {
                        html += `<th style="padding:4px;">${esc(h)}</th>`
                    })
                    html += '</tr></thead>'

                    html += '<tbody>'
                    rows.forEach(r => {
                        html += '<tr>'
                        r.forEach(c => {
                            html += `<td style="padding:4px;">${esc(c)}</td>`
                        })
                        html += '</tr>'
                    })
                    html += '</tbody></table>'

                    const supportsRichClipboard =
                        typeof navigator !== 'undefined' &&
                        !!navigator.clipboard &&
                        typeof navigator.clipboard.write === 'function' &&
                        typeof window.ClipboardItem !== 'undefined'

                    if (supportsRichClipboard) {
                        await navigator.clipboard.write([
                            new ClipboardItem({
                                'text/html': new Blob([html], { type: 'text/html' }),
                                'text/plain': new Blob([textLines], { type: 'text/plain' })
                            })
                        ])
                    } else if (navigator.clipboard && typeof navigator.clipboard.writeText === 'function') {
                        await navigator.clipboard.writeText(textLines)
                    } else {
                        legacyCopyText(textLines)
                    }

                    toast('Tabela copiada!', 'success')

                } catch (err) {
                    try {
                        const headCells = Array.from(
                            document.querySelectorAll('#preview-head th')
                        ).map(th => th.textContent.trim())

                        const rows = Array.from(
                            document.querySelectorAll('#preview-body tr')
                        ).map(tr =>
                            Array.from(tr.querySelectorAll('td'))
                                .map(td => td.textContent.trim())
                        )

                        const textLines = [
                            headCells.join('\t'),
                            ...rows.map(r => r.join('\t'))
                        ].join('\n')

                        legacyCopyText(textLines)
                        toast('Tabela copiada!', 'success')
                    } catch (fallbackErr) {
                        console.error(err, fallbackErr)
                        toast('Não foi possível copiar.', 'warning')
                    }
                }
            }

            function legacyCopyText (text) {
                const textarea = document.createElement('textarea')
                textarea.value = text
                textarea.setAttribute('readonly', '')
                textarea.style.position = 'fixed'
                textarea.style.top = '-9999px'
                textarea.style.left = '-9999px'
                document.body.appendChild(textarea)
                textarea.focus()
                textarea.select()
                textarea.setSelectionRange(0, textarea.value.length)
                document.execCommand('copy')
                document.body.removeChild(textarea)
            }

            function esc (str) {
                return ('' + str).replace(/[&<>"']/g, m =>
                    ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[m]))
            }

            function toast (msg, type = 'info') {
                const el = document.createElement('div')
                el.className = `alert alert-${type} alert-dismissible position-fixed bottom-0 end-0 m-3 shadow`
                el.style.cssText = 'z-index:9999;max-width:340px;font-size:14px'
                el.innerHTML = `${msg} <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>`
                document.body.appendChild(el)
                setTimeout(() => el.remove(), 4000)
            }
        </script>
    @endpush

@endsection
