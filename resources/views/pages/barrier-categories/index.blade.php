@extends('layouts.master')

@section('title', 'Categorias de Barreiras')

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Categorias de Barreiras' => null
            ]" />

            <h1>Categorias de Barreiras</h1>

            <p class="text-muted mb-0">
                Classificação para o mapeamento de acessibilidade e identificação de obstáculos.
            </p>
        </div>

        <div class="page-header-actions">
            <x-buttons.link-button
                :href="route('categorias-de-barreiras.criar')"
                variant="info"
                aria-label="Cadastrar nova categoria de barreira"
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
                data-url="{{ route('categorias-de-barreiras.index') }}"
                data-target="#barrier-categories-table"
                :fields="[
                    [
                        'name' => 'name',
                        'label' => 'Nome da categoria'
                    ],
                    [
                        'name' => 'is_active',
                        'type' => 'select',
                        'label' => 'Status',
                        'options' => [
                            '' => 'Status (Todos)',
                            '1' => 'Ativo',
                            '0' => 'Inativo'
                        ]
                    ]
                ]"
            />
        </div>

        <div id="barrier-categories-table" class="p-3" role="region" aria-label="Listagem de categorias de barreiras">
            @include('pages.barrier-categories.partials.table')
        </div>
    </div>

    @push('scripts')
        @vite('resources/js/components/dynamicFilters.js')
    @endpush
@endsection
