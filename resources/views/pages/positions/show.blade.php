@extends('layouts.master')

@section('title', 'Cargo: ' . $position->name)

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Cargos' => route('cargos.index'),
                $position->name => null
            ]" />

            <h1>Detalhes do Cargo</h1>
            <p class="text-muted mb-0">
                Visualize informações cadastrais, atribuições e permissões vinculadas a este cargo.
            </p>
        </div>

        <div class="page-header-actions">
            <x-buttons.link-button
                :href="route('cargos.editar', $position)"
                variant="info">
                <span class="btn-label"><i class="fa fa-pencil"></i></span> Editar
            </x-buttons.link-button>

            <x-buttons.link-button
                :href="route('cargos.index')"
                variant="secondary">
                <span class="btn-label"><i class="fa fa-arrow-left"></i></span> Voltar
            </x-buttons.link-button>
        </div>
    </div>

    <div class="card-custom overflow-hidden show-container">
        <x-forms.section
            title="{{ $position->name }}"
            description="Informações básicas e identificação do cargo no sistema."
        />

        <x-show.info-item label="Nome do Cargo / Função" :value="$position->name" />

        <x-show.info-item label="Status no Sistema">
            <span class="badge bg-{{ $position->is_active ? 'success' : 'danger' }}">
                {{ $position->is_active ? 'Ativo' : 'Inativo' }}
            </span>
        </x-show.info-item>

        <x-show.info-item label="Descrição da Função">
            {!! $position->description ?: 'Nenhuma descrição detalhada foi fornecida para este cargo.' !!}
        </x-show.info-item>

        <x-show.info-item label="Lista de Permissões">
            @if($position->permissions->isNotEmpty())
                <div class="tag-container">
                    @foreach($position->permissions as $permission)
                        <x-show.tag color="light">{{ $permission->name }}</x-show.tag>
                    @endforeach
                </div>
            @else
                <span class="text-muted">Nenhuma permissão específica vinculada.</span>
            @endif
        </x-show.info-item>

        <x-forms.separator />

        <x-forms.section title="Informações do Registro" />

        <x-show.info-item label="ID">
            #{{ $position->id }}
        </x-show.info-item>

        <x-show.info-item label="Status no Sistema">
            <span class="badge bg-{{ $position->is_active ? 'success' : 'danger' }}">
                {{ $position->is_active ? 'Ativo' : 'Inativo' }}
            </span>
        </x-show.info-item>

        <x-show.info-item label="Cadastrado em">
            {{ $position->created_at?->format('d/m/Y H:i') ?? '---' }}
        </x-show.info-item>

        <x-show.info-item label="Atualizado em">
            {{ $position->updated_at?->format('d/m/Y H:i') ?? '---' }}
        </x-show.info-item>

        @php
            $modalId = "modal-delete-position-" . $position->id;
        @endphp

        <x-show.footer>
            <x-buttons.link-button
                :href="route('cargos.index')"
                variant="secondary"
            >
                <span class="btn-label"><i class="fa fa-arrow-left"></i></span>
                Voltar
            </x-buttons.link-button>

            <x-buttons.submit-button
                variant="danger"
                type="button"
                onclick="new bootstrap.Modal(document.getElementById('{{ $modalId }}')).show();"
            >
                <span class="btn-label"><i class="fa fa-eraser"></i></span>
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
                Deseja realmente excluir o cargo <strong>{{ $position->name }}</strong>?
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

            <form action="{{ route('cargos.excluir', $position) }}" method="POST">
                @csrf
                @method('DELETE')
                <x-buttons.submit-button variant="danger">
                    Excluir
                </x-buttons.submit-button>
            </form>
        </x-slot:footer>
    </x-modal.modal>
@endsection
