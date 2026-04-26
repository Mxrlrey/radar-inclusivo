@extends('layouts.master')

@section('title', $institution->name)

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Instituições' => route('instituicoes.index'),
                $institution->name => null
            ]" />

            <h1>Detalhes da Instituição</h1>
            <p class="text-muted mb-0">
                Visualize as informações cadastrais e a localização da instituição no mapa.
            </p>
        </div>

        <div class="page-header-actions">
            <x-buttons.link-button
                :href="route('instituicoes.editar', $institution)"
                variant="info"
            >
                <x-slot:icon><i class="fa fa-pencil"></i></x-slot:icon>
                Editar
            </x-buttons.link-button>

            <x-buttons.link-button
                :href="route('instituicoes.index')"
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
                    title="Informações Gerais"
                    description="Dados principais da instituição."
                />

                <x-show.info-item label="Nome da Instituição">
                    {{ $institution->name }}
                </x-show.info-item>

                <x-show.info-item label="Sigla / Nome Curto">
                    {{ $institution->short_name ?: 'Não informada' }}
                </x-show.info-item>

                <x-show.info-item label="Cidade">
                    {{ $institution->city }}
                </x-show.info-item>

                <x-show.info-item label="Estado">
                    {{ $institution->state }}
                </x-show.info-item>

                <x-show.info-item label="Bairro / Distrito">
                    {{ $institution->district ?: 'Não informado' }}
                </x-show.info-item>

                <x-show.info-item label="Rua / Logradouro">
                    {{ $institution->address ?: 'Não informado' }}
                </x-show.info-item>

                <x-forms.separator />

                <x-forms.section
                    title="Configurações do Mapa"
                    description="Parâmetros usados na exibição da sede."
                />

                <x-show.info-item label="Zoom Padrão">
                    {{ $institution->default_zoom }}
                </x-show.info-item>

                <x-show.info-item label="Latitude Sede">
                    {{ $institution->latitude ?: 'Não informada' }}
                </x-show.info-item>

                <x-show.info-item label="Longitude Sede">
                    {{ $institution->longitude ?: 'Não informada' }}
                </x-show.info-item>

                @can('system.audit.view')
                    <x-forms.separator />

                    <x-forms.section title="Informações do Registro" />

                    <x-show.info-item label="ID">
                        #{{ $institution->id }}
                    </x-show.info-item>

                    <x-show.info-item label="Status no Sistema">
                    <span class="badge bg-{{ $institution->is_active ? 'success' : 'danger' }}">
                        {{ $institution->is_active ? 'Ativa' : 'Inativa' }}
                    </span>
                    </x-show.info-item>

                    <x-show.info-item label="Cadastrado em">
                        {{ $institution->created_at?->format('d/m/Y H:i') ?? '---' }}
                    </x-show.info-item>

                    <x-show.info-item label="Atualizado em">
                        {{ $institution->updated_at?->format('d/m/Y H:i') ?? '---' }}
                    </x-show.info-item>
                @endcan
            </div>

            <div class="col-lg-7 px-0">
                <x-forms.section
                    title="Localização"
                    description="Visualização da sede no mapa."
                    id="map-section-title"
                />

                <div style="position: relative;">
                    <x-show.maps.institution
                        :institution="$institution"
                        :lat="$institution->latitude"
                        :lng="$institution->longitude"
                        :zoom="$institution->default_zoom"
                        height="450px"
                        label="Localização da Instituição"
                    />
                </div>
            </div>
            <x-show.footer>
                <x-buttons.link-button
                    :href="route('instituicoes.index')"
                    variant="secondary"
                >
                    <x-slot:icon><i class="fa fa-arrow-left"></i></x-slot:icon>
                    Voltar
                </x-buttons.link-button>

                <x-buttons.submit-button
                    variant="danger"
                    type="button"
                    label="Excluir instituição"
                    onclick="new bootstrap.Modal(document.getElementById('modal-delete-institution-{{ $institution->id }}')).show();"
                >
                    <x-slot:icon><i class="fa fa-eraser"></i></x-slot:icon>
                    Excluir
                </x-buttons.submit-button>
            </x-show.footer>
        </div>
    </div>

    @php
        $modalId = "modal-delete-institution-" . $institution->id;
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
                Deseja realmente excluir a instituição
                <strong>{{ $institution->name }}</strong>?
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

            <form action="{{ route('instituicoes.excluir', $institution) }}" method="POST">
                @csrf
                @method('DELETE')

                <x-buttons.submit-button variant="danger" label="Confirmar exclusão da instituição">
                    Excluir
                </x-buttons.submit-button>
            </form>
        </x-slot:footer>
    </x-modal.modal>
@endsection
