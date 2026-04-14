@extends('layouts.master')

@section('title', 'Pontos de Referência')

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Pontos de Referência' => null
            ]" />

            <h1>Pontos de Referência</h1>

            <p class="text-muted mb-0">
                Gerenciamento dos prédios, salas e locais específicos dentro de cada instituição.
            </p>
        </div>

        <div class="page-header-actions">
            <x-buttons.link-button
                :href="route('locations.create')"
                variant="info"
                aria-label="Adicionar novo ponto de referência"
            >
                <span class="btn-label">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </span>
                Cadastrar
            </x-buttons.link-button>
        </div>
    </div>

    <div class="card-custom overflow-hidden">
        <div class="px-3 pt-3">
            <x-table.filters.form
                data-url="{{ route('locations.index') }}"
                data-target="#locations-table"
                :fields="[
                    [
                        'name' => 'name',
                        'placeholder' => 'Filtrar por nome...'
                    ],
                    [
                        'name' => 'institution_name',
                        'placeholder' => 'Filtrar por instituição...'
                    ],
                    [
                        'name' => 'is_active',
                        'type' => 'select',
                        'placeholder' => 'Status',
                        'options' => [
                            '' => 'Status (Todos)',
                            '1' => 'Ativo',
                            '0' => 'Inativo'
                        ]
                    ]
                ]"
            />
        </div>

        <div id="locations-table" class="p-3" role="region" aria-label="Listagem de pontos de referência">
            @include('pages.locations.partials.table')
        </div>
    </div>

    @push('scripts')
        @vite('resources/js/components/dynamicFilters.js')
    @endpush
@endsection
