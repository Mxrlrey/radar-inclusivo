@extends('layouts.master')

@section('title', "Inspeção - {$inspection->inspection_date->format('d/m/Y')}")

@section('content')

    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Tecnologias Assistivas' => route('tecnologias-assistivas.index'),
                $assistiveTechnology->name => route('tecnologias-assistivas.visualizar', $assistiveTechnology),
                'Detalhes da Inspeção' => null
            ]" />

            <h1>Detalhes da Inspeção</h1>

            <p class="text-muted mb-0">
                Visualize o estado de conservação, tipo de inspeção, parecer técnico e evidências visuais.
            </p>
        </div>

        <div class="page-header-actions">
            <x-buttons.link-button
                :href="route('tecnologias-assistivas.visualizar', $assistiveTechnology)"
                variant="secondary"
            >
                <x-slot:icon>
                    <i class="fa fa-arrow-left"></i>
                </x-slot:icon>
                Voltar
            </x-buttons.link-button>
        </div>
    </div>

    <div class="card-custom show-container">

        <x-forms.section
            title="Informações Gerais"
            description="Dados principais da inspeção realizada."
        />

        <x-show.info-item label="Estado de Conservação">
            {{ $inspection->conservation_state?->label() ?? $inspection->state?->label() ?? '---' }}
        </x-show.info-item>

        <x-show.info-item label="Tipo de Inspeção">
            {{ $inspection->inspection_type?->label() ?? $inspection->type?->label() ?? '---' }}
        </x-show.info-item>

        <x-show.info-textarea
            label="Parecer Técnico / Descrição"
            :value="($inspection->inspection_description ?? $inspection->description) ?: 'Nenhum parecer técnico registrado.'"
            :rich="true"
        />

        @can('system.audit.view')
            <x-forms.section
                title="Registro do Sistema"
                description="Informações automáticas de auditoria do sistema."
            />

            <x-show.info-item label="ID no Sistema">
                #{{ $inspection->id }}
            </x-show.info-item>

            <x-show.info-item label="Criado em">
                {{ $inspection->created_at?->format('d/m/Y \à\s H:i') ?? '---' }}
            </x-show.info-item>

            <x-show.info-item label="Última atualização">
                {{ $inspection->updated_at?->format('d/m/Y \à\s H:i') ?? '---' }}
            </x-show.info-item>
        @endcan

        <x-forms.section
            title="Evidências Visuais"
            description="Imagens registradas durante a inspeção."
        />

        @if($inspection->images && $inspection->images->count() > 0)
            <div class="inspection-gallery">
                @foreach($inspection->images as $index => $img)
                    <a href="{{ asset('storage/' . $img->path) }}"
                       target="_blank"
                       class="inspection-gallery__item">

                        <img
                            src="{{ asset('storage/' . $img->path) }}"
                            alt="Foto de evidência {{ $index + 1 }}"
                            @if($loop->first) fetchpriority="high" loading="eager" @else loading="lazy" @endif
                        >
                    </a>
                @endforeach
            </div>
        @else
            <div class="inspection-empty">
                <i class="fa fa-camera"></i>
                <span>Nenhuma evidência visual registrada para esta inspeção.</span>
            </div>
        @endif

        <x-show.footer
            :backRoute="route('tecnologias-assistivas.visualizar', $assistiveTechnology)"
        />
    </div>
@endsection
