<x-table.table
    :headers="[
        ['label' => 'Nome'],
        ['label' => 'Vínculos', 'class' => 'col-hide-md'],
        ['label' => 'Status', 'class' => 'col-hide-md'],
        ['label' => 'Ações']
    ]"
    :records="$categories"
    aria-label="Tabela de Categorias de Barreiras"
>
    @forelse($categories as $category)
        @php
            $modalId = 'modal-delete-barrier-category-' . $category->id;
            $statusColor = $category->is_active ? 'success' : 'danger';
            $statusLabel = $category->is_active ? 'Ativo' : 'Inativo';
        @endphp

        <tr>
            <x-table.td scope="row" class="font-weight-medium">
                {{ $category->name }}
            </x-table.td>

            <x-table.td class="align-middle col-hide-md">
                {{ $category->barriers_count ?? $category->barriers->count() }}
            </x-table.td>

            <x-table.td class="align-middle col-hide-md">
                <span class="badge bg-{{ $statusColor }}">
                    {{ $statusLabel }}
                </span>
            </x-table.td>

            <x-table.td>
                <x-table.actions>
                    @can('barrier-category.show')
                        <x-buttons.link-button
                            :href="route('categorias-de-barreiras.visualizar', $category)"
                            variant="info"
                            size="xs"
                            title="Visualizar categoria"
                            aria-label="Visualizar detalhes da categoria {{ $category->name }}"
                        >
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </x-buttons.link-button>
                    @endcan

                    @can('barrier-category.destroy')
                        <x-buttons.submit-button
                            variant="danger"
                            size="xs"
                            type="button"
                            onclick="new bootstrap.Modal(document.getElementById('{{ $modalId }}')).show();"
                            title="Excluir categoria"
                            aria-label="Abrir confirmação para excluir a categoria {{ $category->name }}"
                        >
                            <i class="fa fa-eraser" aria-hidden="true"></i>
                        </x-buttons.submit-button>
                    @endcan
                </x-table.actions>
            </x-table.td>
        </tr>
    @empty
        <tr>
            <td colspan="4" class="text-center text-muted py-4" role="status">
                Nenhuma categoria encontrada até o momento.
            </td>
        </tr>
    @endforelse
</x-table.table>

@foreach($categories as $category)
    @php
        $modalId = 'modal-delete-barrier-category-' . $category->id;
    @endphp

    @can('barrier-category.destroy')
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
                    Deseja realmente excluir a categoria
                    <strong>{{ $category->name }}</strong>?
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

                <form action="{{ route('categorias-de-barreiras.excluir', $category) }}" method="POST">
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
