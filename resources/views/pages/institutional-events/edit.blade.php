@extends('layouts.master')

@section('title', "Editar - $event->title")

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Agenda Institucional' => route('agenda-institucional.index'),
                $event->title => route('agenda-institucional.visualizar', $event),
                'Editar' => null
            ]" />
            <h1>Editar Evento Institucional</h1>
            <p class="text-muted mb-0">
                Atualize as informações do evento ou compromisso na agenda institucional.
            </p>
        </div>

        <div class="page-header-actions">
            <x-buttons.link-button
                :href="route('agenda-institucional.visualizar', $event)"
                variant="secondary"
            >
                <x-slot:icon><i class="fa fa-times" aria-hidden="true"></i></x-slot:icon>
                Cancelar
            </x-buttons.link-button>
        </div>
    </div>

    <x-forms.form-card
        action="{{ route('agenda-institucional.atualizar', $event) }}"
        method="POST"
        class="form-horizontal"
    >
        @method('PUT')

        <x-forms.section
            title="Informações Principais"
            description="Atualize os dados básicos e o local do evento."
        />

        <x-forms.input
            name="title"
            label="Título do Evento"
            required
            :horizontal="true"
            placeholder="Ex: Reunião de Coordenação"
            :value="old('title', $event->title)"
        />

        <x-forms.textarea
            name="description"
            label="Descrição"
            :horizontal="true"
            rows="3"
            placeholder="Detalhes sobre o objetivo do evento"
            :value="old('description', $event->description)"
        />

        <x-forms.input
            name="location"
            label="Local"
            :horizontal="true"
            placeholder="Ex: Sala de Reuniões 1"
            :value="old('location', $event->location)"
        />

        <x-forms.input
            name="organizer"
            label="Organizador"
            :horizontal="true"
            placeholder="Ex: Coordenação Pedagógica"
            :value="old('organizer', $event->organizer)"
        />

        <x-forms.separator />

        <x-forms.section
            title="Cronograma"
            description="Ajuste as datas e horários de início e término."
        />

        <x-forms.input
            type="date"
            name="start_date"
            label="Data de Início"
            required
            :horizontal="true"
            :value="old('start_date', $event->start_date?->format('Y-m-d'))"
        />

        <x-forms.input
            type="time"
            name="start_time"
            label="Horário de Início"
            required
            :horizontal="true"
            :value="old('start_time', $event->start_time?->format('H:i'))"
        />

        <x-forms.input
            type="date"
            name="end_date"
            label="Data de Término"
            required
            :horizontal="true"
            :value="old('end_date', $event->end_date?->format('Y-m-d'))"
        />

        <x-forms.input
            type="time"
            name="end_time"
            label="Horário de Término"
            required
            :horizontal="true"
            :value="old('end_time', $event->end_time?->format('H:i'))"
        />

        <x-forms.separator />

        <x-forms.section
            title="Público e Configurações"
            description="Defina quem deve participar e a visibilidade na agenda."
        />

        <x-forms.input
            name="audience"
            label="Ouvintes"
            :horizontal="true"
            placeholder="Ex: Professores, Equipe Administrativa"
            :value="old('audience', $event->audience)"
        />

        <x-forms.switch
            name="is_active"
            label="Exibir na Agenda"
            :horizontal="true"
            :checked="old('is_active', $event->is_active)"
        />

        <x-forms.form-footer>
            <x-buttons.link-button
                :href="route('agenda-institucional.visualizar', $event)"
                variant="secondary"
            >
                <x-slot:icon><i class="fa fa-times" aria-hidden="true"></i></x-slot:icon>
                Cancelar
            </x-buttons.link-button>

            <x-buttons.submit-button variant="new">
                <x-slot:icon><i class="fa fa-save" aria-hidden="true"></i></x-slot:icon>
                Salvar
            </x-buttons.submit-button>
        </x-forms.form-footer>
    </x-forms.form-card>
@endsection
