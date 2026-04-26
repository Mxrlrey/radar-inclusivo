@extends('layouts.master')

@section('title', $student->person->name)

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Alunos' => route('estudantes.index'),
                $student->person->name => null
            ]" />

            <h1>Detalhes do Aluno</h1>
            <p class="text-muted mb-0">
                Visualize informações cadastrais, acadêmicas e o status do estudante no sistema.
            </p>
        </div>

        <div class="page-header-actions">
            @can('student.update')
                <x-buttons.link-button
                    :href="route('estudantes.editar', $student)"
                    variant="info"
                >
                    <span class="btn-label"><i class="fa fa-pencil" aria-hidden="true"></i></span>
                    Editar
                </x-buttons.link-button>
            @endcan

            <x-buttons.link-button
                :href="route('estudantes.index')"
                variant="secondary"
            >
                <span class="btn-label"><i class="fa fa-arrow-left" aria-hidden="true"></i></span>
                Voltar
            </x-buttons.link-button>
        </div>
    </div>

    <div class="card-custom overflow-hidden show-container">
        <x-forms.section
            title="{{ $student->person->name }}"
            description="Visualize as informações de {{ $student->person->name }}"
        />

        <div class="d-flex flex-column align-items-center py-3 text-center">

            <div class="avatar-show-lg mx-auto">
                @if($student->person->photo)
                    <img
                        src="{{ $student->person->photo_url }}"
                        alt="Foto de {{ $student->person->name }}"
                    >
                @else
                    <i class="ion-android-contact mt-5" aria-hidden="true"></i>
                @endif
            </div>
        </div>

        <x-forms.separator />

        <x-forms.section title="Dados Pessoais" />

        <x-show.info-item label="Nome Completo">
            {{ $student->person->name }}
        </x-show.info-item>

        <x-show.info-item label="CPF / Documento">
            {{ $student->person->document_formatted ?? '---' }}
        </x-show.info-item>

        <x-show.info-item label="Matrícula">
            {{ $student->registration }}
        </x-show.info-item>

        <x-show.info-item label="E-mail">
            {{ $student->person->email ?? '---' }}
        </x-show.info-item>

        <x-show.info-item label="Data de Nascimento">
            {{ $student->person->birth_date?->format('d/m/Y') ?? '---' }}
        </x-show.info-item>

        <x-show.info-item label="Gênero">
            {{ $student->person->gender?->label() ?? '---' }}
        </x-show.info-item>

        <x-show.info-item label="Telefone">
            {{ $student->person->phone ?? '---' }}
        </x-show.info-item>

        <x-show.info-item label="Endereço">
            {!! $student->person->address ?: '---' !!}
        </x-show.info-item>

        <x-forms.separator />

        <x-forms.section title="Informações do Registro" />

        <x-show.info-item label="Data de Ingresso">
            {{ $student->entry_date?->format('d/m/Y') ?? '---' }}
        </x-show.info-item>

        <x-show.info-item label="ID">
            #{{ $student->id }}
        </x-show.info-item>

        <x-show.info-item label="Status no Sistema">
            <span class="badge bg-{{ $student->is_active ? 'success' : 'danger' }}">
                {{ $student->is_active ? 'Ativo' : 'Inativo' }}
            </span>
        </x-show.info-item>

        <x-show.info-item label="Cadastrado em">
            {{ $student->created_at?->format('d/m/Y H:i') ?? '---' }}
        </x-show.info-item>

        <x-show.info-item label="Atualizado em">
            {{ $student->updated_at?->format('d/m/Y H:i') ?? '---' }}
        </x-show.info-item>

        @php
            $modalId = "modal-delete-student-{$student->id}";
        @endphp

        <x-show.footer>
            <x-buttons.link-button
                :href="route('estudantes.index')"
                variant="secondary"
            >
                <span class="btn-label"><i class="fa fa-arrow-left" aria-hidden="true"></i></span>
                Voltar
            </x-buttons.link-button>

            @can('student.delete')
                <x-buttons.submit-button
                    variant="danger"
                    type="button"
                    label="Excluir aluno"
                    onclick="new bootstrap.Modal(document.getElementById('{{ $modalId }}')).show();"
                >
                    <span class="btn-label"><i class="fa fa-eraser" aria-hidden="true"></i></span>
                    Excluir
                </x-buttons.submit-button>
            @endcan
        </x-show.footer>
    </div>

    <x-modal.modal
        :id="$modalId"
        title="Confirmar Exclusão"
        size="sm"
    >
        <div class="p-3">
            <p class="mb-2 text-danger fw-bold">
                Esta ação não pode ser desfeita.
            </p>

            <p class="mb-0 text-muted">
                Deseja realmente excluir o aluno
                <strong>{{ $student->person->name }}</strong>?
            </p>
        </div>

        <x-slot:footer>
            <x-buttons.link-button
                variant="secondary"
                type="button"
                onclick="bootstrap.Modal.getInstance(this.closest('.modal')).hide()"
            >
                Cancelar
            </x-buttons.link-button>

            <form action="{{ route('estudantes.excluir', $student) }}" method="POST">
                @csrf
                @method('DELETE')

                <x-buttons.submit-button variant="danger" label="Confirmar exclusão do aluno">
                    Excluir
                </x-buttons.submit-button>
            </form>
        </x-slot:footer>
    </x-modal.modal>
@endsection
