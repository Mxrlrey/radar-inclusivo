@extends('layouts.master')

@section('title', $backup->file_name)

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Backups' => route('backups.index'),
                $backup->file_name => null
            ]" />

            <h1>Detalhes do Backup</h1>
            <p class="text-muted mb-0">
                Visualize informações técnicas, status e gerenciamento do backup.
            </p>
        </div>

        <div class="page-header-actions">
            <x-buttons.link-button
                :href="route('backups.index')"
                variant="secondary"
            >
                <span class="btn-label"><i class="fa fa-arrow-left"></i></span>
                Voltar
            </x-buttons.link-button>
        </div>
    </div>

    <div class="card-custom overflow-hidden show-container">

        <x-forms.section
            title="{{ $backup->file_name }}"
            description="Informações do arquivo de backup selecionado."
        />

        <x-show.info-item label="Nome do Arquivo">
            {{ $backup->file_name }}
        </x-show.info-item>

        <x-show.info-item label="Caminho no Servidor">
            <code>{{ $backup->file_path }}</code>
        </x-show.info-item>

        <x-forms.separator />

        <x-forms.section title="Metadados" />

        <x-show.info-item label="Tamanho do Arquivo">
            {{ $backup->size }}
        </x-show.info-item>

        <x-show.info-item label="Data de Geração">
            {{ $backup->created_at->format('d/m/Y H:i') }}
        </x-show.info-item>

        <x-show.info-item label="Responsável">
            {{ $backup->user->name ?? 'Sistema' }}
        </x-show.info-item>

        <x-forms.separator />

        <x-forms.section title="Status e Governança" />

        <x-show.info-item label="Status do Backup">
            @php
                $statusColors = [
                    'success'  => 'success',
                    'failed'   => 'danger',
                    'archived' => 'info'
                ];

                $statusLabels = [
                    'success'  => 'Sucesso',
                    'failed'   => 'Falha',
                    'archived' => 'Arquivado'
                ];

                $color = $statusColors[$backup->status] ?? 'secondary';
            @endphp

            <span class="badge bg-{{ $color }}">
                {{ $statusLabels[$backup->status] ?? $backup->status }}
            </span>
        </x-show.info-item>

        <x-show.info-item label="Política de Retenção">
            @if($backup->status === 'archived')
                <span class="text-info fw-bold">
                    Protegido contra limpezas automáticas
                </span>
            @else
                <span class="text-muted">
                    Pode ser removido automaticamente conforme políticas do sistema
                </span>
            @endif
        </x-show.info-item>

        @php
            $modalId = "modal-delete-backup-" . $backup->id;
        @endphp

        <x-show.footer
            :backRoute="route('backups.index')"
        >
            <x-buttons.link-button
                :href="route('backups.download', $backup->id)"
                variant="success"
            >
                <span class="btn-label"><i class="fa fa-download"></i></span>
                Baixar Backup
            </x-buttons.link-button>

            <x-buttons.submit-button
                variant="danger"
                type="button"
                onclick="new bootstrap.Modal(document.getElementById('{{ $modalId }}')).show();"
            >
                <span class="btn-label"><i class="fa fa-eraser"></i></span>
                Excluir
            </x-buttons.submit-button>
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
                Deseja realmente excluir o backup <strong>{{ $backup->file_name }}</strong>?
            </p>
        </div>

        <x-slot:footer>
            <x-buttons.link-button
                href="javascript:void(0)"
                variant="secondary"
                onclick="bootstrap.Modal.getInstance(this.closest('.modal')).hide()"
            >
                Cancelar
            </x-buttons.link-button>

            <form action="{{ route('backups.destroy', $backup->id) }}" method="POST">
                @csrf
                @method('DELETE')

                <x-buttons.submit-button variant="danger">
                    Excluir
                </x-buttons.submit-button>
            </form>
        </x-slot:footer>
    </x-modal.modal>
@endsection
