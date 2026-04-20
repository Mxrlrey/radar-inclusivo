@extends('layouts.master')

@section('title', $event->title)

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Agenda Institucional' => route('agenda-institucional.index'),
                $event->title => null
            ]" />

            <h1>Detalhes da Agenda</h1>
            <p class="text-muted mb-0">
                Visualize as informações, horários e público-alvo do evento institucional.
            </p>
        </div>

        <div class="page-header-actions">
            <x-buttons.link-button
                :href="route('agenda-institucional.editar', $event)"
                variant="info">
                <span class="btn-label"><i class="fa fa-pencil"></i></span> Editar
            </x-buttons.link-button>

            <x-buttons.link-button
                :href="route('agenda-institucional.index')"
                variant="secondary">
                <span class="btn-label"><i class="fa fa-arrow-left"></i></span> Voltar
            </x-buttons.link-button>
        </div>
    </div>

    <div class="card-custom overflow-hidden show-container">
        <x-forms.section
            title="Informações do Registro"
            description="Dados principais e cronograma do evento."
        />

        <x-show.info-item label="Título do Evento" :value="$event->title" />

        <x-show.info-item label="Descrição">
            {!! $event->description ?: '<span class="text-muted">Nenhuma descrição fornecida.</span>' !!}
        </x-show.info-item>

        <x-show.info-item label="Cronograma">
            {{ $event->start_date?->format('d/m/Y') }} às {{ $event->start_time?->format('H:i') }}
            <i class="fa fa-long-arrow-right mx-2 text-muted"></i>
            {{ $event->end_date?->format('d/m/Y') }} às {{ $event->end_time?->format('H:i') }}
        </x-show.info-item>

        <x-forms.separator/>

        <x-forms.section
            title="Detalhes Adicionais"
            description="Localização e público envolvido."
        />

        <x-show.info-item label="Local" :value="$event->location ?: 'Não informado'" />

        <x-show.info-item label="Organizador" :value="$event->organizer ?: 'Não informado'" />

        <x-show.info-item label="Público-Alvo" :value="$event->audience ?: 'Geral'" />

        <x-show.info-item label="Status">
            <span class="badge bg-{{ $event->is_active ? 'success' : 'secondary' }}">
                {{ $event->is_active ? 'Ativo' : 'Inativo' }}
            </span>
        </x-show.info-item>

        <x-forms.separator/>

        @can('system.audit.view')
            <x-forms.section
                title="Registro do Sistema"
                description="Informações automáticas de auditoria."
            />

            <x-show.info-item label="ID no Sistema">
                #{{ $event->id }}
            </x-show.info-item>

            <x-show.info-item label="Criado em">
                {{ $event->created_at?->format('d/m/Y \à\s H:i') ?? '---' }}
            </x-show.info-item>

            <x-show.info-item label="Última atualização">
                {{ $event->updated_at?->format('d/m/Y \à\s H:i') ?? '---' }}
            </x-show.info-item>
        @endcan

        @php
            $modalId = "modal-delete-event-" . $event->id;
        @endphp

        <x-show.footer>
            <x-buttons.link-button
                :href="route('agenda-institucional.index')"
                variant="secondary"
            >
                <span class="btn-label"><i class="fa fa-arrow-left"></i></span>
                Voltar
            </x-buttons.link-button>

            <x-buttons.pdf-button
                :href="route('agenda-institucional.pdf', $event)"
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
                Deseja realmente excluir o evento <strong>{{ $event->title }}</strong> da agenda?
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

            <form action="{{ route('agenda-institucional.excluir', $event) }}" method="POST">
                @csrf
                @method('DELETE')
                <x-buttons.submit-button variant="danger">
                    Excluir
                </x-buttons.submit-button>
            </form>
        </x-slot:footer>
    </x-modal.modal>
@endsection
