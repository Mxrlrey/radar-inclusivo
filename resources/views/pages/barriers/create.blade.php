@extends('layouts.master')

@section('title', 'Relatar Barreira')

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Barreiras' => route('barreiras.index'),
                'Cadastrar' => null
            ]" />

            <h1>Relatar Barreira</h1>

            <p class="text-muted mb-0">
                Registre um ponto de obstrução ou dificuldade encontrada no campus.
            </p>
        </div>

        <div class="page-header-actions">
            <x-buttons.link-button
                :href="route('barreiras.index')"
                variant="secondary"
            >
                <x-slot:icon><i class="fa fa-times"></i></x-slot:icon>
                Cancelar
            </x-buttons.link-button>
        </div>
    </div>

    <x-forms.form-card
        action="{{ route('barreiras.salvar') }}"
        method="POST"
        enctype="multipart/form-data"
        class="form-horizontal"
    >
        @csrf

        <div class="row g-0">
            <div class="col-lg-5 border-end">
                <x-forms.section
                    title="Detalhes da Ocorrência"
                    description="Informe os dados principais da barreira identificada."
                />

                <div class="px-4">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <x-forms.input
                                name="name"
                                label="Título do Relato"
                                required
                                :value="old('name')"
                                placeholder="Ex: Calçada irregular"
                            />
                        </div>

                        <div class="col-md-4">
                            <x-forms.input
                                type="date"
                                name="identified_at"
                                label="Data"
                                required
                                :value="old('identified_at', now()->format('Y-m-d'))"
                            />
                        </div>

                        <div class="col-md-6">
                            <x-forms.select
                                name="priority"
                                label="Prioridade"
                                :options="$priorities"
                                :selected="old('priority', 'medium')"
                            />
                        </div>

                        <div class="col-md-6">
                            <x-forms.select
                                name="barrier_category_id"
                                id="barrier_category_id"
                                label="Categoria"
                                required
                                :options="$categories->pluck('name', 'id')"
                                :selected="old('barrier_category_id')"
                                extraAttributes="data-blocks-map-options"
                            />
                        </div>

                        <div class="col-md-6">
                            <x-forms.select
                                name="institution_id"
                                id="institution_select"
                                label="Campus / Unidade"
                                required
                                :options="$institutions->pluck('name', 'id')"
                                :selected="old('institution_id')"
                                :resourceObjects="$institutions"
                            />
                        </div>

                        <div class="col-md-6">
                            <x-forms.select
                                name="location_id"
                                id="location_select"
                                label="Local / Ponto de Referência"
                                :options="[]"
                                :selected="old('location_id')"
                            />
                        </div>

                        <div id="location_wrapper" class="{{ old('institution_id') ? '' : 'd-none' }} col-md-12 mt-3">
                            <x-forms.textarea
                                name="location_specific_details"
                                label="Complemento"
                                rows="3"
                                placeholder="Descreva melhor onde a barreira está localizada..."
                                :value="old('location_specific_details')"
                            />
                        </div>

                        <div class="col-md-12">
                            <x-forms.textarea
                                name="description"
                                label="Descrição Detalhada"
                                required
                                rows="3"
                                placeholder="Explique o problema encontrado..."
                                :value="old('description')"
                            />
                        </div>
                    </div>
                </div>

                <div class="px-4 mt-4">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Deficiências Relacionadas <i class="text-danger">*</i></label>

                        <div class="d-flex flex-wrap gap-4 p-3 border checkbox-group-wrapper max-h-40 overflow-y-auto custom-scrollbar">
                            @foreach($deficiencies as $def)
                                <x-forms.checkbox
                                    name="deficiencies[]"
                                    id="def_{{ $def->id }}"
                                    :value="$def->id"
                                    :label="$def->name"
                                    :checked="in_array($def->id, old('deficiencies', []))"
                                    class="mb-0"
                                />
                            @endforeach
                        </div>
                    </div>

                    <div class="checkbox-group-wrapper p-3 rounded mb-4 border shadow-sm">
                        <label class="fw-bold small text-uppercase mb-3 d-block">
                            Pessoa Impactada <i class="text-danger">*</i>
                        </label>

                        <div class="d-flex flex-column gap-2">
                            <x-forms.checkbox
                                name="is_anonymous"
                                id="is_anonymous"
                                label="Relato Anônimo"
                                :checked="old('is_anonymous')"
                            />

                            <div id="wrapper_not_applicable">
                                <x-forms.checkbox
                                    name="not_applicable"
                                    id="not_applicable"
                                    label="Relato Geral"
                                    :checked="old('not_applicable')"
                                />
                            </div>
                        </div>

                        <div id="identification_fields" class="mt-3">
                            <div id="person_selects" class="{{ old('not_applicable') ? 'd-none' : '' }}">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <x-forms.select
                                            name="affected_student_id"
                                            label="Estudante"
                                            :options="$students"
                                            :selected="old('affected_student_id')"
                                        />
                                    </div>

                                    <div class="col-md-6">
                                        <x-forms.select
                                            name="affected_professional_id"
                                            label="Profissional"
                                            :options="$professionals"
                                            :selected="old('affected_professional_id')"
                                        />
                                    </div>
                                </div>
                            </div>

                            <div id="manual_person_data" class="{{ old('not_applicable') ? '' : 'd-none' }} mt-2">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <x-forms.input
                                            name="affected_person_name"
                                            label="Nome"
                                            :value="old('affected_person_name')"
                                        />
                                    </div>

                                    <div class="col-md-6">
                                        <x-forms.input
                                            name="affected_person_role"
                                            label="Cargo"
                                            :value="old('affected_person_role')"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="is_active" value="1">
            </div>

            <div class="col-lg-7 px-0">
                <x-forms.section
                    title="Localização no Mapa"
                    description="Selecione a instituição e marque o ponto exato da barreira."
                    id="map-section-title"
                />

                <div class="sticky-top" style="top: 20px; z-index: 1;">
                    <div class="mb-4">
                        <div class="mb-3 px-4 d-flex justify-content-between align-items-center">
                            <div class="toggle-switch">
                                <input
                                    class="toggle-input filter-switch"
                                    type="checkbox"
                                    id="btn-toggle-locations"
                                    checked
                                >

                                <label
                                    class="toggle-label toggle-label--secondary"
                                    for="btn-toggle-locations"
                                >
                                    Exibir Locais (Cinza)
                                </label>
                            </div>
                        </div>

                        <div class="map-container" id="mapWrapper">
                            <x-forms.maps.barrier
                                :institution="$selectedInstitution"
                                height="450px"
                                label="Localização da Barreira"
                            />

                            <div id="map-blocked-overlay" class="map-overlay">
                                <div class="map-overlay-message">
                                    <i class="fa fa-lock mb-2 d-block"></i>
                                    <span id="blocked-message" class="fw-bold">
                                        Selecione uma instituição para liberar o mapa.
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <x-forms.section
                            title="Vistoria Inicial"
                            description="Registro técnico inicial da barreira."
                        />

                        <div class="px-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <x-forms.select
                                        name="status"
                                        id="status_select"
                                        label="Status Inicial"
                                        required
                                        :options="$barrierStatuses"
                                        :selected="old('status', $defaultStatus)"
                                    />
                                </div>

                                <div class="col-md-6">
                                    <x-forms.input
                                        name="inspection_date"
                                        label="Data da Vistoria"
                                        type="date"
                                        required
                                        :value="old('inspection_date', date('Y-m-d'))"
                                    />
                                </div>

                                <div class="col-md-6">
                                    <x-forms.image-uploader
                                        name="images[]"
                                        label="Fotos de Evidência"
                                        :existingImages="old('images', [])"
                                    />
                                </div>

                                <div class="col-md-12">
                                    <x-forms.textarea
                                        name="inspection_description"
                                        id="inspection_description"
                                        label="Parecer Técnico / Notas da Vistoria"
                                        rows="3"
                                        placeholder="Descreva detalhes técnicos sobre a obstrução ou estado do local..."
                                        :value="old('inspection_description')"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 d-flex justify-content-end gap-3 border-top pt-4 px-4 pb-4 mt-4">
                <x-buttons.link-button
                    :href="route('barreiras.index')"
                    variant="secondary"
                >
                    <x-slot:icon><i class="fa fa-times"></i></x-slot:icon>
                    Cancelar
                </x-buttons.link-button>

                <x-buttons.submit-button variant="new">
                    <x-slot:icon><i class="fa fa-save"></i></x-slot:icon>
                    Cadastrar
                </x-buttons.submit-button>
            </div>
        </div>
    </x-forms.form-card>

    @push('scripts')
        <script>
            window.categoriesData = @json($categories->mapWithKeys(fn($cat) => [$cat->id => $cat->blocks_map]));
            window.institutionsData = @json($institutions);
            window.oldLocationId = "{{ old('location_id') }}";
        </script>
    @endpush
@endsection
