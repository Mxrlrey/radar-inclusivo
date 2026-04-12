@extends('layouts.master')

@section('title', "$feature->name")

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Recursos de Acessibilidade' => route('accessibility-features.index'),
                $feature->name => null
            ]" />
            <h1>Detalhes do Recurso de Acessibilidade</h1>
            <p class="text-muted mb-0">

            </p>
        </div>
    </div>

    <div class="card-custom overflow-hidden show-container">

        <x-forms.section
            title="{{ $feature->name }}"
            description="Visualize as informações de {{ $feature->name }}"
        />

        <x-show.info-item label="Nome do Recurso" :value="$feature->name" />

        <x-show.info-item label="Descrição">
            {!! $feature->description ?: '---' !!}
        </x-show.info-item>

        <x-show.info-item label="Recurso Ativo">
            <span class="badge bg-{{ $feature->is_active ? 'success' : 'danger' }}">
                {{ $feature->is_active ? 'Ativo' : 'Inativo' }}
            </span>
        </x-show.info-item>

        <x-show.info-item label="ID no Sistema" :value="'#' . $feature->id" />

        <x-show.footer
            :deleteRoute="route('accessibility-features.destroy', $feature)"
            :editRoute="route('accessibility-features.edit', $feature)"
            :backRoute="route('accessibility-features.index')"
        />
    </div>
@endsection
