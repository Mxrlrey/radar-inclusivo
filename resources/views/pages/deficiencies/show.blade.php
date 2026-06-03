@extends('layouts.master')

@section('title', $deficiency->name)

@section('content')

    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Deficiências' => route('deficiencias.index'),
                $deficiency->name => null
            ]" />

            <h1>Detalhes da Deficiência</h1>

            <p class="text-muted mb-0">
                Visualize informações cadastrais da deficiência no sistema.
            </p>
        </div>

        <div class="page-header-actions">
            @can('deficiency.update')
                <x-buttons.link-button
                    :href="route('deficiencias.editar', $deficiency)"
                    variant="info"
                >
                    <span class="btn-label">
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                    </span>
                    Editar
                </x-buttons.link-button>
            @endcan

            <x-buttons.link-button
                :href="route('deficiencias.index')"
                variant="secondary"
            >
                <span class="btn-label">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                </span>
                Voltar
            </x-buttons.link-button>
        </div>
    </div>

    <div class="card-custom overflow-hidden show-container">

        <x-forms.section
            title="Identificação da Deficiência"
            description="Dados principais cadastrados no sistema"
        />

        <x-show.info-item label="Nome">
            {{ $deficiency->name }}
        </x-show.info-item>

        <x-show.info-item label="Código CID">
            {{ $deficiency->cid_code ?? '---' }}
        </x-show.info-item>

        <x-show.info-item label="Descrição">
            {!! $deficiency->description ?: 'Nenhuma descrição cadastrada.' !!}
        </x-show.info-item>

        <x-show.info-item label="Status no Sistema">
            <span class="badge bg-{{ $deficiency->is_active ? 'success' : 'danger' }}">
                {{ $deficiency->is_active ? 'Ativa' : 'Inativa' }}
            </span>
        </x-show.info-item>

        <x-forms.separator />

        <x-forms.section title="Informações do Registro" />

        <x-show.info-item label="ID">
            #{{ $deficiency->id }}
        </x-show.info-item>

        <x-show.info-item label="Status no Sistema">
            <span class="badge bg-{{ $deficiency->is_active ? 'success' : 'danger' }}">
                {{ $deficiency->is_active ? 'Ativa' : 'Inativa' }}
            </span>
        </x-show.info-item>

        <x-show.info-item label="Cadastrado em">
            {{ $deficiency->created_at?->format('d/m/Y H:i') ?? '---' }}
        </x-show.info-item>

        <x-show.info-item label="Atualizado em">
            {{ $deficiency->updated_at?->format('d/m/Y H:i') ?? '---' }}
        </x-show.info-item>

        @php
            $modalId = "modal-delete-deficiency-{$deficiency->id}";
        @endphp

        <x-show.footer>
            <x-buttons.link-button
                :href="route('deficiencias.index')"
                variant="secondary"
            >
                <span class="btn-label">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                </span>
                Voltar
            </x-buttons.link-button>

            @can('deficiency.delete')
                <x-buttons.submit-button
                    variant="danger"
                    type="button"
                    label="Excluir deficiência"
                    onclick="new bootstrap.Modal(document.getElementById('{{ $modalId }}')).show();"
                >
                    <span class="btn-label">
                        <i class="fa fa-eraser" aria-hidden="true"></i>
                    </span>
                    Excluir
                </x-buttons.submit-button>
            @endcan
        </x-show.footer>
    </div>

    @can('deficiency.delete')
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
                    Deseja realmente excluir a deficiência
                    <strong>{{ $deficiency->name }}</strong>?
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

                <form action="{{ route('deficiencias.excluir', $deficiency) }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <x-buttons.submit-button variant="danger" label="Confirmar exclusão da deficiência">
                        Excluir
                    </x-buttons.submit-button>
                </form>
            </x-slot:footer>
        </x-modal.modal>
    @endcan
@endsection
