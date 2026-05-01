@extends('layouts.master')

@section('title', 'Profissionais')

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Profissionais' => null
            ]" />

            <h1>Profissionais</h1>

            <p class="text-muted mb-0">
                Gerenciamento dos profissionais e seus registros de apoio educacional.
            </p>
        </div>
        @can('professional.create')
            <div class="page-header-actions">
                <x-buttons.link-button
                    :href="route('profissionais.criar')"
                    variant="info"
                    aria-label="Cadastrar novo profissional"
                >
                    <span class="btn-label">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </span>
                    Cadastrar
                </x-buttons.link-button>
            </div>
        @endcan
    </div>

    <div class="card-custom overflow-hidden">
        <div class="px-3 pt-3">
            <x-table.filters.form
                data-url="{{ route('profissionais.index') }}"
                data-target="#professionals-table"
                :fields="[
                    [
                        'name' => 'name',
                        'label' => 'Nome do profissional'
                    ],
                    [
                        'name' => 'email',
                        'label' => 'E-mail'
                    ],
                    [
                        'name' => 'position',
                        'type' => 'select',
                        'label' => 'Cargo',
                        'options' => array_merge(
                            ['' => 'Cargo (Todos)'],
                            collect($positions)
                                ->mapWithKeys(fn($position) => [$position->id => $position->name])
                                ->toArray()
                        )
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
                    ],
                ]"
            />
        </div>

        <div id="professionals-table" class="p-3" role="region" aria-label="Listagem de profissionais">
            @include('pages.professionals.partials.table')
        </div>
    </div>

    @push('scripts')
        @vite('resources/js/components/dynamicFilters.js')
    @endpush
@endsection
