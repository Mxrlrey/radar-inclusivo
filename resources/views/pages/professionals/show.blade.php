@extends('layouts.master')

@section('title', $professional->person->name)

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Profissionais' => route('profissionais.index'),
                $professional->person->name => null
            ]" />

            <h1>Detalhes do Profissional</h1>
            <p class="text-muted mb-0">
                Visualize informações cadastrais, funcionais e o status do profissional no sistema.
            </p>
        </div>

        <div class="page-header-actions">
            @can('professional.update')
                <x-buttons.link-button
                    :href="route('profissionais.editar', $professional)"
                    variant="info"
                >
                    <span class="btn-label">
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                    </span>
                    Editar
                </x-buttons.link-button>
            @endcan

            <x-buttons.link-button
                :href="route('profissionais.index')"
                variant="secondary"
            >
                <span class="btn-label">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                </span>
                Voltar
            </x-buttons.link-button>
        </div>
    </div>

    <div class="card-custom overflow-hidden show-container">
        <x-forms.section
            title="{{ $professional->person->name }}"
            description="Visualize as informações de {{ $professional->person->name }}"
        />

        <div class="d-flex flex-column align-items-center py-3 text-center">
            <div class="avatar-show-lg mx-auto">
                @if($professional->person->photo)
                    <img
                        src="{{ $professional->person->photo_url }}"
                        alt="Foto de {{ $professional->person->name }}"
                    >
                @else
                    <i class="ion-android-social-user mt-5" aria-hidden="true"></i>
                @endif
            </div>
        </div>

        <x-forms.separator />

        <x-forms.section title="Dados Pessoais" />

        <x-show.info-item label="Nome Completo">
            {{ $professional->person->name }}
        </x-show.info-item>

        <x-show.info-item label="CPF">
            {{ $professional->person->document_formatted ?? '---' }}
        </x-show.info-item>

        <x-show.info-item label="Matrícula">
            {{ $professional->registration }}
        </x-show.info-item>

        <x-show.info-item label="E-mail">
            {{ $professional->person->email ?? '---' }}
        </x-show.info-item>

        <x-show.info-item label="Cargo / Função">
            {{ $professional->position->name ?? 'Não definido' }}
        </x-show.info-item>

        <x-show.info-item label="Data de Nascimento">
            {{ $professional->person->birth_date?->format('d/m/Y') ?? '---' }}
        </x-show.info-item>

        <x-show.info-item label="Gênero">
            {{ $professional->person->gender?->label() ?? '---' }}
        </x-show.info-item>

        <x-show.info-item label="Telefone">
            {{ $professional->person->phone ?? '---' }}
        </x-show.info-item>

        <x-show.info-item label="Endereço">
            {!! $professional->person->address ?: '---' !!}
        </x-show.info-item>

        <x-show.info-item label="Data de Ingresso">
            {{ $professional->entry_date?->format('d/m/Y') ?? '---' }}
        </x-show.info-item>

        @can('system.audit.view')
            <x-forms.section title="Informações do Registro" />

            <x-show.info-item label="Tempo de Instituição">
                {{ $professional->entry_date
                    ? $professional->entry_date->diffForHumans(['parts' => 2])
                    : '---' }}
            </x-show.info-item>

            <x-show.info-item label="Status no Sistema">
            <span class="badge bg-{{ $professional->is_active ? 'success' : 'danger' }}">
                {{ $professional->is_active ? 'Ativo' : 'Inativo' }}
            </span>
            </x-show.info-item>

            @if(auth()->check() && auth()->user()->isAdmin())
                <x-show.info-item label="Administrador do Sistema">
                <span class="badge bg-{{ optional($professional->user)->is_admin ? 'success' : 'danger' }}">
                    {{ optional($professional->user)->is_admin ? 'Sim' : 'Não' }}
                </span>
                </x-show.info-item>
            @endif

            <x-show.info-item label="ID">
                #{{ $professional->id }}
            </x-show.info-item>

            <x-show.info-item label="Cadastrado em">
                {{ $professional->created_at?->format('d/m/Y H:i') ?? '---' }}
            </x-show.info-item>

            <x-show.info-item label="Atualizado em">
                {{ $professional->updated_at?->format('d/m/Y H:i') ?? '---' }}
            </x-show.info-item>
        @endcan

        @php
            $modalId = "modal-delete-professional-{$professional->id}";
        @endphp

        <x-show.footer>
            <x-buttons.link-button
                :href="route('profissionais.index')"
                variant="secondary"
            >
                <span class="btn-label">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                </span>
                Voltar
            </x-buttons.link-button>

            @can('professional.delete')
                <x-buttons.submit-button
                    variant="danger"
                    type="button"
                    onclick="new bootstrap.Modal(document.getElementById('{{ $modalId }}')).show();"
                >
                    <span class="btn-label">
                        <i class="fa fa-eraser" aria-hidden="true"></i>
                    </span>
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
                Deseja realmente excluir o profissional
                <strong>{{ $professional->person->name }}</strong>?
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

            <form action="{{ route('profissionais.excluir', $professional) }}" method="POST">
                @csrf
                @method('DELETE')

                <x-buttons.submit-button variant="danger">
                    Excluir
                </x-buttons.submit-button>
            </form>
        </x-slot:footer>
    </x-modal.modal>
@endsection
