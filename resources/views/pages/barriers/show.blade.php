@extends('layouts.master')

@section('title', $barrier->name)

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Barreiras' => route('barreiras.index'),
                $barrier->name => null
            ]" />

            <h1>Detalhes da Barreira</h1>
            <p class="text-muted mb-0">
                Visualize as informações, localização e histórico de vistorias.
            </p>
        </div>

        <div class="page-header-actions">
            @can('barrier.edit')
                <x-buttons.link-button
                    :href="route('barreiras.editar', $barrier)"
                    variant="info"
                >
                    <x-slot:icon><i class="fa fa-pencil" aria-hidden="true"></i></x-slot:icon>
                    Editar
                </x-buttons.link-button>
            @endcan

            <x-buttons.link-button
                :href="route('barreiras.index')"
                variant="secondary"
            >
                <x-slot:icon><i class="fa fa-arrow-left" aria-hidden="true"></i></x-slot:icon>
                Voltar
            </x-buttons.link-button>
        </div>
    </div>

    <div class="card-custom overflow-hidden show-container">
        <div class="row g-0">
            <div class="col-lg-5 border-end">
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
                            <i class="fa fa-user-secret me-1" aria-hidden="true"></i> Relato Anônimo
                        </span>
                    @elseif($barrier->not_applicable)
                        <span class="fw-bold">Relato Geral</span>

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
                                    <i class="fa fa-user-graduate text-primary me-1" aria-hidden="true"></i>
                                    <strong>{{ $barrier->affectedStudent->person->name }}</strong>
                                    <small class="text-muted d-block">Estudante</small>
                                </div>
                            @endif

                            @if($barrier->affectedProfessional)
                                <div class="{{ $barrier->affectedStudent ? 'border-top pt-2' : '' }}">
                                    <i class="fa fa-user-tie text-success me-1" aria-hidden="true"></i>
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

                @can('system.audit.view')
                    <x-forms.separator />

                    <x-forms.section title="Informações do Registro" />

                    <x-show.info-item label="ID">
                        #{{ $barrier->id }}
                    </x-show.info-item>

                    <x-show.info-item label="Status no Sistema">
                        <span class="badge bg-{{ $barrier->is_active ? 'success' : 'danger' }}">
                            {{ $barrier->is_active ? 'Ativo' : 'Inativo' }}
                        </span>
                    </x-show.info-item>

                    <x-show.info-item label="Cadastrado em">
                        {{ $barrier->created_at?->format('d/m/Y H:i') ?? '---' }}
                    </x-show.info-item>

                    <x-show.info-item label="Atualizado em">
                        {{ $barrier->updated_at?->format('d/m/Y H:i') ?? '---' }}
                    </x-show.info-item>
                @endcan
            </div>

            <div class="col-lg-7 px-0">
                <x-forms.section
                    title="Localização"
                    description="Visualização da barreira no mapa da instituição."
                />

                <div class="map-container {{ $barrier->category?->blocks_map ? 'is-blocked' : '' }}" id="mapWrapper">
                    <x-show.maps.barrier
                        :barrier="$barrier"
                        :institution="$barrier->institution"
                        height="450px"
                    />

                    @if($barrier->category?->blocks_map)
                        <div id="map-blocked-overlay" class="map-overlay">
                            <div class="map-overlay-message">
                                <i class="fa fa-lock mb-2 d-block" aria-hidden="true"></i>
                                <span id="blocked-message" class="fw-bold">
                                    Mapa não aplicável para categoria {{ $barrier->category->name }}
                                </span>
                            </div>
                        </div>
                    @endif
                </div>

                <dl class="show-field show-field--stacked mt-3">
                    <dt class="show-label">Histórico de Vistorias</dt>
                    <dd class="show-value" id="inspections-table-wrapper-barrier">
                        @include('pages.barriers.partials.inspections-table')
                    </dd>
                </dl>
            </div>

            @php
                $modalId = "modal-delete-barrier-" . $barrier->id;
            @endphp

            <x-show.footer>
                <div class="d-flex gap-2">
                    <x-buttons.link-button
                        :href="route('barreiras.index')"
                        variant="secondary"
                    >
                        <x-slot:icon><i class="fa fa-arrow-left" aria-hidden="true"></i></x-slot:icon>
                        Voltar
                    </x-buttons.link-button>

                    @can('barrier.pdf')
                        <x-buttons.link-button
                            :href="route('barreiras.pdf', $barrier)"
                            variant="danger"
                        >
                            <x-slot:icon><i class="fa fa-file-pdf-o" aria-hidden="true"></i></x-slot:icon>
                            PDF
                        </x-buttons.link-button>
                    @endcan

                    @can('barrier.destroy')
                        <x-buttons.submit-button
                            variant="danger"
                            type="button"
                            label="Excluir barreira"
                            onclick="new bootstrap.Modal(document.getElementById('{{ $modalId }}')).show();"
                        >
                            <x-slot:icon><i class="fa fa-eraser" aria-hidden="true"></i></x-slot:icon>
                            Excluir
                        </x-buttons.submit-button>
                    @endcan
                </div>
            </x-show.footer>
        </div>
    </div>

    @can('barrier.destroy')
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
                    variant="secondary"
                    type="button"
                    onclick="bootstrap.Modal.getInstance(this.closest('.modal')).hide()"
                >
                    Cancelar
                </x-buttons.link-button>

                <form action="{{ route('barreiras.excluir', $barrier) }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <x-buttons.submit-button variant="danger" label="Confirmar exclusão da barreira">
                        Excluir
                    </x-buttons.submit-button>
                </form>
            </x-slot:footer>
        </x-modal.modal>
    @endcan
@endsection
@vite('resources/js/pages/barriers.js')
