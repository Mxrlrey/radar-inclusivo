@extends('layouts.master')

@section('title', 'Cadastrar - Material Pedagógico Acessível')

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Materiais Pedagógicos Acessíveis' => route('materiais-pedagogicos-acessiveis.index'),
                'Cadastrar' => null
            ]" />
            <h1>Novo Material Pedagógico Acessível</h1>
            <p class="text-muted mb-0">Cadastre materiais adaptados e realize a vistoria inicial.</p>
        </div>
        <div class="page-header-actions">
            <x-buttons.link-button
                :href="route('materiais-pedagogicos-acessiveis.index')"
                variant="secondary"
            >
                <x-slot:icon><i class="fa fa-times"></i></x-slot:icon>
                Cancelar
            </x-buttons.link-button>
        </div>
    </div>

    <x-forms.form-card
        action="{{ route('materiais-pedagogicos-acessiveis.salvar') }}"
        method="POST"
        enctype="multipart/form-data"
        class="form-horizontal"
    >
        <x-forms.section
            title="Identificação do Recurso"
            description="Informe os dados básicos do material pedagógico."
        />

        <x-forms.input
            name="name"
            label="Título do Material"
            required
            :horizontal="true"
            placeholder="Ex: Livro em Braille, Maquete Tátil..."
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
            title="Recursos de Acessibilidade"
            description="Selecione os recursos presentes no material."
        />

        <div class="form-group-horizontal mb-3">
            <label class="control-label">Recursos do Material</label>
            <div class="field-wrapper">
                <div class="d-flex flex-wrap gap-3 p-3 border checkbox-group-wrapper @error('accessibility_features') border-danger @enderror">
                    @foreach($accessibilityFeatures as $feature)
                        <x-forms.checkbox
                            name="accessibility_features[]"
                            id="feat_{{ $feature->id }}"
                            :value="$feature->id"
                            :label="$feature->name"
                            :checked="is_array(old('accessibility_features')) && in_array($feature->id, old('accessibility_features'))"
                        />
                    @endforeach
                </div>
                @error('accessibility_features')
                <small class="text-danger d-block mt-1">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <x-forms.separator />

        <x-forms.section
            title="Vistoria Inicial"
            description="Registre o estado do material no momento do cadastro."
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
            description="Configure disponibilidade e público-alvo do material."
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
            <label class="control-label">Público-Alvo <i class="text-danger">*</i></label>
            <div class="field-wrapper">
                <div class="d-flex flex-wrap gap-3 p-3 border checkbox-group-wrapper @error('deficiencies') border-danger @enderror">
                    @foreach($deficiencies as $def)
                        <x-forms.checkbox
                            name="deficiencies[]"
                            id="def_{{ $def->id }}"
                            :value="$def->id"
                            :label="$def->name"
                            :checked="is_array(old('deficiencies', $selectedDeficiencies ?? [])) && in_array($def->id, old('deficiencies', $selectedDeficiencies ?? []))"
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
                :href="route('materiais-pedagogicos-acessiveis.index')"
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

    @vite('resources/js/pages/accessible-educational-materials.js')
@endsection
