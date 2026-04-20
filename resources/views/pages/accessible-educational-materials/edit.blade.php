@extends('layouts.master')

@section('title', "Editar - $material->name")

@section('content')

    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Materiais Pedagógicos Acessíveis' => route('materiais-pedagogicos-acessiveis.index'),
                $material->name => route('materiais-pedagogicos-acessiveis.visualizar', $material),
                'Editar' => null
            ]" />

            <h1>Editar Material Pedagógico Acessível</h1>
            <p class="text-muted mb-0">
                Atualize os dados do material e registre uma nova vistoria.
            </p>
        </div>

        <div class="page-header-actions">
            <x-buttons.link-button
                :href="route('materiais-pedagogicos-acessiveis.visualizar', $material)"
                variant="secondary"
            >
                <x-slot:icon><i class="fa fa-times"></i></x-slot:icon>
                Cancelar
            </x-buttons.link-button>
        </div>
    </div>

    <x-forms.form-card
        action="{{ route('materiais-pedagogicos-acessiveis.atualizar', $material) }}"
        method="POST"
        enctype="multipart/form-data"
        class="form-horizontal"
    >
        @csrf
        @method('PUT')

        <x-forms.section
            title="Identificação do Recurso"
            description="Atualize os dados básicos do material pedagógico."
        />

        <x-forms.input
            name="name"
            label="Título do Material"
            required
            :horizontal="true"
            :value="old('name', $material->name)"
        />

        <x-forms.select
            name="is_digital"
            label="Natureza do Recurso"
            required
            :horizontal="true"
            :options="[0 => 'Recurso Físico', 1 => 'Recurso Digital']"
            :selected="old('is_digital', $material->is_digital ? 1 : 0)"
        />

        <x-forms.input
            name="asset_code"
            label="Patrimônio / Tombamento"
            :horizontal="true"
            :value="old('asset_code', $material->asset_code)"
            id="asset_code_container"
        />

        <x-forms.textarea
            name="notes"
            label="Descrição"
            :horizontal="true"
            rows="3"
            :value="old('notes', $material->notes)"
        />

        <x-forms.separator />

        <x-forms.section
            title="Recursos de Acessibilidade"
            description="Atualize os recursos presentes no material."
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
                            :checked="in_array($feature->id, old('accessibility_features', $material->accessibilityFeatures->pluck('id')->toArray()))"
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
            title="Nova Vistoria"
            description="Registre uma nova inspeção do material."
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
            :selected="old('conservation_state', $material->conservation_state?->value)"
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
            placeholder="Descreva alterações ou detalhes da nova vistoria..."
            :value="old('inspection_description')"
        />

        <x-forms.separator />

        <x-forms.section
            title="Gestão e Público"
            description="Atualize disponibilidade e público-alvo."
        />

        <x-forms.input
            name="quantity"
            label="Quantidade Total"
            type="number"
            :horizontal="true"
            :min="$activeLoans"
            :value="old('quantity', $material->quantity)"
        />

        @if($activeLoans > 0)
            <div class="form-group-horizontal mb-3">
                <label class="control-label"></label>
                <div class="field-wrapper">
                    <div class="alert alert-warning py-2 mb-0">
                        <small class="fw-bold">
                            <i class="fas fa-lock"></i> {{ $activeLoans }} unidades em uso.
                        </small>
                    </div>
                </div>
            </div>
        @endif

        <x-forms.select
            name="status"
            label="Status do Recurso"
            :horizontal="true"
            :options="$resourceStatuses"
            :selected="old('status', $material->status?->value)"
        />

        <x-forms.switch
            name="is_loanable"
            label="Permitir Empréstimos"
            :horizontal="true"
            :checked="old('is_loanable', $material->is_loanable)"
        />

        <x-forms.switch
            name="is_active"
            label="Ativar no Sistema"
            :horizontal="true"
            :checked="old('is_active', $material->is_active)"
        />

        <div class="form-group-horizontal mb-3">
            <label class="control-label">Público-Alvo <i class="text-danger">*</i></label>
            <div class="field-wrapper">
                <div class="d-flex flex-wrap gap-4 p-3 border checkbox-group-wrapper @error('deficiencies') border-danger @enderror">
                    @foreach($deficiencies as $def)
                        <x-forms.checkbox
                            name="deficiencies[]"
                            id="def_{{ $def->id }}"
                            :value="$def->id"
                            :label="$def->name"
                            :checked="in_array($def->id, old('deficiencies', $material->deficiencies->pluck('id')->toArray()))"
                            class="mb-0"
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
                :href="route('materiais-pedagogicos-acessiveis.visualizar', $material)"
                variant="secondary"
            >
                <x-slot:icon><i class="fa fa-times"></i></x-slot:icon>
                Cancelar
            </x-buttons.link-button>

            <x-buttons.submit-button variant="new">
                <x-slot:icon><i class="fa fa-save"></i></x-slot:icon>
                Salvar
            </x-buttons.submit-button>
        </x-forms.form-footer>
    </x-forms.form-card>
    @vite('resources/js/pages/accessible-educational-materials.js')
@endsection
