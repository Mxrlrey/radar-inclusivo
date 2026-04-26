@extends('layouts.master')

@section('title', "Editar - $barrier->name")

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Barreiras' => route('barreiras.index'),
                $barrier->name => route('barreiras.visualizar', $barrier),
                'Editar' => null
            ]" />

            <h1>Editar Barreira</h1>
            <p class="text-muted mb-0">
                Atualize os dados do relato, da localização e da vistoria.
            </p>
        </div>

        <div class="page-header-actions">
            <x-buttons.link-button
                :href="route('barreiras.visualizar', $barrier)"
                variant="secondary"
            >
                <x-slot:icon><i class="fa fa-times" aria-hidden="true"></i></x-slot:icon>
                Cancelar
            </x-buttons.link-button>
        </div>
    </div>

    <x-forms.form-card
        action="{{ route('barreiras.atualizar', $barrier) }}"
        method="POST"
        enctype="multipart/form-data"
        class="form-horizontal"
    >
        @method('PUT')

        <div class="row g-0">
            <div class="col-lg-5 border-end">
                <x-forms.section
                    title="Detalhes da Ocorrência"
                    description="Atualize os dados principais da barreira identificada."
                />

                <div class="px-4">
                    <div class="row g-3">
                        <div class="col-md-7">
                            <x-forms.input
                                name="name"
                                label="Título do Relato"
                                required
                                :value="old('name', $barrier->name)"
                                placeholder="Ex: Calçada irregular"
                            />
                        </div>

                        <div class="col-md-5">
                            <x-forms.input
                                type="date"
                                name="identified_at"
                                label="Data"
                                required
                                :value="old('identified_at', $barrier->identified_at?->format('Y-m-d'))"
                            />
                        </div>

                        <div class="col-md-6">
                            <x-forms.select
                                name="priority"
                                label="Prioridade"
                                :options="$priorities"
                                :selected="old('priority', $barrier->priority?->value)"
                            />
                        </div>

                        <div class="col-md-6">
                            <x-forms.select
                                name="barrier_category_id"
                                id="barrier_category_id"
                                label="Categoria"
                                required
                                :options="$categories->pluck('name', 'id')"
                                :selected="old('barrier_category_id', $barrier->barrier_category_id)"
                                extraAttributes="data-blocks-map-options"
                            />
                        </div>

                        <div class="col-md-6">
                            <x-forms.select
                                name="institution_id"
                                id="institution_select"
                                label="Campus / Unidade"
                                required
                                :options="$institutions->pluck('name','id')"
                                :selected="old('institution_id', $barrier->institution_id)"
                                :resourceObjects="$institutions"
                            />
                        </div>

                        <div class="col-md-6">
                            <x-forms.select
                                name="location_id"
                                id="location_select"
                                label="Local / Ponto de Referência"
                                :options="[]"
                                :selected="old('location_id', $barrier->location_id)"
                            />
                        </div>

                        <div
                            id="location_wrapper"
                            class="{{ old('institution_id', $barrier->institution_id) ? '' : 'd-none' }} col-md-12 mt-3"
                            @if(!old('institution_id', $barrier->institution_id)) hidden aria-hidden="true" @else aria-hidden="false" @endif
                        >
                            <x-forms.textarea
                                name="location_specific_details"
                                label="Complemento"
                                rows="3"
                                placeholder="Descreva melhor onde a barreira está localizada..."
                                :value="old('location_specific_details', $barrier->location_specific_details)"
                            />
                        </div>

                        <div class="col-md-12">
                            <x-forms.textarea
                                name="description"
                                label="Descrição Detalhada"
                                required
                                rows="3"
                                placeholder="Explique o problema encontrado..."
                                :value="old('description', $barrier->description)"
                            />
                        </div>
                    </div>
                </div>

                <div class="px-4 mt-4">
                    <fieldset class="mb-4">
                        <legend class="form-label fw-bold mb-2">Deficiências Relacionadas <i class="text-danger" aria-hidden="true">*</i></legend>
                        <div
                            class="d-flex flex-wrap gap-4 p-3 border checkbox-group-wrapper max-h-40 overflow-y-auto custom-scrollbar @error('deficiencies') border-danger @enderror"
                            @error('deficiencies') aria-describedby="barrier-deficiencies-error" @enderror
                        >
                            @foreach($deficiencies as $def)
                                <x-forms.checkbox
                                    name="deficiencies[]"
                                    id="def_{{ $def->id }}"
                                    :value="$def->id"
                                    :label="$def->name"
                                    :checked="in_array($def->id, old('deficiencies', $barrier->deficiencies->pluck('id')->toArray()))"
                                    class="mb-0"
                                />
                            @endforeach
                        </div>
                        @error('deficiencies')
                        <small class="text-danger d-block mt-1" id="barrier-deficiencies-error" role="alert">{{ $message }}</small>
                        @enderror
                    </fieldset>
                    <fieldset class="checkbox-group-wrapper p-3 rounded mb-4 border shadow-sm">
                        <legend class="fw-bold small text-uppercase mb-3 d-block">
                            Pessoa Impactada <i class="text-danger" aria-hidden="true">*</i>
                        </legend>

                        <div class="d-flex flex-column gap-2">
                            <x-forms.checkbox
                                name="is_anonymous"
                                id="is_anonymous"
                                label="Relato Anônimo"
                                :checked="old('is_anonymous', $barrier->is_anonymous)"
                            />

                            <div id="wrapper_not_applicable" aria-hidden="false">
                                <x-forms.checkbox
                                    name="not_applicable"
                                    id="not_applicable"
                                    label="Relato Geral"
                                    :checked="old('not_applicable', $barrier->not_applicable)"
                                />
                            </div>
                        </div>

                        <div id="identification_fields" class="mt-3" @if(old('is_anonymous', $barrier->is_anonymous)) hidden aria-hidden="true" @else aria-hidden="false" @endif>
                            <div id="person_selects" class="{{ old('not_applicable', $barrier->not_applicable) ? 'd-none' : '' }}" @if(old('not_applicable', $barrier->not_applicable)) hidden aria-hidden="true" @else aria-hidden="false" @endif>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <x-forms.select
                                            name="affected_student_id"
                                            label="Estudante"
                                            :options="$students"
                                            :selected="old('affected_student_id', $barrier->affected_student_id)"
                                        />
                                    </div>

                                    <div class="col-md-6">
                                        <x-forms.select
                                            name="affected_professional_id"
                                            label="Profissional"
                                            :options="$professionals"
                                            :selected="old('affected_professional_id', $barrier->affected_professional_id)"
                                        />
                                    </div>
                                </div>
                            </div>

                            <div id="manual_person_data" class="{{ old('not_applicable', $barrier->not_applicable) ? '' : 'd-none' }} mt-2" @if(!old('not_applicable', $barrier->not_applicable)) hidden aria-hidden="true" @else aria-hidden="false" @endif>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <x-forms.input
                                            name="affected_person_name"
                                            label="Nome"
                                            :value="old('affected_person_name', $barrier->affected_person_name)"
                                        />
                                    </div>

                                    <div class="col-md-6">
                                        <x-forms.input
                                            name="affected_person_role"
                                            label="Cargo"
                                            :value="old('affected_person_role', $barrier->affected_person_role)"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>

            <div class="col-lg-7 px-0">
                <x-forms.section
                    title="Localização no Mapa"
                    description="Atualize a posição da barreira e revise as localizações próximas."
                    id="map-section-title"
                />

                <div class="sticky-top" style="top:20px; z-index:1;">
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
                                :barrier="$barrier"
                                :institution="$selectedInstitution"
                                height="450px"
                                label="Localização da Barreira"
                            />

                            <div id="map-blocked-overlay" class="map-overlay" role="status" aria-live="polite">
                                <div class="map-overlay-message">
                                    <i class="fa fa-lock mb-2 d-block" aria-hidden="true"></i>
                                    <span id="blocked-message" class="fw-bold">
                                        Selecione uma instituição para liberar o mapa.
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="px-4 mt-3">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="lat_manual" class="form-label fw-bold text-primary">
                                        Latitude
                                    </label>
                                    <input
                                        type="number"
                                        id="lat_manual"
                                        step="any"
                                        inputmode="decimal"
                                        class="form-control custom-input"
                                        value="{{ old('latitude', $barrier->latitude ?? $selectedInstitution?->latitude ?? -14.2350) }}"
                                        aria-describedby="barrier-map-coordinates-help"
                                    >
                                </div>

                                <div class="col-md-6">
                                    <label for="lng_manual" class="form-label fw-bold text-primary">
                                        Longitude
                                    </label>
                                    <input
                                        type="number"
                                        id="lng_manual"
                                        step="any"
                                        inputmode="decimal"
                                        class="form-control custom-input"
                                        value="{{ old('longitude', $barrier->longitude ?? $selectedInstitution?->longitude ?? -51.9253) }}"
                                        aria-describedby="barrier-map-coordinates-help"
                                    >
                                </div>
                            </div>

                            <div id="barrier-map-coordinates-help" class="form-text mt-2">
                                Você pode informar latitude e longitude manualmente se não quiser marcar o ponto diretamente no mapa.
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <x-forms.section
                            title="Vistoria Periódica"
                            description="Atualize o status e registre uma nova vistoria."
                        />

                        <div class="px-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <x-forms.select
                                        name="status"
                                        id="status_select"
                                        label="Status Atual"
                                        :options="$barrierStatuses"
                                        :selected="old('status', $barrier->status?->value ?? 'identified')"
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
                                        label="Parecer Técnico / Notas"
                                        rows="3"
                                        placeholder="Descreva o estado atual do local..."
                                        :value="old('inspection_description')"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <x-forms.form-footer>
                <x-buttons.link-button
                    :href="route('barreiras.visualizar', $barrier)"
                    variant="secondary"
                >
                    <x-slot:icon><i class="fa fa-times" aria-hidden="true"></i></x-slot:icon>
                    Cancelar
                </x-buttons.link-button>

                <x-buttons.submit-button variant="new">
                    <x-slot:icon><i class="fa fa-save" aria-hidden="true"></i></x-slot:icon>
                    Salvar
                </x-buttons.submit-button>
            </x-forms.form-footer>
        </div>
    </x-forms.form-card>

    @push('scripts')
        <script>
            window.categoriesData = @json($categories->mapWithKeys(fn($cat) => [$cat->id => $cat->blocks_map]));
            window.institutionsData = @json($institutions);
            window.oldLocationId = "{{ old('location_id', $barrier->location_id) }}";
            window.barrierData = @json($barrier);
        </script>
    @endpush
@endsection
