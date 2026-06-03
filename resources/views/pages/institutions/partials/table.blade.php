<x-table.table
    :headers="[
        ['label' => 'Nome'],
        ['label' => 'Localização', 'class' => 'col-hide-md'],
        ['label' => 'Status', 'class' => 'col-hide-md'],
        ['label' => 'Ações']
    ]"
    :records="$institutions"
    aria-label="Tabela de Instituições"
>
    @forelse($institutions as $inst)
        @php
            $modalId = "modal-delete-institution-" . $inst->id;
            $statusColor = $inst->is_active ? 'success' : 'danger';
            $statusLabel = $inst->is_active ? 'Ativo' : 'Inativo';
        @endphp

        <tr>
            <x-table.td scope="row" class="font-weight-medium">
                {{ $inst->name }}
            </x-table.td>

            <x-table.td class="align-middle col-hide-md">
                {{ $inst->city }} - {{ $inst->state }}
            </x-table.td>

            <x-table.td class="align-middle col-hide-md">
                <span class="badge bg-{{ $statusColor }}">
                    {{ $statusLabel }}
                </span>
            </x-table.td>

            <x-table.td>
                <x-table.actions>
                    @can('institution.show')
                        <x-buttons.link-button
                            :href="route('instituicoes.visualizar', $inst)"
                            variant="info"
                            size="xs"
                            title="Visualizar instituição"
                            aria-label="Visualizar detalhes da instituição {{ $inst->name }}"
                        >
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </x-buttons.link-button>
                    @endcan

                    @can('institution.destroy')
                        <x-buttons.submit-button
                            variant="danger"
                            size="xs"
                            type="button"
                            onclick="new bootstrap.Modal(document.getElementById('{{ $modalId }}')).show();"
                            title="Excluir instituição"
                            aria-label="Abrir confirmação para excluir a instituição {{ $inst->name }}"
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
                Nenhuma instituição cadastrada até o momento.
            </td>
        </tr>
    @endforelse
</x-table.table>

@foreach($institutions as $inst)
    @php
        $modalId = "modal-delete-institution-" . $inst->id;
    @endphp

    @can('institution.destroy')
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
                    Deseja realmente excluir a instituição
                    <strong>{{ $inst->name }}</strong>?
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

                <form action="{{ route('instituicoes.excluir', $inst) }}" method="POST">
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
