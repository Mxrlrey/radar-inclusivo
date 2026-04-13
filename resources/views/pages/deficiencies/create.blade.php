@extends('layouts.master')

@section('title', 'Cadastrar - Deficiência')

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Deficiências' => route('deficiencies.index'),
                'Cadastrar' => null
            ]" />

            <h1>Nova Deficiência</h1>

            <p class="text-muted mb-0">
                Cadastre categorias de deficiência utilizadas no sistema de suporte especializado.
            </p>
        </div>

        <div class="page-header-actions">
            <x-buttons.link-button
                :href="route('deficiencies.index')"
                variant="secondary"
            >
                <x-slot:icon>
                    <i class="fa fa-times" aria-hidden="true"></i>
                </x-slot:icon>
                Cancelar
            </x-buttons.link-button>
        </div>
    </div>

    <x-forms.form-card
        action="{{ route('deficiencies.store') }}"
        method="POST"
        class="form-horizontal"
    >
        @csrf

        <x-forms.section
            title="Identificação da Deficiência"
            description="Informe os dados básicos da categoria de deficiência."
        />

        <x-forms.input
            name="name"
            label="Nome da Deficiência"
            required
            :horizontal="true"
            :value="old('name')"
            placeholder="Ex: Deficiência Intelectual"
        />

        <x-forms.input
            name="cid_code"
            label="Código CID"
            :horizontal="true"
            :value="old('cid_code')"
            placeholder="Ex: F84.0"
        />

        <x-forms.textarea
            name="description"
            label="Descrição"
            :horizontal="true"
            rows="3"
            :value="old('description')"
        />

        <x-forms.separator />

        <x-forms.section
            title="Status do Registro"
            description="Defina se a deficiência estará ativa no sistema."
        />

        <x-forms.switch
            name="is_active"
            label="Ativar no Sistema"
            :horizontal="true"
            :checked="old('is_active', true)"
        />

        <x-forms.form-footer>
            <x-buttons.link-button
                :href="route('deficiencies.index')"
                variant="secondary"
            >
                <x-slot:icon>
                    <i class="fa fa-times" aria-hidden="true"></i>
                </x-slot:icon>
                Cancelar
            </x-buttons.link-button>

            <x-buttons.submit-button variant="new">
                <x-slot:icon>
                    <i class="fa fa-save" aria-hidden="true"></i>
                </x-slot:icon>
                Cadastrar
            </x-buttons.submit-button>
        </x-forms.form-footer>
    </x-forms.form-card>
@endsection
