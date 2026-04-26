@extends('layouts.master')

@section('title', $location->name)

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Pontos de Referência' => route('localizacoes.index'),
                $location->name => null
            ]" />

            <h1>Detalhes do Ponto de Referência</h1>
            <p class="text-muted mb-0">
                Visualize as informações cadastradas e a posição no mapa do ponto de referência.
            </p>
        </div>

        <div class="page-header-actions">
            <x-buttons.link-button
                :href="route('localizacoes.editar', $location)"
                variant="info"
            >
                <x-slot:icon><i class="fa fa-pencil"></i></x-slot:icon>
                Editar
            </x-buttons.link-button>

            <x-buttons.link-button
                :href="route('localizacoes.index')"
                variant="secondary"
            >
                <x-slot:icon><i class="fa fa-arrow-left"></i></x-slot:icon>
                Voltar
            </x-buttons.link-button>
        </div>
    </div>

    <div class="card-custom overflow-hidden show-container">
        <div class="row g-0">
            <div class="col-lg-5 border-end">
                <x-forms.section
                    title="Vínculo e Identificação"
                    description="Dados principais do ponto de referência."
                />

                <x-show.info-item label="Instituição Base">
                    {{ $location->institution?->name ?? 'Não informada' }}
                </x-show.info-item>

                <x-show.info-item label="Nome do Local">
                    {{ $location->name }}
                </x-show.info-item>

                <x-show.info-item label="Tipo de Local">
                    {{ $location->type ?: 'Não informado' }}
                </x-show.info-item>

                <x-show.info-item label="Descrição / Observações">
                    {!! $location->description  ?: 'Não informada'!!}
                </x-show.info-item>

                <x-forms.separator />

                <x-forms.section
                    title="Coordenadas"
                    description="Posição geográfica cadastrada para este local."
                />

                <x-show.info-item label="Latitude">
                    {{ $location->latitude ?: 'Não informada' }}
                </x-show.info-item>

                <x-show.info-item label="Longitude">
                    {{ $location->longitude ?: 'Não informada' }}
                </x-show.info-item>

                @can('system.audit.view')
                    <x-forms.separator />

                    <x-forms.section title="Informações do Registro" />

                    <x-show.info-item label="ID">
                        #{{ $location->id }}
                    </x-show.info-item>

                    <x-show.info-item label="Status no Sistema">
                        <span class="badge bg-{{ $location->is_active ? 'success' : 'danger' }}">
                            {{ $location->is_active ? 'Ativo' : 'Inativo' }}
                        </span>
                    </x-show.info-item>

                    <x-show.info-item label="Cadastrado em">
                        {{ $location->created_at?->format('d/m/Y H:i') ?? '---' }}
                    </x-show.info-item>

                    <x-show.info-item label="Atualizado em">
                        {{ $location->updated_at?->format('d/m/Y H:i') ?? '---' }}
                    </x-show.info-item>
                @endcan
            </div>

            <div class="col-lg-7 px-0">
                <x-forms.section
                    title="Localização"
                    description="Visualização do ponto de referência no mapa."
                    id="map-section-title"
                />

                <div style="position: relative;">
                    <x-show.maps.location
                        :location="$location"
                        :institution="$location->institution"
                        height="450px"
                        label="Localização do Ponto"
                    />
                </div>
            </div>

            <x-show.footer>
                <x-buttons.link-button
                    :href="route('localizacoes.index')"
                    variant="secondary"
                >
                    <x-slot:icon><i class="fa fa-arrow-left"></i></x-slot:icon>
                    Voltar
                </x-buttons.link-button>

                <x-buttons.submit-button
                    variant="danger"
                    type="button"
                    label="Excluir ponto de referência"
                    onclick="new bootstrap.Modal(document.getElementById('modal-delete-location-{{ $location->id }}')).show();"
                >
                    <x-slot:icon><i class="fa fa-eraser"></i></x-slot:icon>
                    Excluir
                </x-buttons.submit-button>
            </x-show.footer>
        </div>
    </div>

    @php
        $modalId = "modal-delete-location-" . $location->id;
    @endphp

    <x-modal.modal
        :id="$modalId"
        title="Confirmar Exclusão"
        size="sm"
    >
        <div class="p-3">
            <p class="mb-2 text-danger fw-bold">
                Esta ação não pode ser desfeita.
            </p>

            <p class="mb-0 text-muted">
                Deseja realmente excluir o ponto de referência
                <strong>{{ $location->name }}</strong>?
            </p>
        </div>

        <x-slot:footer>
            <x-buttons.link-button
                variant="secondary"
                type="button"
                onclick="bootstrap.Modal.getInstance(this.closest('.modal')).hide()"
            >
                Cancelar
            </x-buttons.link-button>

            <form action="{{ route('localizacoes.excluir', $location) }}" method="POST">
                @csrf
                @method('DELETE')

                <x-buttons.submit-button variant="danger" label="Confirmar exclusão do ponto de referência">
                    Excluir
                </x-buttons.submit-button>
            </form>
        </x-slot:footer>
    </x-modal.modal>
@endsection
