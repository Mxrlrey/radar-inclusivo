@extends('layouts.master')

@section('title', "Editar - $accessibilityFeature->name")

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Recursos de Acessibilidade' => route('recursos-de-acessibilidade.index'),
                $accessibilityFeature->name => route('recursos-de-acessibilidade.visualizar', $accessibilityFeature),
                'Editar' => null
            ]" />
            <h1>Editar Recurso de Acessibilidade</h1>
            <p class="text-muted mb-0">
                Atualizando informações de: <strong>{{ $accessibilityFeature->name }}</strong>
            </p>
        </div>
        <div class="page-header-actions">
            <x-buttons.link-button
                href="{{ route('recursos-de-acessibilidade.visualizar', $accessibilityFeature) }}"
                variant="secondary"
            >
                <x-slot:icon><i class="fa fa-times" aria-hidden="true"></i></x-slot:icon> Cancelar
            </x-buttons.link-button>
        </div>
    </div>

    <x-forms.form-card
        action="{{ route('recursos-de-acessibilidade.atualizar', $accessibilityFeature->id) }}"
        method="POST"
        class="form-horizontal"
    >
        @method('PUT')

        <x-forms.section
            title="Identificação do Recurso"
            description="Atualize os dados básicos do recurso de acessibilidade."
        />

        <x-forms.input
            name="name"
            label="Nome do Recurso"
            required
            :horizontal="true"
            :value="old('name', $accessibilityFeature->name)"
        />

        <x-forms.textarea
            name="description"
            label="Descrição Detalhada"
            :horizontal="true"
            rows="4"
            :value="old('description', $accessibilityFeature->description)"
        />

        <x-forms.switch
            name="is_active"
            label="Recurso Ativo"
            :horizontal="true"
            :checked="old('is_active', $accessibilityFeature->is_active)"
        />

        <x-forms.form-footer>
            <x-buttons.link-button
                href="{{ route('recursos-de-acessibilidade.visualizar', $accessibilityFeature) }}"
                variant="secondary"
            >
                <x-slot:icon><i class="fa fa-times" aria-hidden="true"></i></x-slot:icon> Cancelar
            </x-buttons.link-button>

            <x-buttons.submit-button variant="new">
                <x-slot:icon><i class="fa fa-save" aria-hidden="true"></i></x-slot:icon>
                Salvar
            </x-buttons.submit-button>
        </x-forms.form-footer>
    </x-forms.form-card>
@endsection
