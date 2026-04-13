<x-table.table
    :headers="[
        ['label' => 'Nome'],
        ['label' => 'Status', 'class' => 'col-hide-md'],
        ['label' => 'Ações'],
    ]"
    :records="$features"
    class="table-striped"
>
    @forelse($features as $feature)
        <tr>
            <x-table.td scope="row" class="font-weight-medium">
                {{ $feature->name }}
            </x-table.td>

            <x-table.td class="align-middle text-nowrap col-hide-md">
                <span class="badge bg-{{ $feature->is_active ? 'success' : 'danger' }}">
                    {{ $feature->is_active ? 'Ativo' : 'Inativo' }}
                </span>
            </x-table.td>

            <x-table.td>
                <x-table.actions>
                    <x-buttons.link-button
                        :href="route('accessibility-features.show', $feature)"
                        variant="info"
                        size="xs"
                        title="Visualizar {{ $feature->name }}"
                        aria-label="Visualizar detalhes do recurso {{ $feature->name }}"
                    >
                        <i class="fa fa-eye" aria-hidden="true"></i>
                    </x-buttons.link-button>

                    <form action="{{ route('accessibility-features.destroy', $feature) }}"
                          method="POST"
                          class="d-inline"
                    >
                        @csrf
                        @method('DELETE')
                        <x-buttons.submit-button
                            variant="danger"
                            size="xs"
                            title="Remover {{ $feature->name }}"
                            aria-label="Excluir recurso {{ $feature->name }}"
                            onclick="return confirm('Deseja realmente remover o recurso {{ $feature->name }}?')"
                        >
                            <i class="fa fa-eraser" aria-hidden="true"></i>
                        </x-buttons.submit-button>
                    </form>
                </x-table.actions>
            </x-table.td>
        </tr>
    @empty
        <tr>
            <td colspan="3" class="text-center text-muted py-4">
                Nenhum recurso de acessibilidade cadastrado.
            </td>
        </tr>
    @endforelse
</x-table.table>
