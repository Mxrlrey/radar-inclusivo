@extends('layouts.master')

@section('title', "Empréstimo #$loan->id")

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Empréstimos' => route('emprestimos.index'),
                'Detalhes' => null
            ]" />

            <h1>Detalhes do Empréstimo</h1>
            <p class="text-muted mb-0">
                Visualize as informações, prazos e histórico do recurso emprestado.
            </p>
        </div>

        <div class="page-header-actions">
            <x-buttons.link-button
                :href="route('emprestimos.editar', $loan)"
                variant="info">
                <span class="btn-label" aria-hidden="true"><i class="fa fa-pencil"></i></span> Editar
            </x-buttons.link-button>

            <x-buttons.link-button
                :href="route('emprestimos.index')"
                variant="secondary">
                <span class="btn-label" aria-hidden="true"><i class="fa fa-arrow-left"></i></span> Voltar
            </x-buttons.link-button>
        </div>
    </div>

    @if($isOverdue)
        <div class="alert alert-warning border-0 shadow-sm mb-4 d-flex align-items-center gap-3">
            <i class="fa fa-clock-o fs-4" aria-hidden="true"></i>
            <div>
                <p class="mb-0 fw-bold">Atenção: Este item está com a devolução atrasada!</p>
                <small>O prazo encerrou em {{ $loan->due_date->format('d/m/Y') }}.</small>
            </div>
        </div>
    @endif

    <div class="card-custom overflow-hidden show-container">

        <x-forms.section
            title="Geral do Empréstimo"
            description="Dados do item vinculado a este registro."
        />

        <x-show.info-item label="Item" :value="$loan->loanable->name ?? ($loan->loanable->title ?? 'Item não identificado')" />

        <x-show.info-item label="Patrimônio / Tombamento" :value="$loan->loanable->asset_code ?? 'Não informado'" />

        @if($loan->student)
            <x-show.info-item label="Estudante (Beneficiário)" :value="$loan->student->person->name" />
            <x-show.info-item label="Matrícula" :value="$loan->student->registration" />
        @elseif($loan->professional)
            <x-show.info-item label="Profissional (Beneficiário)" :value="$loan->professional->person->name" />
        @endif

        <x-show.info-item label="Usuário Responsável" :value="$loan->user->name ?? '---'" />

        <x-show.info-item label="Data de Saída" :value="$loan->loan_date->format('d/m/Y H:i')" />

        <x-show.info-item label="Previsão de Devolução">
            <span class="{{ $isOverdue ? 'text-danger fw-bold' : '' }}">
                {{ $loan->due_date->format('d/m/Y') }}
            </span>
        </x-show.info-item>

        <x-show.info-item label="Status Atual">
            <span class="badge bg-{{ $statusColor }}">
                {{ $statusLabel }}
            </span>
        </x-show.info-item>

        <x-show.info-item label="Data Real da Devolução" :value="$loan->return_date?->format('d/m/Y H:i') ?? 'Ainda não devolvido'" />

        <x-show.info-item label="Observações">
            {!! $loan->observation ?: '<span class="text-muted">Nenhuma observação registrada.</span>' !!}
        </x-show.info-item>

        @can('system.audit.view')
            <x-forms.separator/>

            <x-forms.section title="Informações do Registro" />
            <x-show.info-item label="ID" :value="'#' . $loan->id" />
            <x-show.info-item label="Status no Sistema">
                <span class="badge bg-{{ $statusColor }}">
                    {{ $statusLabel }}
                </span>
            </x-show.info-item>
            <x-show.info-item label="Cadastrado em" :value="$loan->created_at?->format('d/m/Y H:i')" />
            <x-show.info-item label="Atualizado em" :value="$loan->updated_at?->format('d/m/Y H:i')" />
        @endcan

        @php
            $modalDeleteId = "modal-delete-loan-" . $loan->id;
            $modalReturnId = "modal-return-loan-" . $loan->id;
        @endphp

        <x-show.footer>
            <x-buttons.link-button
                :href="route('emprestimos.index')"
                variant="secondary"
            >
                <span class="btn-label" aria-hidden="true"><i class="fa fa-arrow-left"></i></span>
                Voltar
            </x-buttons.link-button>

            <x-buttons.link-button
                :href="route('emprestimos.pdf', $loan)"
                variant="danger"
            >
                <span class="btn-label" aria-hidden="true"><i class="fa fa-file-pdf-o"></i></span>
                PDF
            </x-buttons.link-button>

            @if($loan->status->value === 'active')
                <x-buttons.submit-button
                    variant="success"
                    type="button"
                    label="Registrar devolução do empréstimo"
                    onclick="new bootstrap.Modal(document.getElementById('{{ $modalReturnId }}')).show();"
                >
                    <span class="btn-label" aria-hidden="true"><i class="fa fa-undo"></i></span>
                    Devolver
                </x-buttons.submit-button>
            @endif

            <x-buttons.submit-button
                variant="danger"
                type="button"
                label="Excluir empréstimo"
                onclick="new bootstrap.Modal(document.getElementById('{{ $modalDeleteId }}')).show();"
            >
                <span class="btn-label" aria-hidden="true"><i class="fa fa-eraser"></i></span>
                Excluir
            </x-buttons.submit-button>
        </x-show.footer>
    </div>

    <x-modal.modal :id="$modalReturnId" title="Confirmar Devolução" size="sm">
        <form action="{{ route('emprestimos.devolver', $loan) }}" method="POST" id="form-return-{{ $loan->id }}">
            @csrf
            @method('PATCH')
            <div class="p-3">
                <p>Deseja confirmar a devolução deste recurso ao acervo?</p>
                <x-forms.checkbox name="is_damaged" label="Item devolvido com avarias ou danos" />
            </div>
        </form>
        <x-slot:footer>
            <x-buttons.link-button variant="secondary" type="button" onclick="bootstrap.Modal.getInstance(this.closest('.modal')).hide()">
                Cancelar
            </x-buttons.link-button>
            <x-buttons.submit-button variant="success" label="Confirmar devolução do empréstimo" onclick="document.getElementById('form-return-{{ $loan->id }}').submit()">
                Devolver
            </x-buttons.submit-button>
        </x-slot:footer>
    </x-modal.modal>

    <x-modal.modal :id="$modalDeleteId" title="Confirmar Exclusão" size="sm">
        <div class="p-3">
            <p class="mb-2 text-danger fw-bold">Esta ação não pode ser desfeita.</p>
            <p class="mb-0 text-muted">Deseja realmente excluir o registro de empréstimo <strong>#{{ $loan->id }}</strong>?</p>
        </div>
        <x-slot:footer>
            <x-buttons.link-button variant="secondary" type="button" onclick="bootstrap.Modal.getInstance(this.closest('.modal')).hide()">
                Cancelar
            </x-buttons.link-button>
            <form action="{{ route('emprestimos.excluir', $loan) }}" method="POST">
                @csrf
                @method('DELETE')
                <x-buttons.submit-button variant="danger" label="Confirmar exclusão do empréstimo">Excluir</x-buttons.submit-button>
            </form>
        </x-slot:footer>
    </x-modal.modal>
@endsection
