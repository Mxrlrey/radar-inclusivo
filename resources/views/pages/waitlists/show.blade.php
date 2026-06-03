@extends('layouts.master')

@section('title', "Solicitação #$waitlist->id")

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Fila de Espera' => route('filas-de-espera.index'),
                'Detalhes' => null
            ]" />

            <h1>Detalhes da Fila de Espera</h1>
            <p class="text-muted mb-0">
                Visualize informações da solicitação, status e histórico do recurso.
            </p>
        </div>

        <div class="page-header-actions">
            @can('waitlist.edit')
                <x-buttons.link-button
                    :href="route('filas-de-espera.editar', $waitlist)"
                    variant="info">
                    <span class="btn-label"><i class="fa fa-pencil" aria-hidden="true"></i></span> Editar
                </x-buttons.link-button>
            @endcan

            <x-buttons.link-button
                :href="route('filas-de-espera.index')"
                variant="secondary">
                <span class="btn-label"><i class="fa fa-arrow-left" aria-hidden="true"></i></span> Voltar
            </x-buttons.link-button>
        </div>
    </div>

    <div class="card-custom overflow-hidden show-container">
        <x-forms.section
            title="Informações da Solicitação"
            description="Dados do recurso desejado e do beneficiário interessado."
        />

        <x-show.info-item label="Tipo do Recurso">
            {{ $waitlist->waitlistable_type === 'assistive_technology' ? 'Tecnologia Assistiva' : 'Material Pedagógico' }}
        </x-show.info-item>

        <x-show.info-item
            label="Item"
            :value="$waitlist->waitlistable->name ?? ($waitlist->waitlistable->title ?? 'Item Removido')"
        />

        @if($waitlist->student)
            <x-show.info-item
                label="Estudante (Beneficiário)"
                :value="$waitlist->student->person->name"
            />
        @elseif($waitlist->professional)
            <x-show.info-item
                label="Profissional (Beneficiário)"
                :value="$waitlist->professional->person->name"
            />
        @endif

        <x-show.info-item label="Responsável pelo Registro" :value="$waitlist->user->name ?? '---'" />

        <x-show.info-item label="Data da Solicitação" :value="$waitlist->requested_at->format('d/m/Y \à\s H:i')" />

        <x-show.info-item label="Status Atual">
            <span class="badge bg-{{ $statusColor }}">
                {{ $statusLabel }}
            </span>
        </x-show.info-item>

        <x-show.info-item label="Observações">
            {!! $waitlist->observation ?: '<span class="text-muted">Nenhuma observação registrada.</span>' !!}
        </x-show.info-item>

        @can('system.audit.view')
            <x-forms.separator/>

            <x-forms.section title="Informações do Registro" />
            <x-show.info-item label="ID" :value="'#' . $waitlist->id" />
            <x-show.info-item label="Status no Sistema">
                <span class="badge bg-{{ $statusColor }}">
                    {{ $statusLabel }}
                </span>
            </x-show.info-item>
            <x-show.info-item label="Cadastrado em" :value="$waitlist->created_at?->format('d/m/Y H:i')" />
            <x-show.info-item label="Atualizado em" :value="$waitlist->updated_at?->format('d/m/Y H:i')" />
        @endcan

        @php
            $modalDeleteId = "modal-delete-waitlist-" . $waitlist->id;
            $modalCancelId = "modal-cancel-waitlist-" . $waitlist->id;
        @endphp

        <x-show.footer>
            <x-buttons.link-button :href="route('filas-de-espera.index')" variant="secondary">
                <span class="btn-label"><i class="fa fa-arrow-left" aria-hidden="true"></i></span> Voltar
            </x-buttons.link-button>

            @can('waitlist.pdf')
                <x-buttons.link-button
                    :href="route('filas-de-espera.pdf', $waitlist)"
                    variant="danger"
                >
                    <span class="btn-label"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></span>
                    PDF
                </x-buttons.link-button>
            @endcan

            @can('waitlist.cancel')
                @if($canCancel)
                    <x-buttons.submit-button
                        variant="warning"
                        type="button"
                        label="Cancelar solicitação da fila de espera"
                        onclick="new bootstrap.Modal(document.getElementById('{{ $modalCancelId }}')).show();"
                    >
                        <span class="btn-label"><i class="fa fa-chain-broken" aria-hidden="true"></i></span> Cancelar
                    </x-buttons.submit-button>
                @endif
            @endcan

            @can('waitlist.destroy')
                <x-buttons.submit-button
                    variant="danger"
                    type="button"
                    label="Excluir solicitação da fila de espera"
                    onclick="new bootstrap.Modal(document.getElementById('{{ $modalDeleteId }}')).show();"
                >
                    <span class="btn-label"><i class="fa fa-eraser" aria-hidden="true"></i></span> Excluir
                </x-buttons.submit-button>
            @endcan
        </x-show.footer>
    </div>

    @can('waitlist.cancel')
        <x-modal.modal :id="$modalCancelId" title="Cancelar Solicitação" size="sm">
            <div class="p-3">
                <p class="mb-2 text-warning fw-bold">Esta ação não pode ser desfeita.</p>
                <p>Deseja realmente <strong>cancelar</strong> esta solicitação na fila de espera?</p>
            </div>
            <x-slot:footer>
                <x-buttons.link-button variant="secondary" type="button" onclick="bootstrap.Modal.getInstance(this.closest('.modal')).hide()">
                    Voltar
                </x-buttons.link-button>
                <form action="{{ route('filas-de-espera.cancelar', $waitlist) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <x-buttons.submit-button variant="warning" label="Confirmar cancelamento da solicitação">Cancelar</x-buttons.submit-button>
                </form>
            </x-slot:footer>
        </x-modal.modal>
    @endcan

    @can('waitlist.destroy')
        <x-modal.modal :id="$modalDeleteId" title="Confirmar Exclusão" size="sm">
            <div class="p-3">
                <p class="mb-2 text-danger fw-bold">Esta ação não pode ser desfeita.</p>
                <p class="mb-0 text-muted">Deseja excluir permanentemente o registro de fila <strong>#{{ $waitlist->id }}</strong>?</p>
            </div>
            <x-slot:footer>
                <x-buttons.link-button variant="secondary" type="button" onclick="bootstrap.Modal.getInstance(this.closest('.modal')).hide()">
                    Cancelar
                </x-buttons.link-button>
                <form action="{{ route('filas-de-espera.excluir', $waitlist) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <x-buttons.submit-button variant="danger" label="Confirmar exclusão da solicitação">Excluir</x-buttons.submit-button>
                </form>
            </x-slot:footer>
        </x-modal.modal>
    @endcan
@endsection
