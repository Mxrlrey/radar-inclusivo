@extends('layouts.master')

@section('title', 'Cadastrar - Tecnologia Assistiva')

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Tecnologias Assistivas' => route('tecnologias-assistivas.index'),
                'Cadastrar' => null
            ]" />
            <h1>Nova Tecnologia Assistiva</h1>
            <p class="text-muted mb-0">
                Cadastre novos recursos institucionais e realize a vistoria inicial.
            </p>
        </div>

        <div class="page-header-actions">
            <x-buttons.link-button
                :href="route('tecnologias-assistivas.index')"
                variant="secondary"
            >
                <x-slot:icon><i class="fa fa-times"></i></x-slot:icon>
                Cancelar
            </x-buttons.link-button>
        </div>
    </div>

    <x-forms.form-card
        action="{{ route('tecnologias-assistivas.salvar') }}"
        method="POST"
        enctype="multipart/form-data"
        class="form-horizontal"
    >
        @csrf

        <x-forms.section
            title="Identificação do Recurso"
            description="Informe os dados básicos da tecnologia assistiva."
        />

        <x-forms.input
            name="name"
            label="Tipo da Tecnologia"
            required
            :horizontal="true"
            placeholder="Ex: Cadeira de Rodas Motorizada"
            :value="old('name')"
        />

        <x-forms.select
            name="is_digital"
            label="Natureza do Recurso"
            required
            :horizontal="true"
            :options="[0 => 'Recurso Físico', 1 => 'Recurso Digital']"
            :selected="old('is_digital', 0)"
        />

        <x-forms.input
            name="asset_code"
            label="Patrimônio / Tombamento"
            :horizontal="true"
            :value="old('asset_code')"
            id="asset_code_container"
        />

        <x-forms.textarea
            name="notes"
            label="Descrição"
            :horizontal="true"
            rows="3"
            :value="old('notes')"
        />

        <x-forms.separator />

        <x-forms.section
            title="Vistoria Inicial"
            description="Registre o estado do item no momento do cadastro."
        />

        <x-forms.select
            name="inspection_type"
            label="Tipo de Inspeção"
            required
            :horizontal="true"
            :options="$inspectionTypes"
            :selected="old('inspection_type', $defaultInspection)"
        />

        <x-forms.input
            name="inspection_date"
            label="Data da Inspeção"
            type="date"
            required
            :horizontal="true"
            :value="old('inspection_date', date('Y-m-d'))"
        />

        <x-forms.select
            name="conservation_state"
            label="Estado de Conservação"
            required
            :horizontal="true"
            :options="$conservationStates"
            :selected="old('conservation_state')"
        />

        <x-forms.image-uploader
            name="images[]"
            label="Fotos de Evidência"
            :horizontal="true"
        />

        <x-forms.textarea
            name="inspection_description"
            label="Parecer Técnico"
            :horizontal="true"
            rows="3"
            :value="old('inspection_description')"
        />

        <x-forms.separator />

        <x-forms.section
            title="Gestão e Público"
            description="Configure disponibilidade e público-alvo da tecnologia."
        />

        <x-forms.input
            name="quantity"
            label="Quantidade Total"
            type="number"
            :horizontal="true"
            min="1"
            :value="old('quantity', 1)"
        />

        <x-forms.select
            name="status"
            label="Status do Recurso"
            :horizontal="true"
            :options="$resourceStatuses"
            :selected="old('status', $defaultStatus)"
        />

        <x-forms.switch
            name="is_loanable"
            label="Permitir Empréstimos"
            :horizontal="true"
            :checked="old('is_loanable', true)"
        />

        <x-forms.switch
            name="is_active"
            label="Ativar no Sistema"
            :horizontal="true"
            :checked="old('is_active', true)"
        />

        <div class="form-group-horizontal mb-3">
            <label class="control-label">Público-Alvo</label>
            <div class="field-wrapper">
                <div class="d-flex flex-wrap gap-3 p-3 border checkbox-group-wrapper @error('deficiencies') border-danger @enderror">
                    @foreach($deficiencies as $def)
                        <x-forms.checkbox
                            name="deficiencies[]"
                            id="def_{{ $def->id }}"
                            :value="$def->id"
                            :label="$def->name"
                            :checked="is_array(old('deficiencies')) && in_array($def->id, old('deficiencies'))"
                        />
                    @endforeach
                </div>

                @error('deficiencies')
                <small class="text-danger d-block mt-1">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <x-forms.form-footer>
            <x-buttons.link-button
                :href="route('tecnologias-assistivas.index')"
                variant="secondary"
            >
                <x-slot:icon><i class="fa fa-times"></i></x-slot:icon>
                Cancelar
            </x-buttons.link-button>

            <x-buttons.submit-button variant="new">
                <x-slot:icon><i class="fa fa-save"></i></x-slot:icon>
                Cadastrar
            </x-buttons.submit-button>
        </x-forms.form-footer>
    </x-forms.form-card>
    @vite('resources/js/pages/assistive-technologies.js')
@endsection
