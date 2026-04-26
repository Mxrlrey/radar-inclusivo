@extends('layouts.master')

@section('title', $material->name)

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Materiais Pedagógicos Acessíveis' => route('materiais-pedagogicos-acessiveis.index'),
                $material->name => null
            ]" />
            <h1>Detalhes do Material Pedagógico Acessível</h1>
            <p class="text-muted mb-0">
                Visualize informações cadastrais, histórico de vistorias e gestão do material.
            </p>
        </div>
        <div class="page-header-actions">
            <x-buttons.link-button :href="route('materiais-pedagogicos-acessiveis.editar', $material)" variant="info">
                <span class="btn-label"><i class="fa fa-pencil" aria-hidden="true"></i></span> Editar
            </x-buttons.link-button>

            <x-buttons.link-button
                :href="route('materiais-pedagogicos-acessiveis.index')"
                variant="secondary"
            >
                <span class="btn-label"><i class="fa fa-arrow-left" aria-hidden="true"></i></span> Voltar
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

        <dl class="show-field show-field--stacked">
            <dt class="show-label">Histórico de Vistorias</dt>
            <dd class="show-value" id="inspections-table-wrapper">
                @include('pages.accessible-educational-materials.partials.inspections-table')
            </dd>
        </dl>

        @can('system.audit.view')
            <x-forms.separator/>

            <x-forms.section title="Informações do Registro" />

            <x-show.info-item label="ID">
                #{{ $material->id }}
            </x-show.info-item>

            <x-show.info-item label="Status no Sistema">
            <span class="badge bg-{{ $material->is_active ? 'success' : 'danger' }}">
                {{ $material->is_active ? 'Ativo' : 'Inativo' }}
            </span>
            </x-show.info-item>

            <x-show.info-item label="Cadastrado em">
                {{ $material->created_at?->format('d/m/Y H:i') ?? '---' }}
            </x-show.info-item>

            <x-show.info-item label="Atualizado em">
                {{ $material->updated_at?->format('d/m/Y H:i') ?? '---' }}
            </x-show.info-item>
        @endcan

        @php
            $modalId = "modal-delete-material-" . $material->id;
        @endphp

        <x-show.footer>
            <x-buttons.link-button
                :href="route('materiais-pedagogicos-acessiveis.index')"
                variant="secondary">
                <span class="btn-label"><i class="fa fa-arrow-left" aria-hidden="true"></i></span>
                Voltar
            </x-buttons.link-button>

            <x-buttons.link-button
                :href="route('materiais-pedagogicos-acessiveis.registros', $material)"
                variant="secondary-outline">
                <span class="btn-label"><i class="fa fa-history" aria-hidden="true"></i></span>
                Logs
            </x-buttons.link-button>

            <x-buttons.link-button
                :href="route('materiais-pedagogicos-acessiveis.pdf', $material)"
                variant="danger"
            >
                <span class="btn-label"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></span>
                PDF
            </x-buttons.link-button>

            <x-buttons.submit-button
                variant="danger"
                type="button"
                label="Excluir material pedagógico acessível"
                onclick="new bootstrap.Modal(document.getElementById('{{ $modalId }}')).show();"
            >
                <span class="btn-label"><i class="fa fa-eraser" aria-hidden="true"></i></span>
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
                variant="secondary"
                type="button"
                onclick="bootstrap.Modal.getInstance(this.closest('.modal')).hide()"
            >
                Cancelar
            </x-buttons.link-button>

            <form action="{{ route('materiais-pedagogicos-acessiveis.excluir', $material) }}" method="POST">
                @csrf
                @method('DELETE')

                <x-buttons.submit-button variant="danger" label="Confirmar exclusão do material pedagógico acessível">
                    Excluir
                </x-buttons.submit-button>
            </form>
        </x-slot:footer>
    </x-modal.modal>

    @vite('resources/js/pages/accessible-educational-materials.js')
@endsection
