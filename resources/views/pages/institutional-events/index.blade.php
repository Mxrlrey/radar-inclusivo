@extends('layouts.master')

@section('title', 'Agenda Institucional')

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Agenda Institucional' => null
            ]" />
            <h1>Agenda Institucional</h1>
            <p class="text-muted mb-0">
                Gerenciamento de eventos, reuniões e atividades institucionais.
            </p>
        </div>

        <div class="page-header-actions">
            <x-buttons.link-button
                :href="route('institutional-events.create')"
                variant="info"
                aria-label="Cadastrar novo evento institucional"
            >
                <span class="btn-label"><i class="fa fa-plus" aria-hidden="true"></i></span>
                Cadastrar
            </x-buttons.link-button>
        </div>
    </div>

    <div class="card-custom overflow-hidden">
        <div class="px-3 pt-3">
            <x-table.filters.form
                data-url="{{ route('institutional-events.index') }}"
                data-target="#events-table"
                :fields="[
                    [
                        'name' => 'title',
                        'placeholder' => 'Filtrar por nome...',
                        'label' => 'Nome do evento'
                    ],
                    [
                        'name' => 'is_active',
                        'type' => 'select',
                        'placeholder' => 'Status',
                        'label' => 'Situação',
                        'options' => [
                            '' => 'Status (Todos)',
                            '1' => 'Ativo',
                            '0' => 'Inativo'
                        ]
                    ]
                ]"
            />
        </div>

        <div id="events-table" class="p-3" role="region" aria-label="Listagem de eventos institucionais">
            @include('pages.institutional-events.partials.table')
        </div>
    </div>

    @push('scripts')
        @vite('resources/js/components/dynamicFilters.js')
    @endpush
@endsection
