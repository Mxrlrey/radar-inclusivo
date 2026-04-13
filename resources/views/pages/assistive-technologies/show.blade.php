@extends('layouts.master')

@section('title', $assistiveTechnology->name)

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Tecnologias Assistivas' => route('assistive-technologies.index'),
                $assistiveTechnology->name => null
            ]" />

            <h1>Detalhes da Tecnologia Assistiva</h1>
            <p class="text-muted mb-0">
                Visualize informações cadastrais, histórico de vistorias, treinamentos e gestão do item.
            </p>
        </div>

        <div class="page-header-actions">
            <x-buttons.link-button
                :href="route('assistive-technologies.edit', $assistiveTechnology)"
                variant="info">
                <span class="btn-label"><i class="fa fa-pencil"></i></span> Editar
            </x-buttons.link-button>

            <x-buttons.link-button
                :href="route('assistive-technologies.index')"
                variant="secondary">
                <span class="btn-label"><i class="fa fa-arrow-left"></i></span> Voltar
            </x-buttons.link-button>
        </div>
    </div>

    <div class="card-custom overflow-hidden show-container">
        <x-forms.section
            title="{{ $assistiveTechnology->name }}"
            description="Visualize as informações de {{ $assistiveTechnology->name }}"
        />

        <x-show.info-item label="Tipo da Tecnologia" :value="$assistiveTechnology->name" />

        <x-show.info-item label="Descrição Detalhada">
            {!! $assistiveTechnology->notes ?: '---' !!}
        </x-show.info-item>

        <x-show.info-item label="Natureza do Recurso">
            {{ $assistiveTechnology->is_digital ? 'Recurso Digital' : 'Recurso Físico' }}
        </x-show.info-item>

        <x-show.info-item label="Patrimônio / Tombamento">
            {{ $assistiveTechnology->asset_code ?? 'Não se Aplica' }}
        </x-show.info-item>

        <x-forms.separator/>

        <x-forms.section title="Histórico de Vistorias" />

        <div class="show-field">
            <span class="show-label"></span>

            <div class="show-value" style="max-width: 100%; flex: 1;">
                <div class="history-timeline">

                    @forelse($inspections as $inspection)
                        <div class="mb-3 cursor-pointer inspection-link"
                             role="button"
                             tabindex="0"
                             data-url="{{ route('assistive-technologies.inspection.show', [$assistiveTechnology, $inspection]) }}"
                             aria-label="Ver detalhes da vistoria de {{ $inspection->inspection_date->format('d/m/Y') }}">

                            <x-forms.inspection-history-card :inspection="$inspection" />
                        </div>
                    @empty
                        <p class="text-muted mb-0">Nenhum histórico de vistoria encontrado.</p>
                    @endforelse

                </div>
            </div>
        </div>

        <x-forms.separator/>

        <x-forms.section title="Gestão e Público" />

        <x-show.info-item label="Quantidade Total">
            {{ $assistiveTechnology->is_digital ? 'Não se aplica' : $assistiveTechnology->quantity }}
        </x-show.info-item>

        <x-show.info-item label="Quantidade Disponível">
            {{ $assistiveTechnology->is_digital ? 'Não se aplica' : ($assistiveTechnology->quantity_available ?? '---') }}
        </x-show.info-item>

        <x-show.info-item label="Status do Recurso">
            <span class="badge bg-{{ $assistiveTechnology->status?->color() ?? 'secondary' }}">
                {{ $assistiveTechnology->status?->label() ?? '---' }}
            </span>
        </x-show.info-item>

        <x-show.info-item label="Permite Empréstimos">
            <span class="badge bg-{{ $assistiveTechnology->is_loanable ? 'success' : 'danger' }}">
                {{ $assistiveTechnology->is_loanable ? 'Sim' : 'Não' }}
            </span>
        </x-show.info-item>

        <x-show.info-item label="Status no Sistema">
            <span class="badge bg-{{ $assistiveTechnology->is_active ? 'success' : 'danger' }}">
                {{ $assistiveTechnology->is_active ? 'Ativo' : 'Inativo' }}
            </span>
        </x-show.info-item>

        <x-show.info-item label="Público-Alvo">
            <div class="tag-container">
                @forelse($deficiencies as $def)
                    <x-show.tag color="light">{{ $def->name }}</x-show.tag>
                @empty
                    <span class="text-muted">Nenhum público-alvo definido.</span>
                @endforelse
            </div>
        </x-show.info-item>

        @php
            $modalId = "modal-delete-assistive-tech-" . $assistiveTechnology->id;
        @endphp

        <x-show.footer>
            <x-buttons.link-button
                :href="route('assistive-technologies.index')"
                variant="secondary"
            >
                <span class="btn-label"><i class="fa fa-arrow-left"></i></span>
                Voltar
            </x-buttons.link-button>

            <x-buttons.link-button
                :href="route('assistive-technologies.logs', $assistiveTechnology)"
                variant="secondary-outline"
            >
                <span class="btn-label"><i class="fa fa-history"></i></span>
                Logs
            </x-buttons.link-button>

            <x-buttons.pdf-button
                :href="route('assistive-technologies.pdf', $assistiveTechnology)"
            />

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
                Deseja realmente excluir a tecnologia assistiva
                <strong>{{ $assistiveTechnology->name }}</strong>?
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

            <form action="{{ route('assistive-technologies.destroy', $assistiveTechnology) }}" method="POST">
                @csrf
                @method('DELETE')

                <x-buttons.submit-button variant="danger">
                    Excluir
                </x-buttons.submit-button>
            </form>
        </x-slot:footer>
    </x-modal.modal>
    @vite('resources/js/pages/assistive-technologies.js')
@endsection
