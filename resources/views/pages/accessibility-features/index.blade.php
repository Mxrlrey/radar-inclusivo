@extends('layouts.master')

@section('title', 'Recursos de Acessibilidade')

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Recursos de Acessibilidade' => null
            ]" />
            <h1>Recursos de Acessibilidade</h1>
            <p class="text-muted mb-0">
                Gerencie as categorias de recursos de acessibilidade disponíveis no sistema.
            </p>
        </div>

        <div class="page-header-actions">
            @can('accessibility-feature.create')
                <x-buttons.link-button
                    :href="route('recursos-de-acessibilidade.criar')"
                    variant="info"
                    aria-label="Cadastrar novo recurso de acessibilidade"
                >
                    <span class="btn-label"><i class="fa fa-plus" aria-hidden="true"></i></span>
                    Cadastrar
                </x-buttons.link-button>
            @endcan
        </div>
    </div>

    <div class="card-custom overflow-hidden">
        <div class="px-3 pt-3">
            <x-table.filters.form
                data-url="{{ route('recursos-de-acessibilidade.index') }}"
                data-target="#features-table"
                :fields="[
                    [
                        'name' => 'name',
                        'label' => 'Nome do recurso'
                    ],
                    [
                        'name' => 'is_active',
                        'type' => 'select',
                        'label' => 'Status',
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

        <div id="features-table" class="p-3" role="region" aria-label="Listagem de recursos de acessibilidade">
            @include('pages.accessibility-features.partials.table')
        </div>
    </div>

    @push('scripts')
        @vite('resources/js/components/dynamicFilters.js')
    @endpush
@endsection
