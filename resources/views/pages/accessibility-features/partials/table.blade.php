<x-table.table
    :headers="[
        ['label' => 'Nome'],
        ['label' => 'Status', 'class' => 'col-hide-md'],
        ['label' => 'Ações']
    ]"
    :records="$features"
    aria-label="Tabela de Recursos de Acessibilidade"
>
    @forelse($features as $feature)
        @php
            $modalId = "modal-delete-feature-" . $feature->id;
        @endphp

        <tr>
            <x-table.td scope="row" class="font-weight-medium">
                {{ $feature->name }}
            </x-table.td>

            <x-table.td class="align-middle col-hide-md">
                <span class="badge bg-{{ $feature->is_active ? 'success' : 'danger' }}">
                    {{ $feature->is_active ? 'Ativo' : 'Inativo' }}
                </span>
            </x-table.td>

            <x-table.td>
                <x-table.actions>
                    <x-buttons.link-button
                        :href="route('recursos-de-acessibilidade.visualizar', $feature)"
                        variant="info"
                        size="xs"
                        title="Visualizar {{ $feature->name }}"
                        aria-label="Visualizar detalhes de {{ $feature->name }}"
                    >
                        <i class="fa fa-eye" aria-hidden="true"></i>
                    </x-buttons.link-button>

                    <x-buttons.submit-button
                        variant="danger"
                        size="xs"
                        type="button"
                        onclick="new bootstrap.Modal(document.getElementById('{{ $modalId }}')).show();"
                        title="Excluir {{ $feature->name }}"
                        aria-label="Abrir confirmação para excluir o recurso {{ $feature->name }}"
                    >
                        <i class="fa fa-eraser" aria-hidden="true"></i>
                    </x-buttons.submit-button>
                </x-table.actions>
            </x-table.td>
        </tr>
    @empty
        <tr>
            <td colspan="3" class="text-center text-muted py-4" role="status">
                Nenhum recurso de acessibilidade encontrado.
            </td>
        </tr>
    @endforelse
</x-table.table>

@foreach($features as $feature)
    @php
        $modalId = "modal-delete-feature-" . $feature->id;
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
                Deseja realmente excluir o recurso
                <strong>{{ $feature->name }}</strong>?
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

            <form action="{{ route('recursos-de-acessibilidade.excluir', $feature) }}" method="POST">
                @csrf
                @method('DELETE')

                <x-buttons.submit-button variant="danger">
                    Excluir
                </x-buttons.submit-button>
            </form>
        </x-slot:footer>
    </x-modal.modal>
@endforeach
