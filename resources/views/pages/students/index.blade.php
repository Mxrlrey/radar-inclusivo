@extends('layouts.master')

@section('title', 'Alunos')

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Alunos' => null
            ]" />
            <h1>Alunos</h1>
            <p class="text-muted mb-0">
                Gerencie os estudantes e seus documentos de apoio especializado.
            </p>
        </div>

        <div class="page-header-actions">
            <x-buttons.link-button
                :href="route('estudantes.criar')"
                variant="info"
                aria-label="Cadastrar novo aluno"
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
                data-url="{{ route('estudantes.index') }}"
                data-target="#students-table"
                :fields="[
                    [
                        'name' => 'name',
                        'placeholder' => 'Filtrar por nome...',
                        'label' => 'Nome do aluno'
                    ],
                    [
                        'name' => 'email',
                        'placeholder' => 'Filtrar por e-mail...',
                        'label' => 'E-mail'
                    ],
                    [
                        'name' => 'registration',
                        'placeholder' => 'Filtrar por matrícula...',
                        'label' => 'Matrícula'
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

        <div id="students-table" class="p-3" role="region" aria-label="Listagem de alunos">
            @include('pages.students.partials.table')
        </div>
    </div>

    @push('scripts')
        @vite('resources/js/components/dynamicFilters.js')
    @endpush
@endsection
