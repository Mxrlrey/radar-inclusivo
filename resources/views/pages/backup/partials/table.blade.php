<x-table.table
    :headers="[
        ['label' => 'Arquivo'],
        ['label' => 'Status', 'class' => 'col-hide-md'],
        ['label' => 'Tamanho', 'class' => 'col-hide-md'],
        ['label' => 'Criado em', 'class' => 'col-hide-md'],
        ['label' => 'Responsável', 'class' => 'col-hide-md'],
        ['label' => 'Ações']
    ]"
    :records="$backups"
    class="table-striped"
>
    @forelse($backups as $backup)
        @php
            $modalId = "modal-delete-backup-" . $backup->id;
        @endphp

        <tr>
            <x-table.td scope="row" class="font-weight-medium">
                <a href="{{ route('backups.show', $backup->id) }}" class="text-decoration-none text-dark">
                    {{ $backup->file_name }}
                </a>
            </x-table.td>

            <x-table.td class="col-hide-md">
                @php
                    $map = [
                        'success' => ['success', 'Sucesso'],
                        'failed' => ['danger', 'Falha'],
                        'archived' => ['secondary', 'Arquivado'],
                    ];
                    [$variant, $label] = $map[$backup->status] ?? ['secondary', $backup->status];
                @endphp

                <span class="badge bg-{{ $variant }}">
                    {{ $label }}
                </span>
            </x-table.td>

            <x-table.td class="col-hide-md">
                {{ $backup->size }}
            </x-table.td>

            <x-table.td class="col-hide-md">
                {{ $backup->created_at->format('d/m/Y H:i') }}
            </x-table.td>

            <x-table.td class="col-hide-md">
                {{ $backup->user->name ?? 'Sistema' }}
            </x-table.td>

            <x-table.td>
                <x-table.actions>
                    <x-buttons.link-button
                        :href="route('backups.show', $backup->id)"
                        variant="info"
                        size="xs"
                        title="Visualizar backup"
                        aria-label="Visualizar detalhes do backup"
                    >
                        <i class="fa fa-eye" aria-hidden="true"></i>
                    </x-buttons.link-button>

                    <x-buttons.link-button
                        :href="route('backups.download', $backup->id)"
                        variant="success"
                        size="xs"
                        title="Baixar backup"
                        aria-label="Baixar arquivo do backup"
                    >
                        <i class="fa fa-download" aria-hidden="true"></i>
                    </x-buttons.link-button>

                    @if($backup->status === 'success')
                        <form action="{{ route('backups.restore', $backup->id) }}"
                              method="POST"
                              class="d-inline form-restore">
                            @csrf
                            <x-buttons.submit-button
                                variant="warning"
                                size="xs"
                                class="btn-restore"
                                data-filename="{{ $backup->file_name }}"
                                title="Restaurar backup"
                                aria-label="Restaurar sistema usando o backup"
                            >
                                <i class="fa fa-history" aria-hidden="true"></i>
                            </x-buttons.submit-button>
                        </form>
                    @endif

                    <x-buttons.submit-button
                        variant="danger"
                        size="xs"
                        type="button"
                        onclick="new bootstrap.Modal(document.getElementById('{{ $modalId }}')).show();"
                        title="Excluir backup"
                        aria-label="Abrir confirmação para excluir o backup"
                    >
                        <i class="fa fa-eraser" aria-hidden="true"></i>
                    </x-buttons.submit-button>
                </x-table.actions>
            </x-table.td>
        </tr>
    @empty
        <tr>
            <td colspan="6" class="text-center text-muted py-4">
                Nenhum backup encontrado.
            </td>
        </tr>
    @endforelse
</x-table.table>

@foreach($backups as $backup)
    @php
        $modalId = "modal-delete-backup-" . $backup->id;
    @endphp

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
                Deseja realmente excluir o backup
                <strong>{{ $backup->file_name }}</strong>?
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
@endforeach
