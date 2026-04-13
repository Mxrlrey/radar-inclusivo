@extends('layouts.master')

@section('title', 'Materiais Pedagógicos Acessíveis')

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Materiais Pedagógicos Acessíveis' => null
            ]" />
            <h1>Materiais Pedagógicos Acessíveis</h1>
            <p class="text-muted mb-0">Gestão de recursos didáticos, livros e jogos adaptados.</p>
        </div>
        <div class="page-header-actions">
            <x-buttons.link-button
                :href="route('accessible-educational-materials.create')"
                variant="info"
                aria-label="Cadastrar novo material pedagógico"
            >
                <span class="btn-label"><i class="fa fa-plus" aria-hidden="true"></i></span> Cadastrar
            </x-buttons.link-button>
        </div>
    </div>

    <div class="card-custom overflow-hidden">
        <div class="px-3 pt-3">
            <x-table.filters.form
                data-url="{{ route('accessible-educational-materials.index') }}"
                data-target="#materials-table"
                :fields="[
                    [
                        'name' => 'name',
                        'placeholder' => 'Filtrar por nome...',
                        'label' => 'Nome do material'
                    ],
                    [
                        'name' => 'is_digital',
                        'type' => 'select',
                        'placeholder' => 'Natureza',
                        'options' => [
                            '' => 'Natureza (Todos)',
                            '1' => 'Digital',
                            '0' => 'Físico'
                        ]
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
                    ],
                    [
                        'name' => 'available',
                        'type' => 'select',
                        'placeholder' => 'Disponibilidade',
                        'options' => [
                            '' => 'Disponibilidade (Todos)',
                            '1' => 'Disponível',
                            '0' => 'Indisponível'
                        ]
                    ]
                ]"
            />
        </div>

        <div id="materials-table" class="p-3" role="region" aria-label="Listagem de materiais pedagógicos">
            @include('pages.accessible-educational-materials.partials.table')
        </div>
    </div>

    @push('scripts')
        @vite('resources/js/components/dynamicFilters.js')
    @endpush
@endsection
