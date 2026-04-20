@extends('layouts.master')

@section('title', 'Barreiras')

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Barreiras' => null
            ]" />

            <h1>Barreiras</h1>

            <p class="text-muted mb-0">
                Mapeamento de obstáculos relatados pela comunidade para melhoria da acessibilidade.
            </p>
        </div>

        <div class="page-header-actions">
            <x-buttons.link-button
                :href="route('barreiras.criar')"
                variant="info"
                aria-label="Cadastrar nova barreira"
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
                data-url="{{ route('barreiras.index') }}"
                data-target="#barriers-table"
                :fields="[
                    [
                        'name' => 'name',
                        'placeholder' => 'Filtrar por nome...'
                    ],
                    [
                        'name' => 'category',
                        'placeholder' => 'Filtrar por categoria...'
                    ],
                    [
                        'name' => 'priority',
                        'type' => 'select',
                        'placeholder' => 'Prioridade',
                        'options' => collect(\App\Enums\Priority::cases())
                            ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
                            ->prepend('Prioridade (Todas)', '')
                            ->toArray()
                    ],
                    [
                        'name' => 'status',
                        'type' => 'select',
                        'placeholder' => 'Status',
                        'options' => collect(\App\Enums\BarrierStatus::cases())
                            ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
                            ->prepend('Status (Todos)', '')
                            ->toArray()
                    ]
                ]"
            />
        </div>

        <div id="barriers-table" class="p-3" role="region" aria-label="Listagem de barreiras">
            @include('pages.barriers.partials.table')
        </div>
    </div>

    @push('scripts')
        @vite('resources/js/components/dynamicFilters.js')
    @endpush
@endsection
