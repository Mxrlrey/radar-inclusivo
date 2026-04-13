@extends('layouts.master')

@section('title', $deficiency->name)

@section('content')

    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Deficiências' => route('deficiencies.index'),
                $deficiency->name => null
            ]" />

            <h1>Detalhes da Deficiência</h1>

            <p class="text-muted mb-0">
                Visualize informações cadastrais da deficiência no sistema.
            </p>
        </div>

        <div class="page-header-actions">
            <x-buttons.link-button
                :href="route('deficiencies.edit', $deficiency)"
                variant="info"
            >
                <span class="btn-label">
                    <i class="fa fa-pencil"></i>
                </span>
                Editar
            </x-buttons.link-button>

            <x-buttons.link-button
                :href="route('deficiencies.index')"
                variant="secondary"
            >
                <span class="btn-label">
                    <i class="fa fa-arrow-left"></i>
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

        @php
            $modalId = "modal-delete-deficiency-{$deficiency->id}";
        @endphp

        <x-show.footer>
            <x-buttons.link-button
                :href="route('deficiencies.index')"
                variant="secondary"
            >
                <span class="btn-label">
                    <i class="fa fa-arrow-left"></i>
                </span>
                Voltar
            </x-buttons.link-button>

            <x-buttons.submit-button
                variant="danger"
                type="button"
                onclick="new bootstrap.Modal(document.getElementById('{{ $modalId }}')).show();"
            >
                <span class="btn-label">
                    <i class="fa fa-eraser"></i>
                </span>
                Excluir
            </x-buttons.submit-button>
        </x-show.footer>
    </div>

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
                href="javascript:void(0)"
                variant="secondary"
                onclick="bootstrap.Modal.getInstance(this.closest('.modal')).hide()"
            >
                Cancelar
            </x-buttons.link-button>

            <form action="{{ route('deficiencies.destroy', $deficiency) }}" method="POST">
                @csrf
                @method('DELETE')

                <x-buttons.submit-button variant="danger">
                    Excluir
                </x-buttons.submit-button>
            </form>
        </x-slot:footer>
    </x-modal.modal>
@endsection
