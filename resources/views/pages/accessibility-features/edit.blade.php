@extends('layouts.master')

@section('title', "Editar - $accessibilityFeature->name")

@section('content')
    <div class="mb-5">
        <x-breadcrumb :items="[
            'Home' => route('dashboard'),
            'Recursos de Acessibilidade' => route('accessibility-features.index'),
            $accessibilityFeature->name => route('accessibility-features.show', $accessibilityFeature),
            'Editar' => null
        ]" />
    </div>

    <div class="page-header">
        <div class="page-header-title">
            <h1>Editar Recurso de Acessibilidade</h1>
            <p class="text-muted">
                Atualizando informações de: <strong>{{ $accessibilityFeature->name }}</strong>
            </p>
        </div>
        <div class="page-header-actions">
            <x-buttons.link-button class="btn-action info" href="{{ route('accessibility-features.show', $accessibilityFeature) }}">
                <span class="btn-label"><i class="fa fa-times"></i></span> Cancelar
            </x-buttons.link-button>
        </div>
    </div>

    <x-forms.form-card action="{{ route('accessibility-features.update', $accessibilityFeature->id) }}" method="POST">
        @method('PUT')

        <x-forms.section title="Identificação do Recurso" />

        <div class="col-md-12">
            <x-forms.input
                name="name"
                label="Nome do Recurso"
                required
                :value="old('name', $accessibilityFeature->name)"
            />
        </div>

        <div class="col-md-12">
            <x-forms.textarea
                name="description"
                label="Descrição Detalhada"
                rows="4"
                :value="old('description', $accessibilityFeature->description)"
            />
        </div>

        <x-forms.section title="Configurações de Status" />

        <div class="col-md-6">
            <x-forms.checkbox
                name="is_active"
                label="Recurso Ativo"
                description="Define se este recurso está disponível para uso no sistema"
                :checked="old('is_active', $accessibilityFeature->is_active)"
            />
        </div>

        <x-forms.form-footer>
            <x-buttons.link-button href="{{ route('accessibility-features.show', $accessibilityFeature) }}" variant="secondary">
                <i class="fa fa-times"></i> Cancelar
            </x-buttons.link-button>

            <x-buttons.submit-button variant="new">
                <i class="fa fa-check"></i> Salvar
            </x-buttons.submit-button>
        </x-forms.form-footer>
    </x-forms.form-card>
@endsection
