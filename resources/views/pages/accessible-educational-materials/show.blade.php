@extends('layouts.master')

@section('title', $material->name)

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Materiais Pedagógicos Acessíveis' => route('accessible-educational-materials.index'),
                $material->name => null
            ]" />
            <h1>Detalhes do Material Pedagógico Acessível</h1>
            <p class="text-muted mb-0">
                Visualize informações cadastrais, histórico de vistorias e gestão do material.
            </p>
        </div>
        <div class="page-header-actions">
            <x-buttons.link-button :href="route('accessible-educational-materials.edit', $material)" variant="info">
                <span class="btn-label"><i class="fa fa-pencil"></i></span> Editar
            </x-buttons.link-button>

            <x-buttons.link-button
                :href="route('accessible-educational-materials.index')"
                variant="secondary"
            >
                <span class="btn-label"><i class="fa fa-arrow-left"></i></span> Voltar
            </x-buttons.link-button>
        </div>
    </div>

    <div class="card-custom overflow-hidden show-container">

        <x-forms.section
            title="{{ $material->name }}"
            description="Visualize as informações de {{ $material->name }}"
        />

        <x-show.info-item label="Título do Material" :value="$material->name" />

        <x-show.info-item label="Descrição">
            {!! $material->notes ?: '---' !!}
        </x-show.info-item>

        <x-show.info-item label="Natureza do Recurso">
            {{ $material->is_digital ? 'Recurso Digital' : 'Recurso Físico' }}
        </x-show.info-item>

        <x-show.info-item label="Patrimônio / Tombamento">
            {{ $material->asset_code ?? 'Não se Aplica' }}
        </x-show.info-item>

        <x-show.info-item label="Recursos de Acessibilidade">
            <div class="tag-container">
                @forelse($features as $feature)
                    <x-show.tag color="light">{{ $feature->name }}</x-show.tag>
                @empty
                    <span class="text-muted">Nenhum recurso definido.</span>
                @endforelse
            </div>
        </x-show.info-item>

        <x-show.info-item label="Quantidade Total">
            {{ $material->is_digital ? 'Não se aplica' : $material->quantity }}
        </x-show.info-item>

        <x-show.info-item label="Quantidade Disponível">
            {{ $material->is_digital ? 'Não se aplica' : ($material->quantity_available ?? '---') }}
        </x-show.info-item>

        <x-show.info-item label="Status do Recurso">
            <span class="badge bg-{{ $material->status?->color() ?? 'secondary' }}">
                {{ $material->status?->label() ?? '---' }}
            </span>
        </x-show.info-item>

        <x-show.info-item label="Permite Empréstimos">
            <span class="badge bg-{{ $material->is_loanable ? 'success' : 'danger' }}">
                {{ $material->is_loanable ? 'Sim' : 'Não' }}
            </span>
        </x-show.info-item>

        <x-show.info-item label="Status no Sistema">
            <span class="badge bg-{{ $material->is_active ? 'success' : 'danger' }}">
                {{ $material->is_active ? 'Ativo' : 'Inativo' }}
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

        @can('system.audit.view')
            <x-forms.separator/>

            <x-forms.section
                title="Registro do Sistema"
                description="Informações automáticas de auditoria do sistema."
            />

            <x-show.info-item label="ID no Sistema">
                #{{ $material->id }}
            </x-show.info-item>

            <x-show.info-item label="Status do Sistema">
            <span class="badge bg-{{ $material->is_active ? 'success' : 'danger' }}">
                {{ $material->is_active ? 'Ativo' : 'Inativo' }}
            </span>
            </x-show.info-item>

            <x-show.info-item label="Criado em">
                {{ $material->created_at?->format('d/m/Y \à\s H:i') ?? '---' }}
            </x-show.info-item>

            <x-show.info-item label="Última atualização">
                {{ $material->updated_at?->format('d/m/Y \à\s H:i') ?? '---' }}
            </x-show.info-item>
        @endcan

        <x-forms.separator/>

        <x-forms.section title="Histórico de Vistorias" />

        <div class="show-field">
            <span class="show-label"></span>
            <div class="show-value" style="max-width: 100%; flex: 1;">
                <div class="history-timeline">
                    @forelse($material->inspections as $inspection)
                        <div class="mb-3 cursor-pointer p-2 border inspection-link"
                             role="button"
                             tabindex="0"
                             data-url="{{ route('accessible-educational-materials.inspection.show', [$material, $inspection]) }}"
                             aria-label="Ver detalhes da vistoria de {{ $inspection->inspection_date->format('d/m/Y') }}">
                            <x-forms.inspection-history-card :inspection="$inspection" />
                        </div>
                    @empty
                        <p class="text-muted mb-0">Nenhum histórico de vistoria encontrado.</p>
                    @endforelse
                </div>
            </div>
        </div>

        @php
            $modalId = "modal-delete-material-" . $material->id;
        @endphp

        <x-show.footer>
            <x-buttons.link-button
                :href="route('accessible-educational-materials.index')"
                variant="secondary">
                <span class="btn-label"><i class="fa fa-arrow-left"></i></span>
                Voltar
            </x-buttons.link-button>

            <x-buttons.link-button
                :href="route('accessible-educational-materials.logs', $material)"
                variant="secondary-outline">
                <span class="btn-label"><i class="fa fa-history"></i></span>
                Logs
            </x-buttons.link-button>

            <x-buttons.pdf-button
                :href="route('accessible-educational-materials.pdf', $material)"
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
                Deseja realmente excluir o material pedagógico
                <strong>{{ $material->name }}</strong>?
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

            <form action="{{ route('accessible-educational-materials.destroy', $material) }}" method="POST">
                @csrf
                @method('DELETE')

                <x-buttons.submit-button variant="danger">
                    Excluir
                </x-buttons.submit-button>
            </form>
        </x-slot:footer>
    </x-modal.modal>

    @vite('resources/js/pages/accessible-educational-materials.js')
@endsection
