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
            <p class="text-muted mb-0">Gerencie as categorias de recursos de acessibilidade disponíveis.</p>
        </div>
        <div class="page-header-actions">
            <x-buttons.link-button :href="route('accessibility-features.create')" variant="info">
                <span class="btn-label"><i class="fa fa-plus"></i></span> Cadastrar
            </x-buttons.link-button>
        </div>
    </div>

    <div class="card-custom overflow-hidden">
        <div class="px-3 pt-3">
            <x-table.filters.form
                data-dynamic-filter
                data-target="#features-table"
                :fields="[
                    ['name' => 'name', 'placeholder' => 'Filtrar por nome...'],
                    ['name' => 'is_active', 'type' => 'select', 'options' => [
                        '' => 'Status (Todos)',
                        '1' => 'Ativo',
                        '0' => 'Inativo'
                    ]]
                ]"
            />
        </div>

        <div id="features-table" class="p-3">
            @include('pages.accessibility-features.partials.table')
        </div>
    </div>

    @push('scripts')
        @vite('resources/js/components/dynamicFilters.js')
    @endpush
@endsection
