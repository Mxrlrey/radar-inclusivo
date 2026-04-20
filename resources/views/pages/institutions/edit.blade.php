@extends('layouts.master')

@section('title', "Editar Instituição")

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Instituições' => route('instituicoes.index'),
                $institution->name => route('instituicoes.visualizar', $institution),
                'Editar' => null
            ]" />

            <h1>Editar Instituição</h1>

            <p class="text-muted mb-0">
                Atualize as informações e a localização da instituição no sistema.
            </p>
        </div>

        <div class="page-header-actions">
            <x-buttons.link-button
                :href="route('instituicoes.visualizar', $institution)"
                variant="secondary"
            >
                <x-slot:icon><i class="fa fa-times"></i></x-slot:icon>
                Cancelar
            </x-buttons.link-button>
        </div>
    </div>

    <x-forms.form-card
        action="{{ route('instituicoes.atualizar', $institution) }}"
        method="POST"
        class="form-horizontal"
    >
        @csrf
        @method('PUT')

        <div class="row g-0">
            <div class="col-lg-5 border-end">
                <x-forms.section
                    title="Informações Gerais"
                    description="Atualize os dados principais da instituição."
                />

                <div class="px-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <x-forms.input
                                name="name"
                                label="Nome da Instituição"
                                required
                                :value="old('name', $institution->name)"
                            />
                        </div>

                        <div class="col-12">
                            <x-forms.input
                                name="short_name"
                                label="Sigla / Nome Curto"
                                :value="old('short_name', $institution->short_name)"
                            />
                        </div>

                        <div class="col-md-8">
                            <x-forms.input
                                id="city_search"
                                name="city"
                                label="Cidade"
                                required
                                :value="old('city', $institution->city)"
                            />
                        </div>

                        <div class="col-md-4">
                            <x-forms.input
                                id="state_search"
                                name="state"
                                label="Estado"
                                required
                                :value="old('state', $institution->state)"
                            />
                        </div>

                        <div class="col-md-5">
                            <x-forms.input
                                id="district_search"
                                name="district"
                                label="Bairro / Distrito"
                                :value="old('district', $institution->district)"
                            />
                        </div>

                        <div class="col-md-7">
                            <x-forms.input
                                id="address_search"
                                name="address"
                                label="Rua / Logradouro"
                                :value="old('address', $institution->address)"
                            />
                        </div>
                    </div>
                </div>

                <x-forms.switch
                    name="is_active"
                    label="Instituição Ativa"
                    description="Define se esta instituição estará disponível para uso no sistema."
                    :horizontal="false"
                    :checked="old('is_active', $institution->is_active)"
                />
            </div>

            <div class="col-lg-7 px-0">
                <x-forms.section
                    title="Ajuste a Sede no Mapa"
                    description="Reposicione a instituição diretamente no mapa."
                    id="map-section-title"
                />

                <div style="position: relative;">
                    <x-forms.maps.institution
                        :institution="$institution"
                        :lat="old('latitude', $institution->latitude)"
                        :lng="old('longitude', $institution->longitude)"
                        :zoom="old('default_zoom', $institution->default_zoom)"
                        height="450px"
                    />
                </div>

                <div class="px-4 pb-4 mt-3">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="default_zoom" class="form-label fw-bold mb-1">
                                Zoom Padrão
                            </label>

                            <small class="text-muted d-block mb-2">
                                Define o nível de aproximação inicial no mapa.
                            </small>

                            <div class="d-flex align-items-center gap-3">
                                <input
                                    type="range"
                                    name="default_zoom"
                                    id="default_zoom"
                                    min="1"
                                    max="20"
                                    value="{{ old('default_zoom', $institution->default_zoom) }}"
                                    class="form-range custom-range"
                                    oninput="document.getElementById('zoom_val').innerText = this.value"
                                >

                                <span id="zoom_val" class="badge bg-purple-dark p-2" style="min-width:40px;">
                                    {{ old('default_zoom', $institution->default_zoom) }}
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
                                value="{{ old('latitude', $institution->latitude) }}"
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
                                value="{{ old('longitude', $institution->longitude) }}"
                            >
                        </div>
                    </div>
                </div>
            </div>

            <x-forms.form-footer>
                <x-buttons.link-button
                    :href="route('instituicoes.visualizar', $institution)"
                    variant="secondary"
                >
                    <x-slot:icon><i class="fa fa-times"></i></x-slot:icon>
                    Cancelar
                </x-buttons.link-button>

                <x-buttons.submit-button variant="new">
                    <x-slot:icon><i class="fa fa-save"></i></x-slot:icon>
                    Salvar
                </x-buttons.submit-button>
            </x-forms.form-footer>
        </div>
    </x-forms.form-card>
@endsection
