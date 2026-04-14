@extends('layouts.master')

@section('title', $barrier->name)

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Barreiras' => route('barriers.index'),
                $barrier->name => null
            ]" />

            <h1>Detalhes da Barreira</h1>
            <p class="text-muted mb-0">
                Visualize as informações, localização e histórico de vistorias.
            </p>
        </div>

        <div class="page-header-actions">
            <x-buttons.link-button
                :href="route('barriers.edit', $barrier)"
                variant="info"
            >
                <x-slot:icon><i class="fa fa-pencil"></i></x-slot:icon>
                Editar
            </x-buttons.link-button>

            <x-buttons.link-button
                :href="route('barriers.index')"
                variant="secondary"
            >
                <x-slot:icon><i class="fa fa-arrow-left"></i></x-slot:icon>
                Voltar
            </x-buttons.link-button>
        </div>
    </div>

    <div class="card-custom overflow-hidden show-container">
        <x-forms.section
            title="Detalhes da Ocorrência"
            description="Informações gerais do relato da barreira."
        />

        <x-show.info-item label="Título do Relato">
            {{ $barrier->name }}
        </x-show.info-item>

        <x-show.info-item label="Data de Identificação">
            {{ $barrier->identified_at?->format('d/m/Y') ?? 'Não informada' }}
        </x-show.info-item>

        <x-show.info-item label="Prioridade">
            <span class="badge bg-{{ $barrier->priority?->color() ?? 'secondary' }}">
                {{ $barrier->priority?->label() ?? 'Não definida' }}
            </span>
        </x-show.info-item>

        <x-show.info-item label="Categoria">
            {{ $barrier->category?->name ?? 'Não categorizada' }}
        </x-show.info-item>

        <x-show.info-item label="Campus / Unidade">
            {{ $barrier->institution?->name ?? 'Não informado' }}
        </x-show.info-item>

        <x-show.info-item label="Local / Ponto de Referência">
            {{ $barrier->location?->name ?? 'Não informado' }}
        </x-show.info-item>

        @if($barrier->location_specific_details)
            <x-show.info-textarea
                label="Complemento"
                :value="$barrier->location_specific_details"
                :rich="true"
            />
        @endif

        <x-show.info-textarea
            label="Descrição"
            :value="$barrier->description ?: 'Sem descrição.'"
            :rich="true"
        />

        <x-show.info-item label="Deficiências Relacionadas">
            {{ $barrier->deficiencies->pluck('name')->join(', ') ?: '---' }}
        </x-show.info-item>

        <x-show.info-item label="Tipo de Relato">
            @if($barrier->is_anonymous)
                <span class="text-muted fw-bold">
                    <i class="fa fa-user-secret me-1"></i> Relato Anônimo
                </span>
            @elseif($barrier->not_applicable)
                <span class="fw-bold">
                    Relato Geral
                </span>

                @if($barrier->affected_person_name || $barrier->affected_person_role)
                    <div class="mt-2 pt-2 border-top">
                        <small class="text-muted d-block">Identificação manual</small>
                        <span>
                            {{ $barrier->affected_person_name }}
                            {{ $barrier->affected_person_role ? "({$barrier->affected_person_role})" : '' }}
                        </span>
                    </div>
                @endif
            @else
                <div class="d-flex flex-column gap-2">

                    @if($barrier->affectedStudent)
                        <div>
                            <i class="fa fa-user-graduate text-primary me-1"></i>
                            <strong>{{ $barrier->affectedStudent->person->name }}</strong>
                            <small class="text-muted d-block">Estudante</small>
                        </div>
                    @endif

                    @if($barrier->affectedProfessional)
                        <div class="{{ $barrier->affectedStudent ? 'border-top pt-2' : '' }}">
                            <i class="fa fa-user-tie text-success me-1"></i>
                            <strong>{{ $barrier->affectedProfessional->person->name }}</strong>
                            <small class="text-muted d-block">Profissional</small>
                        </div>
                    @endif

                    @if(!$barrier->affectedStudent && !$barrier->affectedProfessional)
                        <span class="text-muted">Não informado</span>
                    @endif
                </div>
            @endif
        </x-show.info-item>

        <x-show.info-item label="Relator">
            {{ $barrier->reporter_display_name }}
        </x-show.info-item>

        <x-forms.separator/>

        @can('system.audit.view')
            <x-forms.section
                title="Registro do Sistema"
                description="Informações automáticas de auditoria do sistema."
            />

            <x-show.info-item label="ID no Sistema">
                #{{ $barrier->id }}
            </x-show.info-item>

            <x-show.info-item label="Status do Sistema">
            <span class="badge bg-{{ $barrier->is_active ? 'success' : 'danger' }}">
                {{ $barrier->is_active ? 'Ativo' : 'Inativo' }}
            </span>
            </x-show.info-item>

            <x-show.info-item label="Criado em">
                {{ $barrier->created_at?->format('d/m/Y \à\s H:i') ?? '---' }}
            </x-show.info-item>

            <x-show.info-item label="Última atualização">
                {{ $barrier->updated_at?->format('d/m/Y \à\s H:i') ?? '---' }}
            </x-show.info-item>
        @endcan

        <x-forms.separator/>

        <x-forms.section
            title="Localização"
            description="Visualização da barreira no mapa da instituição."
        />

        <div style="position: relative;">
            <x-show.maps.barrier
                :barrier="$barrier"
                :institution="$barrier->institution"
                height="450px"
            />

            @if($barrier->category?->blocks_map)
                <div id="map-blocked-overlay"
                     style="position:absolute; inset:0; background:rgba(255,255,255,0.85);
                     display:flex; align-items:center; justify-content:center; font-weight:bold;">
                    <div class="bg-white p-3 rounded shadow-sm text-center">
                        <i class="fa fa-lock text-danger mb-2 d-block"></i>
                        Mapa não aplicável para esta categoria
                    </div>
                </div>
            @endif
        </div>

        <x-forms.separator/>

        <x-forms.section title="Histórico de Vistorias" />

        <div class="history-timeline custom-scrollbar p-3 border rounded bg-white"
             style="max-height: 450px; overflow-y:auto;">

            @forelse($barrier->inspections as $inspection)
                <div class="mb-3 cursor-pointer"
                     role="button"
                     tabindex="0"
                     data-url="{{ route('barriers.inspection.show', [$barrier, $inspection]) }}">

                    <x-forms.inspection-history-card :inspection="$inspection" />
                </div>
            @empty
                <p class="text-muted text-center mb-0">
                    Nenhuma vistoria registrada.
                </p>
            @endforelse
        </div>

        @php
            $modalId = "modal-delete-barrier-" . $barrier->id;
        @endphp

        <x-show.footer>
            <div class="d-flex gap-2">
                <x-buttons.link-button
                    :href="route('barriers.index')"
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
                    <x-slot:icon><i class="fa fa-trash"></i></x-slot:icon>
                    Excluir
                </x-buttons.submit-button>
            </div>
        </x-show.footer>
    </div>

    @push('styles')
        <style>
            .custom-scrollbar::-webkit-scrollbar { width: 6px; }
            .custom-scrollbar::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }
        </style>
    @endpush

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @endpush
    @vite('resources/js/pages/barriers.js')
@endsection
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
            Deseja realmente excluir a barreira
            <strong>{{ $barrier->name }}</strong>?
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

        <form action="{{ route('barriers.destroy', $barrier) }}" method="POST">
            @csrf
            @method('DELETE')

            <x-buttons.submit-button variant="danger">
                Excluir
            </x-buttons.submit-button>
        </form>
    </x-slot:footer>
</x-modal.modal>
