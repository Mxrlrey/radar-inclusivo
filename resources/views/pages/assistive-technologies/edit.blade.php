@extends('layouts.master')

@section('title', "Editar - $assistiveTechnology->name")

@section('content')

    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Tecnologias Assistivas' => route('tecnologias-assistivas.index'),
                $assistiveTechnology->name => route('tecnologias-assistivas.visualizar', $assistiveTechnology),
                'Editar' => null
            ]" />

            <h1>Editar Tecnologia Assistiva</h1>
            <p class="text-muted mb-0">
                Atualize os dados do recurso e registre uma nova vistoria.
            </p>
        </div>

        <div class="page-header-actions">
            <x-buttons.link-button :href="route('tecnologias-assistivas.visualizar', $assistiveTechnology)"
                variant="secondary"
            >
                <x-slot:icon><i class="fa fa-times"></i></x-slot:icon>
                Cancelar
            </x-buttons.link-button>
        </div>
    </div>

    <x-forms.form-card
        action="{{ route('tecnologias-assistivas.atualizar', $assistiveTechnology) }}"
        method="POST"
        enctype="multipart/form-data"
        class="form-horizontal"
    >
        @csrf
        @method('PUT')

        <x-forms.section
            title="Identificação do Recurso"
            description="Atualize os dados básicos da tecnologia assistiva."
        />

        <x-forms.input
            name="name"
            label="Tipo da Tecnologia"
            required
            :horizontal="true"
            :value="old('name', $assistiveTechnology->name)"
        />

        <x-forms.select
            name="is_digital"
            label="Natureza do Recurso"
            required
            :horizontal="true"
            :options="[0 => 'Recurso Físico', 1 => 'Recurso Digital']"
            :selected="old('is_digital', $assistiveTechnology->is_digital ? 1 : 0)"
        />

        <x-forms.input
            name="asset_code"
            label="Patrimônio / Tombamento"
            :horizontal="true"
            :value="old('asset_code', $assistiveTechnology->asset_code)"
            id="asset_code_container"
        />

        <x-forms.textarea
            name="notes"
            label="Descrição"
            :horizontal="true"
            rows="3"
            :value="old('notes', $assistiveTechnology->notes)"
        />

        <x-forms.separator />

        <x-forms.section
            title="Nova Vistoria"
            description="Registre uma nova inspeção da tecnologia."
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
            :selected="old('conservation_state', $assistiveTechnology->conservation_state?->value)"
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
            description="Atualize disponibilidade e público-alvo."
        />

        <x-forms.input
            name="quantity"
            label="Quantidade Total"
            type="number"
            :horizontal="true"
            :min="$activeLoans"
            :value="old('quantity', $assistiveTechnology->quantity)"
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
            :selected="old('status', $assistiveTechnology->status?->value)"
        />

        <x-forms.switch
            name="is_loanable"
            label="Permitir Empréstimos"
            :horizontal="true"
            :checked="old('is_loanable', $assistiveTechnology->is_loanable)"
        />

        <x-forms.switch
            name="is_active"
            label="Ativar no Sistema"
            :horizontal="true"
            :checked="old('is_active', $assistiveTechnology->is_active)"
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
                            :checked="in_array($def->id, old('deficiencies', $assistiveTechnology->deficiencies->pluck('id')->toArray()))"
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
                :href="route('tecnologias-assistivas.index', $assistiveTechnology)"
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
    @vite('resources/js/pages/assistive-technologies.js')
@endsection
