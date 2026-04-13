@extends('layouts.master')

@section('title', 'Profissionais')

@section('content')
    <div class="mb-5">
        <x-breadcrumb :items="[
            'Home' => route('dashboard'),
            'Profissionais' => null
        ]" />
    </div>

    <div class="custom-table-card shadow-sm border rounded-3 overflow-hidden">
        <x-table.page-header
            title="Profissionais"
            subtitle="Gerencie os profissionais e seus documentos de apoio especializado."
        >
            <x-buttons.link-button
                :href="route('professionals.create')"
                variant="new"
                title="Adicionar profissional"
            >
                <i class="fas fa-plus"></i>
            </x-buttons.link-button>
        </x-table.page-header>

        <div class="px-3 pt-3">
            <x-table.filters.form
                data-dynamic-filter
                data-target="#professionals-table"
                :fields="[
                    [
                        'name' => 'name',
                        'placeholder' => 'Nome do Profissional...'
                    ],
                    [
                        'name' => 'email',
                        'placeholder' => 'Email...'
                    ],
                    [
                        'name' => 'position',
                        'type' => 'select',
                        'options' => ['' => 'Cargo (Todos)'] +
                            collect($positions)
                                ->mapWithKeys(fn($position) => [
                                    $position->id => $position->name
                                ])
                                ->toArray()
                    ],
                ]"
            />
        </div>

        <div id="professionals-table" class="p-3">
            @include('pages.professionals.partials.table')
        </div>
    </div>

    @push('scripts')
        @vite('resources/js/components/dynamicFilters.js')
    @endpush
@endsection
