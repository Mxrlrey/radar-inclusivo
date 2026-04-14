@extends('layouts.master')

@section('title', 'Cadastrar - Agenda Institucional')

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Agenda Institucional' => route('institutional-events.index'),
                'Cadastrar' => null
            ]" />
            <h1>Novo Evento Institucional</h1>
            <p class="text-muted mb-0">
                Cadastre eventos, reuniões ou compromissos para a agenda da instituição.
            </p>
        </div>

        <div class="page-header-actions">
            <x-buttons.link-button
                :href="route('institutional-events.index')"
                variant="secondary"
            >
                <x-slot:icon><i class="fa fa-times"></i></x-slot:icon>
                Cancelar
            </x-buttons.link-button>
        </div>
    </div>

    <x-forms.form-card
        action="{{ route('institutional-events.store') }}"
        method="POST"
        class="form-horizontal"
    >
        @csrf

        <x-forms.section
            title="Informações Principais"
            description="Informe os dados básicos e o local do evento."
        />

        <x-forms.input
            name="title"
            label="Título do Evento"
            required
            :horizontal="true"
            placeholder="Ex: Reunião de Coordenação"
            :value="old('title')"
        />

        <x-forms.textarea
            name="description"
            label="Descrição"
            :horizontal="true"
            rows="3"
            placeholder="Detalhes sobre o objetivo do evento"
            :value="old('description')"
        />

        <x-forms.input
            name="location"
            label="Local"
            :horizontal="true"
            placeholder="Ex: Sala de Reuniões 1 ou Link da videochamada"
            :value="old('location')"
        />

        <x-forms.input
            name="organizer"
            label="Organizador"
            :horizontal="true"
            placeholder="Ex: Coordenação Pedagógica"
            :value="old('organizer')"
        />

        <x-forms.separator />

        <x-forms.section
            title="Cronograma"
            description="Defina as datas e horários de início e término."
        />

        <x-forms.input
            type="date"
            name="start_date"
            label="Data de Início"
            required
            :horizontal="true"
            :value="old('start_date', date('Y-m-d'))"
        />

        <x-forms.input
            type="time"
            name="start_time"
            label="Horário de Início"
            required
            :horizontal="true"
            :value="old('start_time')"
        />

        <x-forms.input
            type="date"
            name="end_date"
            label="Data de Término"
            required
            :horizontal="true"
            :value="old('end_date', date('Y-m-d'))"
        />

        <x-forms.input
            type="time"
            name="end_time"
            label="Horário de Término"
            required
            :horizontal="true"
            :value="old('end_time')"
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
            :value="old('audience')"
        />

        <x-forms.switch
            name="is_active"
            label="Exibir na Agenda"
            :horizontal="true"
            :checked="old('is_active', true)"
        />

        <x-forms.form-footer>
            <x-buttons.link-button
                :href="route('institutional-events.index')"
                variant="secondary"
            >
                <x-slot:icon><i class="fa fa-times"></i></x-slot:icon>
                Cancelar
            </x-buttons.link-button>

            <x-buttons.submit-button variant="new">
                <x-slot:icon><i class="fa fa-save"></i></x-slot:icon>
                Cadastrar
            </x-buttons.submit-button>
        </x-forms.form-footer>
    </x-forms.form-card>
@endsection
