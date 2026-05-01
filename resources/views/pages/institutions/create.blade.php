@extends('layouts.master')

@section('title', 'Cadastrar Instituição')

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Instituições' => route('instituicoes.index'),
                'Cadastrar' => null
            ]" />

            <h1>Cadastrar Instituição</h1>

            <p class="text-muted mb-0">
                Defina a sede e as informações básicas da instituição para uso no mapeamento de acessibilidade.
            </p>
        </div>

        <div class="page-header-actions">
            <x-buttons.link-button
                :href="route('instituicoes.index')"
                variant="secondary"
            >
                <x-slot:icon><i class="fa fa-times" aria-hidden="true"></i></x-slot:icon>
                Cancelar
            </x-buttons.link-button>
        </div>
    </div>

    <x-forms.form-card
        action="{{ route('instituicoes.salvar') }}"
        method="POST"
        class="form-horizontal"
    >
        <div class="row g-0">
            <div class="col-lg-5 border-end">
                <x-forms.section
                    title="Informações Gerais"
                    description="Informe os dados principais da instituição."
                />

                <div class="px-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <x-forms.input
                                name="name"
                                label="Nome da Instituição"
                                required aria-required="true"
                                :value="old('name')"
                            />
                        </div>

                        <div class="col-12">
                            <x-forms.input
                                name="short_name"
                                label="Sigla / Nome Curto"
                                :value="old('short_name')"
                            />
                        </div>

                        <div class="col-md-8">
                            <x-forms.input
                                name="city_search"
                                label="Cidade"
                                required
                                :value="old('city')"
                            />
                        </div>

                        <div class="col-md-4">
                            <x-forms.input
                                name="state_search"
                                label="Estado"
                                required
                                :value="old('state')"
                            />
                        </div>

                        <div class="col-md-5">
                            <x-forms.input
                                name="district_search"
                                label="Bairro / Distrito"
                                :value="old('district')"
                            />
                        </div>

                        <div class="col-md-7">
                            <x-forms.input
                                name="address_search"
                                label="Rua / Logradouro"
                                :value="old('address')"
                            />
                        </div>
                    </div>
                </div>

                <x-forms.switch
                    name="is_active"
                    label="Instituição Ativa"
                    description="Define se esta instituição estará disponível para uso no sistema."
                    :horizontal="false"
                    :checked="old('is_active', true)"
                />
            </div>

            <div class="col-lg-7 px-0">
                <x-forms.section
                    title="Localize a Sede no Mapa"
                    description="Marque o ponto exato da instituição."
                    id="map-section-title"
                />

                <div style="position: relative;">
                    <x-forms.maps.institution
                        :lat="old('latitude', -14.2350)"
                        :lng="old('longitude', -51.9253)"
                        :zoom="old('default_zoom', 16)"
                        height="450px"
                        label="Localize a Sede no Mapa"
                    />
                </div>

                <div class="px-4 pb-4 mt-3">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="default_zoom" class="form-label fw-bold mb-1">
                                Zoom Padrão
                            </label>
                            <small id="zoom_help" class="text-muted d-block mb-2">
                                Define o nível de aproximação inicial no mapa.
                            </small>

                            <div class="d-flex align-items-center gap-3">
                                <input
                                    type="range"
                                    name="default_zoom"
                                    id="default_zoom"
                                    min="1"
                                    max="20"
                                    value="{{ old('default_zoom', 16) }}"
                                    class="form-range custom-range"
                                    aria-describedby="zoom_help"
                                    oninput="document.getElementById('zoom_val').innerText = this.value"
                                >
                                <span id="zoom_val" class="badge bg-purple-dark p-2" style="min-width:40px;">
                                    {{ old('default_zoom', 16) }}
                                </span>
                            </div>
                        </div>

                        <div class="col-6">
                            <label for="latitude" class="form-label fw-bold">
                                Latitude Sede
                            </label>
                            <small class="text-muted d-block mb-2">
                                Preenchida automaticamente pelo mapa ou manualmente.
                            </small>

                            <input
                                type="number"
                                step="any"
                                name="latitude"
                                id="latitude"
                                class="form-control"
                                value="{{ old('latitude', -14.2350) }}"
                            >
                        </div>

                        <div class="col-6">
                            <label for="longitude" class="form-label fw-bold">
                                Longitude Sede
                            </label>
                            <small class="text-muted d-block mb-2">
                                Preenchida automaticamente pelo mapa ou manualmente.
                            </small>

                            <input
                                type="number"
                                step="any"
                                name="longitude"
                                id="longitude"
                                class="form-control"
                                value="{{ old('longitude', -51.9253) }}"
                            >
                        </div>
                    </div>
                </div>
            </div>

            <x-forms.form-footer>
                <x-buttons.link-button
                    :href="route('instituicoes.index')"
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
