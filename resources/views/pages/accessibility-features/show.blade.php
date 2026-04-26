@extends('layouts.master')

@section('title', $feature->name)

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Recursos de Acessibilidade' => route('recursos-de-acessibilidade.index'),
                $feature->name => null
            ]" />

            <h1>Detalhes do Recurso de Acessibilidade</h1>
            <p class="text-muted mb-0">
                Visualize as informações cadastradas do recurso.
            </p>
        </div>

        <div class="page-header-actions">
            <x-buttons.link-button
                :href="route('recursos-de-acessibilidade.editar', $feature)"
                variant="info"
            >
                <x-slot:icon><i class="fa fa-pencil"></i></x-slot:icon>
                Editar
            </x-buttons.link-button>

            <x-buttons.link-button
                :href="route('recursos-de-acessibilidade.index')"
                variant="secondary"
            >
                <x-slot:icon><i class="fa fa-arrow-left"></i></x-slot:icon>
                Voltar
            </x-buttons.link-button>
        </div>
    </div>

    <div class="card-custom overflow-hidden show-container">

        <x-forms.section
            title="{{ $feature->name }}"
            description="Visualize as informações do recurso de acessibilidade."
        />

        <x-show.info-item label="Nome do Recurso">
            {{ $feature->name }}
        </x-show.info-item>

        <x-show.info-item label="Descrição">
            {{ $feature->description ? strip_tags($feature->description) : '---' }}
        </x-show.info-item>

        <x-forms.separator />

        <x-forms.section title="Informações do Registro" />

        <x-show.info-item label="ID">
            #{{ $feature->id }}
        </x-show.info-item>

        <x-show.info-item label="Status no Sistema">
            <span class="badge bg-{{ $feature->is_active ? 'success' : 'danger' }}">
                {{ $feature->is_active ? 'Ativo' : 'Inativo' }}
            </span>
        </x-show.info-item>

        <x-show.info-item label="Cadastrado em">
            {{ $feature->created_at?->format('d/m/Y H:i') ?? '---' }}
        </x-show.info-item>

        <x-show.info-item label="Atualizado em">
            {{ $feature->updated_at?->format('d/m/Y H:i') ?? '---' }}
        </x-show.info-item>

        @php
            $modalId = 'modal-delete-feature-' . $feature->id;
        @endphp

        <x-show.footer>
            <x-buttons.link-button
                :href="route('recursos-de-acessibilidade.index')"
                variant="secondary"
            >
                <x-slot:icon><i class="fa fa-arrow-left"></i></x-slot:icon>
                Voltar
            </x-buttons.link-button>

            <x-buttons.submit-button
                variant="danger"
                type="button"
                onclick="new bootstrap.Modal(document.getElementById('{{ $modalId }}')).show();"
            >
                <x-slot:icon><i class="fa fa-eraser"></i></x-slot:icon>
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
                Deseja realmente excluir o recurso
                <strong>{{ $feature->name }}</strong>?
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

            <form action="{{ route('recursos-de-acessibilidade.excluir', $feature) }}" method="POST">
                @csrf
                @method('DELETE')

                <x-buttons.submit-button variant="danger">
                    Excluir
                </x-buttons.submit-button>
            </form>
        </x-slot:footer>
    </x-modal.modal>
@endsection
