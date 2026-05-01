@extends('layouts.master')

@section('title', 'Empréstimos')

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Empréstimos' => null
            ]" />
            <h1>Empréstimos de Recursos</h1>
            <p class="text-muted mb-0">
                Controle de saídas e devoluções de tecnologias e materiais pedagógicos.
            </p>
        </div>

        <div class="page-header-actions">
            <x-buttons.link-button
                :href="route('emprestimos.criar')"
                variant="info"
                aria-label="Cadastrar novo empréstimo"
            >
                <span class="btn-label"><i class="fa fa-plus" aria-hidden="true"></i></span>
                Cadastrar
            </x-buttons.link-button>
        </div>
    </div>

    <div class="card-custom overflow-hidden">
        <div class="px-3 pt-3">
            <x-table.filters.form
                data-url="{{ route('emprestimos.index') }}"
                data-target="#loans-table"
                :fields="[
                    [
                        'name' => 'item',
                        'label' => 'Item'
                    ],
                    [
                        'name' => 'student',
                        'label' => 'Aluno'
                    ],
                    [
                        'name' => 'professional',
                        'label' => 'Profissional'
                    ],
                    [
                        'name' => 'status',
                        'type' => 'select',
                        'label' => 'Status',
                        'options' => [
                            ''         => 'Status (Todos)',
                            'active'   => 'Ativo (Com o Beneficiário)',
                            'returned' => 'Devolvido (No prazo)',
                            'late'     => 'Devolvido (Com atraso)',
                            'damaged'  => 'Devolvido (Com avaria)',
                        ]
                    ]
                ]"
            />
        </div>

        <div id="loans-table" class="p-3" role="region" aria-label="Listagem de empréstimos">
            @include('pages.loans.partials.table')
        </div>
    </div>

    @push('scripts')
        @vite('resources/js/components/dynamicFilters.js')
    @endpush
@endsection
