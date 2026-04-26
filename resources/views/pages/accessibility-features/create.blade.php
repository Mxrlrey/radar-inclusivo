@extends('layouts.master')

@section('title', 'Cadastrar - Recurso de Acessibilidade')

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
            'Home' => route('dashboard'),
            'Recursos de Acessibilidade' => route('recursos-de-acessibilidade.index'),
            'Cadastrar' => null
        ]" />
            <h1>Novo Recurso de Acessibilidade</h1>
        </div>
        <div class="page-header-actions">
            <x-buttons.link-button :href="route('recursos-de-acessibilidade.index')" variant="secondary">
                <x-slot:icon><i class="fa fa-times" aria-hidden="true"></i></x-slot:icon>
                Cancelar
            </x-buttons.link-button>
        </div>
    </div>

    <x-forms.form-card
        action="{{ route('recursos-de-acessibilidade.salvar') }}"
        method="POST"
        class="form-horizontal"
    >
        <x-forms.section
            title="Identificação do Recurso"
            description="Informe os dados básicos do recurso de acessibilidade."
        />

        <x-forms.input
            name="name"
            label="Nome do Recurso"
            required
            :horizontal="true"
            placeholder="Ex: Intérprete de Libras, Piso Podotátil, etc."
            :value="old('name')"
        />

        <x-forms.textarea
            name="description"
            label="Descrição Detalhada"
            :horizontal="true"
            rows="4"
            :value="old('description')"
        />

        <x-forms.switch
            name="is_active"
            label="Recurso Ativo"
            :horizontal="true"
            :checked="old('is_active', true)"
        />

        <x-forms.form-footer>
            <x-buttons.link-button :href="route('recursos-de-acessibilidade.index')" variant="secondary">
                <x-slot:icon><i class="fa fa-times" aria-hidden="true"></i></x-slot:icon>
                Cancelar
            </x-buttons.link-button>

            <x-buttons.submit-button variant="new">
                <x-slot:icon><i class="fa fa-save" aria-hidden="true"></i></x-slot:icon>
                Cadastrar
            </x-buttons.submit-button>
        </x-forms.form-footer>
    </x-forms.form-card>
@endsection
