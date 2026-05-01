@extends('layouts.master')

@section('title', 'Tecnologias Assistivas')

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Tecnologias Assistivas' => null
            ]" />
            <h1>Tecnologias Assistivas</h1>
            <p class="text-muted mb-0">
                Gerenciamento de periféricos, softwares e equipamentos de acessibilidade.
            </p>
        </div>

        <div class="page-header-actions">
            <x-buttons.link-button
                :href="route('tecnologias-assistivas.criar')"
                variant="info"
                aria-label="Cadastrar nova tecnologia assistiva"
            >
                <span class="btn-label"><i class="fa fa-plus" aria-hidden="true"></i></span>
                Cadastrar
            </x-buttons.link-button>
        </div>
    </div>

    <div class="card-custom overflow-hidden">
        <div class="px-3 pt-3">
            <x-table.filters.form
                data-url="{{ route('tecnologias-assistivas.index') }}"
                data-target="#assistive-technologies-table"
                :fields="[
                    [
                        'name' => 'name',
                        'label' => 'Nome da tecnologia'
                    ],
                    [
                        'name' => 'is_digital',
                        'type' => 'select',
                        'label' => 'Natureza',
                        'options' => [
                            '' => 'Natureza (Todos)',
                            '1' => 'Digital',
                            '0' => 'Físico'
                        ]
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
                    [
                        'name' => 'available',
                        'type' => 'select',
                        'label' => 'Disponibilidade',
                        'options' => [
                            '' => 'Disponibilidade (Todos)',
                            '1' => 'Disponível',
                            '0' => 'Indisponível'
                        ]
                    ]
                ]"
            />
        </div>

        <div id="assistive-technologies-table" class="p-3" role="region" aria-label="Listagem de tecnologias assistivas">
            @include('pages.assistive-technologies.partials.table')
        </div>
    </div>

    @push('scripts')
        @vite('resources/js/components/dynamicFilters.js')
    @endpush
@endsection
