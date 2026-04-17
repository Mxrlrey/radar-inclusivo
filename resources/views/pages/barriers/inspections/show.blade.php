@extends('layouts.master')

@section('title', "Inspeção - {$inspection->inspection_date->format('d/m/Y')}")

@section('content')

    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Barreiras' => route('barriers.index'),
                $barrier->name => route('barriers.show', $barrier),
                'Detalhes da Inspeção' => null
            ]" />

            <h1>Detalhes da Inspeção</h1>

            <p class="text-muted mb-0">
                Visualize o estado da barreira, tipo de inspeção, parecer técnico e evidências visuais.
            </p>
        </div>

        <div class="page-header-actions d-flex gap-2">
            <x-buttons.link-button
                :href="route('barriers.show', $barrier)"
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

        <x-show.info-item label="Status da Barreira">
            {{ $inspection->status?->label() ?? 'Identificada' }}
        </x-show.info-item>

        <x-show.info-item label="Data da Inspeção">
            {{ $inspection->inspection_date?->format('d/m/Y') ?? '---' }}
        </x-show.info-item>

        <x-show.info-item label="Tipo de Inspeção">
            {{ $inspection->type?->label() ?? '---' }}
        </x-show.info-item>

        <x-show.info-textarea
            label="Parecer Técnico / Descrição"
            :value="$inspection->description ?: 'Nenhum parecer técnico registrado.'"
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
                            alt="Evidência {{ $index + 1 }}"
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
            :backRoute="route('barriers.show', $barrier)"
        />
    </div>

@endsection
