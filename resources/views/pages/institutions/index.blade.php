@extends('layouts.master')

@section('title', 'Instituições')

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Instituições' => null
            ]" />

            <h1>Instituições</h1>

            <p class="text-muted mb-0">
                Gerenciamento dos locais base onde o radar de acessibilidade opera.
            </p>
        </div>

        <div class="page-header-actions">
            <x-buttons.link-button
                :href="route('institutions.create')"
                variant="info"
                aria-label="Cadastrar nova instituição"
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
                data-url="{{ route('institutions.index') }}"
                data-target="#institutions-table"
                :fields="[
                    [
                        'name' => 'name',
                        'placeholder' => 'Filtrar por nome...'
                    ],
                    [
                        'name' => 'location',
                        'placeholder' => 'Filtrar por cidade ou estado...'
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

        <div id="institutions-table" class="p-3" role="region" aria-label="Listagem de instituições">
            @include('pages.institutions.partials.table')
        </div>
    </div>

    @push('scripts')
        @vite('resources/js/components/dynamicFilters.js')
    @endpush
@endsection
