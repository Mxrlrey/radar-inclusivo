@extends('layouts.master')

@section('title', "$feature->name")

@section('content')
    <div class="mb-5">
        <x-breadcrumb :items="[
            'Home' => route('dashboard'),
            'Recursos de Acessibilidade' => route('accessibility-features.index'),
            $feature->name => null
        ]" />
    </div>

    <div class="page-header">
        <div class="page-header-title">
            <h1>Detalhes do Recurso de Acessibilidade</h1>
            <p class="text-muted">
                Visualize as informações cadastrais e status do recurso: <strong>{{ $feature->name }}</strong>
            </p>
        </div>
        <div class="page-header-actions">
            <x-buttons.link-button :href="route('accessibility-features.edit', $feature)" variant="warning">
                <i class="fa fa-pencil"></i> Editar
            </x-buttons.link-button>
            <x-buttons.link-button :href="route('accessibility-features.index')" variant="secondary">
                <i class="fa fa-arrow-left"></i> Voltar
            </x-buttons.link-button>
        </div>
    </div>

    <div class="card-custom">
        <x-forms.section title="Identificação do Recurso" />

        <div class="row g-3">
            <x-show.info-item label="Nome do Recurso" column="col-md-12" isBox="true">
                <strong>{{ $feature->name }}</strong>
            </x-show.info-item>

            <x-show.info-textarea label="Descrição Detalhada" column="col-md-12" :value="$feature->description ?: '---'" :rich="true"/>
        </div>

        <x-forms.section title="Configurações de Status" />

        <div class="row g-3">
            <x-show.info-item label="Recurso Ativo" column="col-md-12" isBox="true">
                {{ $feature->is_active ? 'Sim' : 'Não' }}
            </x-show.info-item>
        </div>

        <x-forms.form-footer>
            <x-slot:leftContent>
                <i class="fa fa-id-card me-1" aria-hidden="true"></i> ID no Sistema: #{{ $feature->id }}
            </x-slot:leftContent>

            <form action="{{ route('accessibility-features.destroy', $feature) }}"
                  method="POST"
                  onsubmit="return confirm('Deseja excluir permanentemente?')">
                @csrf
                @method('DELETE')
                <x-buttons.submit-button variant="danger">
                    <i class="fa fa-eraser"></i> Excluir
                </x-buttons.submit-button>
            </form>

            <x-buttons.link-button :href="route('accessibility-features.index')" variant="secondary">
                <i class="fa fa-arrow-left"></i> Voltar
            </x-buttons.link-button>
        </x-forms.form-footer>
    </div>
@endsection
