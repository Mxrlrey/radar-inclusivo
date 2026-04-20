@extends('layouts.master')

@section('title', 'Cargos')

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Cargos' => null
            ]" />
            <h1>Cargos</h1>
            <p class="text-muted mb-0">Gerenciamento de funções para o suporte especializado.</p>
        </div>
        <div class="page-header-actions">
            <x-buttons.link-button
                :href="route('cargos.criar')"
                variant="info"
                aria-label="Cadastrar novo cargo"
            >
                <span class="btn-label"><i class="fa fa-plus" aria-hidden="true"></i></span> Cadastrar
            </x-buttons.link-button>
        </div>
    </div>

    <div class="card-custom overflow-hidden">
        <div class="px-3 pt-3">
            <x-table.filters.form
                data-dynamic-filter
                data-target="#positions-table"
                :fields="[
                    ['name' => 'name', 'placeholder' => 'Filtrar por nome...', 'label' => 'Nome do cargo'],
                    ['name' => 'is_active', 'type' => 'select', 'options' => [
                        '' => 'Status (Todos)',
                        '1' => 'Ativo',
                        '0' => 'Inativo'
                    ]]
                ]"
            />
        </div>

        <div id="positions-table" class="p-3" role="region" aria-label="Listagem de cargos">
            @include('pages.positions.partials.table')
        </div>
    </div>

    @push('scripts')
        @vite('resources/js/components/dynamicFilters.js')
    @endpush
@endsection
