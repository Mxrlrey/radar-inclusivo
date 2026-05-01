@extends('layouts.master')

@section('title', 'Cadastrar Ponto de Referência')

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Pontos de Referência' => route('localizacoes.index'),
                'Cadastrar' => null
            ]" />

            <h1>Cadastrar Ponto de Referência</h1>

            <p class="text-muted mb-0">
                Vincule prédios, salas e áreas específicas a uma instituição base.
            </p>
        </div>

        <div class="page-header-actions">
            <x-buttons.link-button
                :href="route('localizacoes.index')"
                variant="secondary"
            >
                <x-slot:icon><i class="fa fa-times" aria-hidden="true"></i></x-slot:icon>
                Cancelar
            </x-buttons.link-button>
        </div>
    </div>

    <x-forms.form-card
        action="{{ route('localizacoes.salvar') }}"
        method="POST"
        class="form-horizontal"
    >
        <div class="row g-0">
            <div class="col-lg-5 border-end">
                <x-forms.section
                    title="Vínculo e Identificação"
                    description="Informe a instituição e os dados principais do ponto."
                />

                <div class="px-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <x-forms.select
                                name="institution_id"
                                id="institution_select"
                                label="Instituição Base"
                                required
                                :options="$institutions"
                                :selected="old('institution_id')"
                            />
                        </div>

                        <div class="col-12">
                            <x-forms.input
                                name="name"
                                id="location_name"
                                label="Nome do Local"
                                required
                                :value="old('name')"
                            />
                        </div>

                        <div class="col-12">
                            <x-forms.input
                                name="type"
                                id="location_type"
                                label="Tipo de Local"
                                :value="old('type')"
                            />
                        </div>

                        <div class="col-12">
                            <x-forms.textarea
                                name="description"
                                label="Descrição / Observações"
                                rows="3"
                                :value="old('description')"
                            />
                        </div>
                    </div>
                </div>

                <x-forms.switch
                    name="is_active"
                    label="Ponto Ativo no Sistema"
                    description="Define se este local aparecerá no mapa público."
                    :horizontal="false"
                    :checked="old('is_active', true)"
                />

                <div class="px-4 mt-4">

                </div>
            </div>

            <div class="col-lg-7 px-0">
                <x-forms.section
                    title="Localização no Mapa"
                    description="Selecione o ponto exato dentro da instituição."
                    id="map-section-title"
                />

                <div style="position: relative;">
                    <x-forms.maps.location
                        :institution="$selectedInstitution"
                        :institutionsData="$institutionsData"
                        :location="null"
                        height="450px"
                        label="Localização no Campus"
                    />
                </div>

                <div class="px-4 pb-4 mt-3">
                    <div class="row g-3">
                        <div class="col-6">
                            <label for="lat_manual" class="form-label fw-bold">
                                Latitude
                            </label>
                            <small class="text-muted d-block mb-2">
                                Preenchida automaticamente pelo mapa ou manualmente.
                            </small>

                            <input
                                type="number"
                                step="any"
                                id="lat_manual"
                                name="latitude"
                                class="form-control"
                                value="{{ old('latitude') }}"
                            >
                        </div>

                        <div class="col-6">
                            <label for="lng_manual" class="form-label fw-bold">
                                Longitude
                            </label>
                            <small class="text-muted d-block mb-2">
                                Preenchida automaticamente pelo mapa ou manualmente.
                            </small>

                            <input
                                type="number"
                                step="any"
                                id="lng_manual"
                                name="longitude"
                                class="form-control"
                                value="{{ old('longitude') }}"
                            >
                        </div>
                    </div>
                </div>
            </div>

            <x-forms.form-footer>
                <x-buttons.link-button
                    :href="route('localizacoes.index')"
                    variant="secondary"
                >
                    <x-slot:icon><i class="fa fa-times" aria-hidden="true"></i></x-slot:icon>
                    Cancelar
                </x-buttons.link-button>

                <x-buttons.submit-button variant="new">
                    <x-slot:icon><i class="fa fa-save" aria-hidden="true"></i></x-slot:icon>
                    Cadastrar
                </x-buttons.submit-button>
            </x-forms.form-footer>
        </div>
    </x-forms.form-card>
@endsection
