<x-table.table
    :headers="[
        ['label' => 'Nome'],
        ['label' => 'Natureza', 'class' => 'col-hide-md'],
        ['label' => 'Estoque', 'class' => 'col-hide-md'],
        ['label' => 'Status', 'class' => 'col-hide-md'],
        ['label' => 'Ações']
    ]"
    :records="$materials"
    aria-label="Tabela de Materiais Pedagógicos Acessíveis"
>
    @forelse($materials as $material)

        @php
            $modalId = "modal-delete-material-" . $material->id;
        @endphp

        <tr>
            <x-table.td scope="row" class="font-weight-medium">
                {{ $material->name }}
            </x-table.td>

            <x-table.td class="align-middle col-hide-md">
                {{ $material->is_digital ? 'Digital' : 'Físico' }}
            </x-table.td>

            <x-table.td class="align-middle col-hide-md">
                @if($material->is_digital)
                    <span class="badge bg-info">
                        Ilimitado
                    </span>
                @else
                    <span class="{{ ($material->quantity_available ?? 0) > 0 ? 'text-success' : 'text-danger' }} fw-bold">
                        {{ $material->quantity_available ?? 0 }}
                    </span>
                    <span class="text-muted">/ {{ $material->quantity ?? 0 }}</span>
                @endif
            </x-table.td>

            <x-table.td class="align-middle col-hide-md">
                @php
                    $isUnavailable = !$material->is_digital && (($material->quantity_available ?? 0) <= 0);
                    $variant = $isUnavailable
                        ? 'danger'
                        : ($material->is_active ? 'success' : 'secondary');

                    $label = $isUnavailable
                        ? 'Esgotado'
                        : ($material->is_active ? 'Ativo' : 'Inativo');
                @endphp

                <span class="badge bg-{{ $variant }}">
                    {{ $label }}
                </span>
            </x-table.td>

            <x-table.td>
                <x-table.actions>
                    <x-buttons.link-button
                        :href="route('materiais-pedagogicos-acessiveis.visualizar', $material)"
                        variant="info"
                        size="xs"
                        title="Visualizar {{ $material->name }}"
                        aria-label="Visualizar detalhes de {{ $material->name }}"
                    >
                        <i class="fa fa-eye" aria-hidden="true"></i>
                    </x-buttons.link-button>

                    <x-buttons.submit-button
                        variant="danger"
                        size="xs"
                        type="button"
                        onclick="new bootstrap.Modal(document.getElementById('{{ $modalId }}')).show();"
                        title="Excluir {{ $material->name }}"
                        aria-label="Abrir confirmação para excluir o material {{ $material->name }}"
                    >
                        <i class="fa fa-eraser" aria-hidden="true"></i>
                    </x-buttons.submit-button>
                </x-table.actions>
            </x-table.td>
        </tr>

    @empty
        <tr>
            <td colspan="5" class="text-center text-muted py-4" role="status">
                Nenhum material pedagógico encontrado.
            </td>
        </tr>
    @endforelse
</x-table.table>


@foreach($materials as $material)

    @php
        $modalId = "modal-delete-material-" . $material->id;
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
                Deseja realmente excluir o material
                <strong>{{ $material->name }}</strong>?
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

            <form action="{{ route('materiais-pedagogicos-acessiveis.excluir', $material) }}" method="POST">
                @csrf
                @method('DELETE')

                <x-buttons.submit-button variant="danger">
                    Excluir
                </x-buttons.submit-button>
            </form>
        </x-slot:footer>
    </x-modal.modal>
@endforeach
