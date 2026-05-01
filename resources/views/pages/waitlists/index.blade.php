@extends('layouts.master')

@section('title', 'Fila de Espera')

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Fila de Espera' => null
            ]" />
            <h1>Fila de Espera</h1>
            <p class="text-muted mb-0">
                Gerencie solicitações de recursos que estão indisponíveis para empréstimo.
            </p>
        </div>

        <div class="page-header-actions">
            <x-buttons.link-button
                :href="route('filas-de-espera.criar')"
                variant="info"
                aria-label="Adicionar à fila de espera"
            >
                <span class="btn-label"><i class="fa fa-plus" aria-hidden="true"></i></span>
                Cadastrar
            </x-buttons.link-button>
        </div>
    </div>

    <div class="card-custom overflow-hidden">
        <div class="px-3 pt-3">
            <x-table.filters.form
                data-url="{{ route('filas-de-espera.index') }}"
                data-target="#waitlists-table"
                :fields="[
                    [
                        'name' => 'item',
                        'label' => 'Filtrar por item...'
                    ],
                    [
                        'name' => 'student',
                        'label' => 'Aluno'
                    ],
                    [
                        'name' => 'professional',
                        'label' => 'Profissional'
                    ],
                    [
                        'name' => 'status',
                        'type' => 'select',
                        'label' => 'Status',
                        'options' => [
                            ''          => 'Status (Todos)',
                            'waiting'   => 'Em espera',
                            'notified'  => 'Notificado',
                            'fulfilled' => 'Atendido',
                            'cancelled' => 'Cancelado',
                        ]
                    ]
                ]"
            />
        </div>

        <div id="waitlists-table" class="p-3" role="region" aria-label="Listagem da fila de espera">
            @include('pages.waitlists.partials.table')
        </div>
    </div>

    @push('scripts')
        @vite('resources/js/components/dynamicFilters.js')
    @endpush
@endsection
