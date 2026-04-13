@extends('layouts.master')

@section('title', "Histórico - $material->name")

@section('content')
    <div class="mb-4">
        <x-breadcrumb :items="[
            'Home' => route('dashboard'),
            'Materiais Pedagógicos' => route('accessible-educational-materials.index'),
            $material->name => route('accessible-educational-materials.show', $material),
            'Histórico de Alterações' => null
        ]" />
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-title">Histórico de Alterações</h2>
            <p class="text-muted mb-1">
                Rastreabilidade de:
                <strong>{{ $material->name }}</strong>
            </p>

            <div class="d-flex align-items-center gap-2">
                <span class="text-muted small text-uppercase fw-bold">
                    Registros
                </span>
                <span class="badge text-bg-secondary">{{ $logs->total() }}</span>
            </div>
        </div>

        <div class="d-flex gap-2">
            <x-buttons.link-button
                href="{{ route('accessible-educational-materials.show', $material) }}"
                variant="secondary"
            >
                <span class="btn-label"><i class="fa fa-arrow-left"></i></span> Voltar
            </x-buttons.link-button>
        </div>
    </div>

    <x-logs.container :logs="$logs" />
    @vite('resources/js/effects/timeline-animation.js')
@endsection
