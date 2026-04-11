@extends('layouts.master')

@section('title', 'Cadastrar - Recurso de Acessibilidade')

@section('content')
    <div class="mb-5">
        <x-breadcrumb :items="[
            'Home' => route('dashboard'),
            'Recursos de Acessibilidade' => route('accessibility-features.index'),
            'Cadastrar' => null
        ]" />
    </div>

    <div class="page-header">
        <div class="page-header-title">
            <h1>Novo Recurso de Acessibilidade</h1>
            <p class="text-muted">Defina uma nova categoria de acessibilidade.</p>
        </div>
        <div class="page-header-actions">
            <x-buttons.link-button
                :href="route('accessibility-features.index')"
                variant="primary"
            >
                <x-slot:icon>
                    <i class="fa fa-times"></i>
                </x-slot:icon>
                Cancelar
            </x-buttons.link-button>
        </div>
    </div>

    <x-forms.form-card action="{{ route('accessibility-features.store') }}" method="POST">
        <x-forms.section
            title="Identificação do Recurso"
            description="Informe os dados básicos do recurso de acessibilidade."
        />

        <x-forms.separator />

        <div class="col-md-12">
            <x-forms.input
                name="name"
                label="Nome do Recurso"
                required
                placeholder="Ex: Intérprete de Libras, Piso Podotátil, etc."
                :value="old('name')"
            />
        </div>

        <div class="col-md-12">
            <x-forms.textarea
                name="description"
                label="Descrição Detalhada"
                rows="4"
                :value="old('description')"
            />
        </div>

        <x-forms.separator />

        <x-forms.section
            title="Configurações de Status"
            description="Defina o status do recurso, indicando se ele estará ativo para utilização no sistema."
        />

        <x-forms.switch
            name="is_active"
            label="Recurso Ativo"
            :checked="old('is_active', true)"
        />

        <x-forms.form-footer>
            <x-buttons.link-button href="{{ route('accessibility-features.index') }}" variant="secondary">
                <i class="fa fa-times"></i> Cancelar
            </x-buttons.link-button>

            <x-buttons.submit-button variant="new">
                Cadastrar
            </x-buttons.submit-button>
        </x-forms.form-footer>
    </x-forms.form-card>
@endsection
