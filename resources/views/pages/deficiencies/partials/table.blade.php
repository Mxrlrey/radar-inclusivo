<x-table.table
    :headers="[
        ['label' => 'Nome'],
        ['label' => 'CID', 'class' => 'col-hide-md'],
        ['label' => 'Status', 'class' => 'col-hide-md'],
        ['label' => 'Ações']
    ]"
    :records="$deficiencies"
    aria-label="Tabela de Deficiências"
>
    @forelse($deficiencies as $item)
        @php
            $modalId = "modal-delete-def-" . $item->id;
        @endphp

        <tr>
            <x-table.td scope="row" class="font-weight-medium">
                {{ $item->name }}
            </x-table.td>

            <x-table.td class="align-middle col-hide-md">
                {{ $item->cid_code ?? '---' }}
            </x-table.td>

            <x-table.td class="align-middle col-hide-md">
                @php
                    $variant = $item->is_active ? 'success' : 'danger';
                    $label = $item->is_active ? 'Ativo' : 'Inativo';
                @endphp

                <span class="badge bg-{{ $variant }}">
                    {{ $label }}
                </span>
            </x-table.td>

            <x-table.td>
                <x-table.actions>
                    @can('deficiency.view')
                        <x-buttons.link-button
                            :href="route('deficiencias.visualizar', $item)"
                            variant="info"
                            size="xs"
                            title="Visualizar {{ $item->name }}"
                            aria-label="Visualizar detalhes de {{ $item->name }}"
                        >
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </x-buttons.link-button>
                    @endcan

                    @can('deficiency.delete')
                        <x-buttons.submit-button
                            variant="danger"
                            size="xs"
                            type="button"
                            onclick="new bootstrap.Modal(document.getElementById('{{ $modalId }}')).show();"
                            title="Excluir {{ $item->name }}"
                            aria-label="Abrir confirmação para excluir deficiência {{ $item->name }}"
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
                Nenhuma deficiência encontrada.
            </td>
        </tr>
    @endforelse
</x-table.table>

@foreach($deficiencies as $item)
    @php
        $modalId = "modal-delete-def-" . $item->id;
    @endphp

    @can('deficiency.delete')
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
                    Deseja realmente excluir a deficiência
                    <strong>{{ $item->name }}</strong>
                    @if($item->cid_code)
                        (CID: {{ $item->cid_code }})
                    @endif
                    ?
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

                <form action="{{ route('deficiencias.excluir', $item) }}" method="POST">
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
