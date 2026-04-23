@extends('layouts.master')

@section('title', "Editar - {$deficiency->name}")

@section('content')

    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Deficiências' => route('deficiencias.index'),
                $deficiency->name => route('deficiencias.visualizar', $deficiency),
                'Editar' => null
            ]" />

            <h1>Editar Deficiência</h1>

            <p class="text-muted mb-0">
                Atualize os dados da deficiência no sistema.
            </p>
        </div>

        <div class="page-header-actions">
            <x-buttons.link-button
                :href="route('deficiencias.visualizar', $deficiency)"
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
        action="{{ route('deficiencias.atualizar', $deficiency) }}"
        method="POST"
        class="form-horizontal"
    >
        @csrf
        @method('PUT')

        <x-forms.section
            title="Identificação da Deficiência"
            description="Atualize os dados principais da categoria."
        />

        <x-forms.input
            name="name"
            label="Nome da Deficiência"
            required
            :horizontal="true"
            :value="old('name', $deficiency->name)"
        />

        <x-forms.input
            name="cid_code"
            label="Código CID"
            :horizontal="true"
            :value="old('cid_code', $deficiency->cid_code)"
            placeholder="Ex: F84.0"
        />

        <x-forms.textarea
            name="description"
            label="Descrição"
            :horizontal="true"
            rows="3"
            :value="old('description', $deficiency->description)"
        />

        <x-forms.separator />

        <x-forms.section
            title="Status do Registro"
            description="Controle a disponibilidade desta deficiência no sistema."
        />

        <x-forms.switch
            name="is_active"
            label="Ativar no Sistema"
            :horizontal="true"
            :checked="old('is_active', $deficiency->is_active)"
        />

        <x-forms.form-footer>
            <x-buttons.link-button
                :href="route('deficiencias.visualizar', $deficiency)"
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
                Salvar
            </x-buttons.submit-button>
        </x-forms.form-footer>
    </x-forms.form-card>
@endsection
