@php $modalId = fn($item) => "modal-delete-position-" . $item->id; @endphp

<x-table.table
    :headers="[
        ['label' => 'Cargo'],
        ['label' => 'Status', 'class' => 'col-hide-md'],
        ['label' => 'Ações']
    ]"
    :records="$positions"
    aria-label="Tabela de Cargos"
>
    @forelse($positions as $item)
        <tr>
            <x-table.td scope="row">
                {{ $item->name }}
            </x-table.td>

            <x-table.td class="align-middle col-hide-md">
                <span class="badge bg-{{ $item->is_active ? 'success' : 'danger' }}">
                    {{ $item->is_active ? 'Ativo' : 'Inativo' }}
                </span>
            </x-table.td>

            <x-table.td>
                <x-table.actions>
                    @can('position.view')
                        <x-buttons.link-button
                            :href="route('cargos.visualizar', $item)"
                            variant="info"
                            size="xs"
                            title="Visualizar {{ $item->name }}"
                        >
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </x-buttons.link-button>
                    @endcan

                    @can('position.delete')
                        <x-buttons.submit-button
                            variant="danger"
                            size="xs"
                            type="button"
                            onclick="new bootstrap.Modal(document.getElementById('{{ $modalId($item) }}')).show();"
                            title="Excluir {{ $item->name }}"
                        >
                            <i class="fa fa-eraser" aria-hidden="true"></i>
                        </x-buttons.submit-button>
                    @endcan
                </x-table.actions>
            </x-table.td>
        </tr>
    @empty
        <tr>
            <td colspan="3" class="text-center text-muted py-4" role="status">
                Nenhum cargo encontrado.
            </td>
        </tr>
    @endforelse
</x-table.table>

@foreach($positions as $item)
    @can('position.delete')
        <x-modal.modal
            :id="$modalId($item)"
            title="Confirmar Exclusão"
            size="sm"
        >
            <div class="p-3">
                <p class="mb-2 text-danger fw-bold">Esta ação não pode ser desfeita.</p>
                <p class="mb-0 text-muted">
                    Deseja realmente excluir o cargo <strong>{{ $item->name }}</strong>?
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

                <form action="{{ route('cargos.excluir', $item) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <x-buttons.submit-button variant="danger">
                        Excluir
                    </x-buttons.submit-button>
                </form>
            </x-slot:footer>
        </x-modal.modal>
    @endcan
@endforeach
