<x-table.table
    :headers="[
        ['label' => 'Nome'],
        ['label' => 'Instituição', 'class' => 'col-hide-md'],
        ['label' => 'Tipo', 'class' => 'col-hide-md'],
        ['label' => 'Status', 'class' => 'col-hide-md'],
        ['label' => 'Ações']
    ]"
    :records="$locations"
    aria-label="Tabela de Pontos de Referência"
>
    @forelse($locations as $loc)
        @php
            $modalId = 'modal-delete-location-' . $loc->id;
            $statusColor = $loc->is_active ? 'success' : 'danger';
            $statusLabel = $loc->is_active ? 'Ativo' : 'Inativo';
        @endphp

        <tr>
            <x-table.td scope="row" class="font-weight-medium">
                {{ $loc->name ?? 'N/A' }}
            </x-table.td>

            <x-table.td class="align-middle col-hide-md">
                {{ $loc->institution->name ?? 'N/A' }}
            </x-table.td>

            <x-table.td class="align-middle col-hide-md">
                {{ $loc->type ?? 'N/A' }}
            </x-table.td>

            <x-table.td class="align-middle col-hide-md">
                <span class="badge bg-{{ $statusColor }}">
                    {{ $statusLabel }}
                </span>
            </x-table.td>

            <x-table.td>
                <x-table.actions>
                    @can('location.show')
                        <x-buttons.link-button
                            :href="route('localizacoes.visualizar', $loc)"
                            variant="info"
                            size="xs"
                            title="Visualizar ponto de referência"
                            aria-label="Visualizar detalhes do ponto de referência {{ $loc->name }}"
                        >
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </x-buttons.link-button>
                    @endcan

                    @can('location.destroy')
                        <x-buttons.submit-button
                            variant="danger"
                            size="xs"
                            type="button"
                            onclick="new bootstrap.Modal(document.getElementById('{{ $modalId }}')).show();"
                            title="Excluir ponto de referência"
                            aria-label="Abrir confirmação para excluir o ponto de referência {{ $loc->name }}"
                        >
                            <i class="fa fa-eraser" aria-hidden="true"></i>
                        </x-buttons.submit-button>
                    @endcan
                </x-table.actions>
            </x-table.td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="text-center text-muted py-4" role="status">
                Nenhum ponto de referência encontrado até o momento.
            </td>
        </tr>
    @endforelse
</x-table.table>

@foreach($locations as $loc)
    @php
        $modalId = 'modal-delete-location-' . $loc->id;
    @endphp

    @can('location.destroy')
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
                    Deseja realmente excluir o ponto de referência
                    <strong>{{ $loc->name }}</strong>?
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

                <form action="{{ route('localizacoes.excluir', $loc) }}" method="POST">
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
