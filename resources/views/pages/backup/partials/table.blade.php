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
>
    @forelse($backups as $backup)
        @php
            $modalDeleteId = "modal-delete-backup-" . $backup->id;
            $modalRestoreId = "modal-restore-backup-" . $backup->id;
        @endphp

        <tr>
            <x-table.td scope="row" class="font-weight-medium">
                <a href="{{ route('copias-seguranca.visualizar', $backup->id) }}" class="text-decoration-none text-dark">
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
                        :href="route('copias-seguranca.visualizar', $backup->id)"
                        variant="info"
                        size="xs"
                        title="Visualizar backup"
                        aria-label="Visualizar detalhes do backup"
                    >
                        <i class="fa fa-eye" aria-hidden="true"></i>
                    </x-buttons.link-button>

                    <x-buttons.link-button
                        :href="route('copias-seguranca.baixar', $backup->id)"
                        variant="success"
                        size="xs"
                        title="Baixar backup"
                        aria-label="Baixar arquivo do backup"
                    >
                        <i class="fa fa-download" aria-hidden="true"></i>
                    </x-buttons.link-button>

                    @if($backup->status === 'success')
                        <x-buttons.submit-button
                            variant="warning"
                            size="xs"
                            type="button"
                            onclick="new bootstrap.Modal(document.getElementById('{{ $modalRestoreId }}')).show();"
                            title="Restaurar backup"
                            aria-label="Restaurar backup"
                        >
                            <i class="fa fa-history" aria-hidden="true"></i>
                        </x-buttons.submit-button>
                    @endif

                    <x-buttons.submit-button
                        variant="danger"
                        size="xs"
                        type="button"
                        onclick="new bootstrap.Modal(document.getElementById('{{ $modalDeleteId }}')).show();"
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
        $modalDeleteId = "modal-delete-backup-" . $backup->id;
        $modalRestoreId = "modal-restore-backup-" . $backup->id;
    @endphp

    <x-modal.modal
        :id="$modalRestoreId"
        title="Confirmar Restauração"
        size="sm"
    >
        <div class="p-3">
            <p class="mb-2 text-warning fw-bold">
                Atenção: esta ação irá restaurar o sistema.
            </p>

            <p class="mb-0 text-muted">
                Deseja realmente restaurar o backup
                <strong>{{ $backup->file_name }}</strong>?
            </p>
        </div>

        <x-slot:footer>
            <div class="d-flex justify-content-end gap-2 w-100">
                <x-buttons.link-button
                    href="javascript:void(0)"
                    variant="secondary"
                    data-bs-dismiss="modal"
                >
                    Cancelar
                </x-buttons.link-button>

                <form action="{{ route('copias-seguranca.restaurar', $backup->id) }}" method="POST" class="m-0">
                    @csrf

                    <x-buttons.submit-button variant="warning" type="submit">
                        Restaurar
                    </x-buttons.submit-button>
                </form>
            </div>
        </x-slot:footer>
    </x-modal.modal>

    <x-modal.modal
        :id="$modalDeleteId"
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
            <div class="d-flex justify-content-end gap-2 w-100">
                <x-buttons.link-button
                    href="javascript:void(0)"
                    variant="secondary"
                    data-bs-dismiss="modal"
                >
                    Cancelar
                </x-buttons.link-button>

                <form action="{{ route('copias-seguranca.excluir', $backup->id) }}" method="POST" class="m-0">
                    @csrf
                    @method('DELETE')

                    <x-buttons.submit-button variant="danger" type="submit">
                        Excluir
                    </x-buttons.submit-button>
                </form>
            </div>
        </x-slot:footer>
    </x-modal.modal>
@endforeach
