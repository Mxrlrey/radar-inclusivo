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
            <x-table.td>
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
                        title="Visualizar Recursos de Acessibilidade"
                    >
                        <i class="fa fa-eye"></i>
                    </x-buttons.link-button>

                    <form action="{{ route('accessibility-features.destroy', $feature) }}"
                          method="POST"
                          class="d-inline"
                          title="Remover Recursos de Acessibilidade"
                    >
                        @csrf
                        @method('DELETE')
                        <x-buttons.submit-button
                            variant="danger"
                            size="xs"
                            onclick="return confirm('Deseja realmente remover este recurso?')"
                        >
                            <i class="fa fa-eraser"></i>
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
