@extends('layouts.master')

@section('title', "Editar - Empréstimo #$loan->id")

@section('content')
    @php
        $isReturned = $loan->status !== 'active' || $loan->return_date !== null;
    @endphp

    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Empréstimos' => route('emprestimos.index'),
                $loan->id => route('emprestimos.visualizar', $loan),
                'Editar' => null
            ]" />
            <h1>Editar Registro de Empréstimo</h1>
            <p class="text-muted mb-0">
                Atualize prazos, status ou registre a devolução do recurso.
            </p>
        </div>

        <div class="page-header-actions">
            <x-buttons.link-button :href="route('emprestimos.visualizar', $loan)" variant="secondary">
                <x-slot:icon><i class="fa fa-times" aria-hidden="true"></i></x-slot:icon>
                Cancelar
            </x-buttons.link-button>
        </div>
    </div>

    @if($isReturned)
        <div class="alert alert-info border-0 shadow-sm mb-4 d-flex align-items-center gap-3">
            <i class="fa fa-info-circle fs-4"></i>
            <div>
                <p class="mb-0 fw-bold">Este empréstimo já foi finalizado.</p>
                <small>Apenas o campo de observações pode ser atualizado nesta tela.</small>
            </div>
        </div>
    @endif

    @if($loan->status === 'active' && $loan->due_date?->isPast())
        <div class="alert alert-warning border-0 shadow-sm mb-4 d-flex align-items-center gap-3">
            <i class="fa fa-clock-o fs-4"></i>
            <div>
                <p class="mb-0 fw-bold">Atenção: Este item está com a devolução atrasada!</p>
                <small>O prazo encerrou em {{ $loan->due_date->format('d/m/Y') }}.</small>
            </div>
        </div>
    @endif

    <x-forms.form-card
        action="{{ route('emprestimos.atualizar', $loan) }}"
        method="POST"
        class="form-horizontal"
    >
        @method('PUT')

        <input type="hidden" name="loanable_id" value="{{ $loan->loanable_id }}">
        <input type="hidden" name="loanable_type" value="{{ $loan->loanable_type }}">

        <x-forms.section
            title="Informações do Empréstimo"
            description="Identificação do recurso, beneficiário e controle de prazos."
        />

        <x-forms.input
            name="item_type_display"
            label="Tipo do Recurso"
            :horizontal="true"
            :value="$loan->loanable_type === 'assistive_technology' ? 'Tecnologia Assistiva' : 'Material Pedagógico'"
            disabled
        />

        <x-forms.input
            name="item_display"
            label="Item"
            :horizontal="true"
            :value="$loan->loanable->name ?? ($loan->loanable->title ?? 'Item não identificado')"
            disabled
        />

        @if($loan->student_id)
            <x-forms.select
                name="student_id"
                label="Estudante"
                :horizontal="true"
                :options="$students"
                :selected="old('student_id', $loan->student_id)"
                :disabled="$isReturned"
            />
        @else
            <x-forms.select
                name="professional_id"
                label="Profissional"
                :horizontal="true"
                :options="$professionals"
                :selected="old('professional_id', $loan->professional_id)"
                :disabled="$isReturned"
            />
        @endif

        <x-forms.input
            name="user_id_display"
            label="Responsável Técnico"
            :horizontal="true"
            :value="$authUser->name"
            disabled
        />
        <input type="hidden" name="user_id" value="{{ $authUser->id }}">

        <x-forms.input
            name="loan_date"
            label="Data de Saída"
            type="datetime-local"
            :horizontal="true"
            readonly
            :value="old('loan_date', $loan->loan_date?->format('Y-m-d\TH:i'))"
        />

        <x-forms.input
            name="due_date"
            label="Previsão de Devolução"
            type="date"
            :horizontal="true"
            :value="old('due_date', $loan->due_date?->format('Y-m-d'))"
            :readonly="$isReturned"
        />

        <x-forms.input
            name="status_display"
            label="Status Atual"
            :horizontal="true"
            :value="$loan->status?->label() ?? 'Status desconhecido'"
            disabled
        />

        <x-forms.input
            name="return_date_display"
            label="Data Real da Devolução"
            :horizontal="true"
            :value="$loan->return_date ? $loan->return_date->format('d/m/Y H:i') : 'Ainda não devolvido'"
            disabled
        />

        <x-forms.textarea
            name="observation"
            label="Observações"
            :horizontal="true"
            rows="3"
            :value="old('observation', $loan->observation)"
        />

        <x-forms.form-footer>
            <x-buttons.link-button
                :href="route('emprestimos.visualizar', $loan)"
                variant="secondary"
            >
                <x-slot:icon><i class="fa fa-times" aria-hidden="true"></i></x-slot:icon>
                Cancelar
            </x-buttons.link-button>

            <x-buttons.submit-button variant="new">
                <x-slot:icon><i class="fa fa-save" aria-hidden="true"></i></x-slot:icon>
                Salvar
            </x-buttons.submit-button>
        </x-forms.form-footer>
    </x-forms.form-card>

    @push('scripts')
        @vite('resources/js/pages/loans.js')
    @endpush
@endsection
