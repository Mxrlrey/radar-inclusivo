@extends('layouts.master')

@section('title', $barrierCategory->name)

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Categorias de Barreiras' => route('categorias-de-barreiras.index'),
                $barrierCategory->name => null
            ]" />

            <h1>Detalhes da Categoria de Barreira</h1>
            <p class="text-muted mb-0">
                Visualize as informações cadastrais da categoria.
            </p>
        </div>

        <div class="page-header-actions">
            <x-buttons.link-button
                :href="route('categorias-de-barreiras.editar', $barrierCategory)"
                variant="info"
            >
                <x-slot:icon><i class="fa fa-pencil"></i></x-slot:icon>
                Editar
            </x-buttons.link-button>

            <x-buttons.link-button
                :href="route('categorias-de-barreiras.index')"
                variant="secondary"
            >
                <x-slot:icon><i class="fa fa-arrow-left"></i></x-slot:icon>
                Voltar
            </x-buttons.link-button>
        </div>
    </div>

    <div class="card-custom overflow-hidden show-container">
        <x-forms.section
            title="Informações da Categoria"
            description="Dados principais da categoria de barreira."
        />

        <x-show.info-item label="Nome da Categoria">
            {{ $barrierCategory->name }}
        </x-show.info-item>

        <x-show.info-item label="Descrição Detalhada">
            {{ $barrierCategory->description ? strip_tags($barrierCategory->description) : '— Não informada —' }}
        </x-show.info-item>

        <x-forms.separator />

        <x-forms.section title="Informações do Registro" />

        <x-show.info-item label="ID">
            #{{ $barrierCategory->id }}
        </x-show.info-item>

        <x-show.info-item label="Status no Sistema">
            <span class="badge bg-{{ $barrierCategory->is_active ? 'success' : 'danger' }}">
                {{ $barrierCategory->is_active ? 'Ativo' : 'Inativo' }}
            </span>
        </x-show.info-item>

        <x-show.info-item label="Mapa se Aplica">
            <span class="badge bg-{{ !$barrierCategory->blocks_map ? 'success' : 'danger' }}">
                {{ !$barrierCategory->blocks_map ? 'Sim' : 'Não' }}
            </span>
        </x-show.info-item>

        <x-show.info-item label="Cadastrado em">
            {{ $barrierCategory->created_at?->format('d/m/Y H:i') ?? '---' }}
        </x-show.info-item>

        <x-show.info-item label="Atualizado em">
            {{ $barrierCategory->updated_at?->format('d/m/Y H:i') ?? '---' }}
        </x-show.info-item>

        @php
            $modalId = 'modal-delete-barrier-category-' . $barrierCategory->id;
        @endphp

        <x-show.footer>
            <x-buttons.link-button
                :href="route('categorias-de-barreiras.index')"
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
                Deseja realmente excluir a categoria
                <strong>{{ $barrierCategory->name }}</strong>?
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

            <form action="{{ route('categorias-de-barreiras.excluir', $barrierCategory) }}" method="POST">
                @csrf
                @method('DELETE')

                <x-buttons.submit-button variant="danger">
                    Excluir
                </x-buttons.submit-button>
            </form>
        </x-slot:footer>
    </x-modal.modal>
@endsection
