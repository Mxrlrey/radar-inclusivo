@extends('layouts.master')

@section('title', "Editar - $barrierCategory->name")

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Categorias de Barreiras' => route('categorias-de-barreiras.index'),
                $barrierCategory->name => route('categorias-de-barreiras.visualizar', $barrierCategory),
                'Editar' => null
            ]" />

            <h1>Editar Categoria de Barreira</h1>
            <p class="text-muted mb-0">
                Atualize as definições da categoria <strong>{{ $barrierCategory->name }}</strong>.
            </p>
        </div>

        <div class="page-header-actions">
            <x-buttons.link-button
                :href="route('categorias-de-barreiras.visualizar', $barrierCategory)"
                variant="secondary"
            >
                <x-slot:icon><i class="fa fa-times" aria-hidden="true"></i></x-slot:icon>
                Cancelar
            </x-buttons.link-button>
        </div>
    </div>

    <x-forms.form-card
        action="{{ route('categorias-de-barreiras.atualizar', $barrierCategory) }}"
        method="POST"
        class="form-horizontal"
    >
        @method('PUT')

        <x-forms.section
            title="Informações da Categoria"
            description="Atualize os dados principais da categoria de barreira."
        />

        <x-forms.input
            name="name"
            label="Nome da Categoria"
            required
            :horizontal="true"
            :value="old('name', $barrierCategory->name)"
            placeholder="Ex: Arquitetônica, Atitudinal, Comunicacional..."
        />

        <x-forms.textarea
            name="description"
            label="Descrição Detalhada"
            :horizontal="true"
            rows="4"
            :value="old('description', $barrierCategory->description)"
            placeholder="Descreva o que este tipo de barreira engloba..."
        />

        <x-forms.separator />

        <x-forms.section
            title="Status e Visibilidade"
            description="Configure a disponibilidade da categoria no sistema."
        />

        <x-forms.switch
            name="is_active"
            label="Ativar no Sistema"
            description="Indica se esta categoria estará disponível para seleção no cadastro de novas barreiras."
            :horizontal="true"
            :checked="old('is_active', $barrierCategory->is_active)"
        />

        <x-forms.switch
            name="blocks_map"
            label="Bloquear Mapa"
            description="Quando ativo, oculta o mapa no cadastro de novas barreiras ao selecionar esta categoria."
            :horizontal="true"
            :checked="old('blocks_map', $barrierCategory->blocks_map)"
        />

        <x-forms.form-footer>
            <x-buttons.link-button
                :href="route('categorias-de-barreiras.visualizar', $barrierCategory)"
                variant="secondary"
            >
                <x-slot:icon><i class="fa fa-times" aria-hidden="true"></i></x-slot:icon>
                Cancelar
            </x-buttons.link-button>

            <x-buttons.submit-button variant="new">
                <x-slot:icon><i class="fa fa-save" aria-hidden="true"></i></x-slot:icon>
                Salvar
            </x-buttons.submit-button>
        </x-forms.form-footer>
    </x-forms.form-card>
@endsection
