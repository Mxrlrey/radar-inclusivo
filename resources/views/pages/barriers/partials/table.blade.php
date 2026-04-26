<x-table.table
    :headers="[
        ['label' => 'Nome'],
        ['label' => 'Categoria', 'class' => 'col-hide-md'],
        ['label' => 'Prioridade', 'class' => 'col-hide-md'],
        ['label' => 'Status', 'class' => 'col-hide-md'],
        ['label' => 'Ações']
    ]"
    :records="$barriers"
    aria-label="Tabela de Barreiras"
>
    @forelse($barriers as $barrier)
        @php
            $modalId = "modal-delete-barrier-" . $barrier->id;
        @endphp

        <tr>
            <x-table.td scope="row" class="font-weight-medium">
                {{ $barrier->name }}
            </x-table.td>

            <x-table.td class="align-middle col-hide-md">
                {{ $barrier->category?->name ?? '-' }}
            </x-table.td>

            <x-table.td class="align-middle col-hide-md">
                @php
                    $prioColor = $barrier->priority?->color() ?? 'secondary';
                @endphp

                <span class="badge bg-{{ $prioColor }}">
                    {{ $barrier->priority?->label() ?? '-' }}
                </span>
            </x-table.td>

            <x-table.td class="align-middle col-hide-md">
                @php
                    $status = $barrier->latestStatus();
                    $statusColor = $status?->color() ?? 'secondary';
                @endphp

                <span class="badge bg-{{ $statusColor }}">
                    {{ $status?->label() ?? 'Pendente' }}
                </span>
            </x-table.td>

            <x-table.td>
                <x-table.actions>
                    <x-buttons.link-button
                        :href="route('barreiras.visualizar', $barrier)"
                        variant="info"
                        size="xs"
                        title="Visualizar barreira"
                        aria-label="Visualizar detalhes da barreira {{ $barrier->name }}"
                    >
                        <i class="fa fa-eye" aria-hidden="true"></i>
                    </x-buttons.link-button>

                    <x-buttons.submit-button
                        variant="danger"
                        size="xs"
                        type="button"
                        onclick="new bootstrap.Modal(document.getElementById('{{ $modalId }}')).show();"
                        title="Excluir barreira"
                        aria-label="Abrir confirmação para excluir a barreira {{ $barrier->name }}"
                    >
                        <i class="fa fa-eraser" aria-hidden="true"></i>
                    </x-buttons.submit-button>
                </x-table.actions>
            </x-table.td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="text-center text-muted py-4" role="status">
                Nenhuma barreira identificada até o momento.
            </td>
        </tr>
    @endforelse
</x-table.table>

@foreach($barriers as $barrier)
    @php
        $modalId = "modal-delete-barrier-" . $barrier->id;
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
                Deseja realmente excluir a barreira
                <strong>{{ $barrier->name }}</strong>?
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

            <form action="{{ route('barreiras.excluir', $barrier) }}" method="POST">
                @csrf
                @method('DELETE')

                <x-buttons.submit-button variant="danger">
                    Excluir
                </x-buttons.submit-button>
            </form>
        </x-slot:footer>
    </x-modal.modal>
@endforeach
