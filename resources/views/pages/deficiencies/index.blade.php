@extends('layouts.master')

@section('title', 'Deficiências')

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Deficiências' => null
            ]" />
            <h1>Deficiências</h1>
            <p class="text-muted mb-0">
                Categorias e códigos CID registrados no sistema.
            </p>
        </div>

        <div class="page-header-actions">
            <x-buttons.link-button
                :href="route('deficiencias.criar')"
                variant="info"
                aria-label="Cadastrar nova deficiência"
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
                data-url="{{ route('deficiencias.index') }}"
                data-target="#deficiencies-table"
                :fields="[
                    [
                        'name' => 'name',
                        'placeholder' => 'Filtrar por nome...',
                        'label' => 'Nome da deficiência'
                    ],
                    [
                        'name' => 'cid_code',
                        'placeholder' => 'Filtrar por CID...',
                        'label' => 'Código CID'
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

        <div id="deficiencies-table" class="p-3" role="region" aria-label="Listagem de deficiências">
            @include('pages.deficiencies.partials.table')
        </div>
    </div>

    @push('scripts')
        @vite('resources/js/components/dynamicFilters.js')
    @endpush
@endsection
