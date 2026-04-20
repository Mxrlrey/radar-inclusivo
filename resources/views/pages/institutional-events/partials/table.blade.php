<x-table.table
    :headers="[
        ['label' => 'Nome'],
        ['label' => 'Início', 'class' => 'col-hide-md'],
        ['label' => 'Término', 'class' => 'col-hide-md'],
        ['label' => 'Horário', 'class' => 'col-hide-md'],
        ['label' => 'Status', 'class' => 'col-hide-md'],
        ['label' => 'Ações']
    ]"
    :records="$events"
    aria-label="Tabela de Eventos Institucionais"
>
    @forelse($events as $event)
        @php
            $modalId = "modal-delete-event-" . $event->id;
        @endphp

        <tr>
            <x-table.td scope="row" class="font-weight-medium">
                {{ $event->title }}
            </x-table.td>

            <x-table.td class="align-middle col-hide-md">
                {{ $event->start_date->format('d/m/Y') }}
            </x-table.td>

            <x-table.td class="align-middle col-hide-md">
                {{ $event->end_date->format('d/m/Y') }}
            </x-table.td>

            <x-table.td class="align-middle col-hide-md">
                {{ \Carbon\Carbon::parse($event->start_time)->format('H:i') }}
                -
                {{ \Carbon\Carbon::parse($event->end_time)->format('H:i') }}
            </x-table.td>

            <x-table.td class="align-middle col-hide-md">
                <span class="badge bg-{{ $event->is_active ? 'success' : 'danger' }}">
                    {{ $event->is_active ? 'Ativo' : 'Inativo' }}
                </span>
            </x-table.td>

            <x-table.td>
                <x-table.actions>
                    <x-buttons.link-button
                        :href="route('agenda-institucional.visualizar', $event)"
                        variant="info"
                        size="xs"
                        title="Visualizar {{ $event->title }}"
                        aria-label="Visualizar detalhes de {{ $event->title }}"
                    >
                        <i class="fa fa-eye" aria-hidden="true"></i>
                    </x-buttons.link-button>

                    <x-buttons.submit-button
                        variant="danger"
                        size="xs"
                        type="button"
                        onclick="new bootstrap.Modal(document.getElementById('{{ $modalId }}')).show();"
                        title="Excluir {{ $event->title }}"
                        aria-label="Abrir confirmação para excluir o evento {{ $event->title }}"
                    >
                        <i class="fa fa-eraser" aria-hidden="true"></i>
                    </x-buttons.submit-button>
                </x-table.actions>
            </x-table.td>
        </tr>
    @empty
        <tr>
            <td colspan="6" class="text-center text-muted py-4" role="status">
                Nenhum evento institucional encontrado.
            </td>
        </tr>
    @endforelse
</x-table.table>

@foreach($events as $event)
    @php
        $modalId = "modal-delete-event-" . $event->id;
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
                Deseja realmente excluir o evento
                <strong>{{ $event->title }}</strong>?
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

            <form action="{{ route('agenda-institucional.excluir', $event) }}" method="POST">
                @csrf
                @method('DELETE')

                <x-buttons.submit-button variant="danger">
                    Excluir
                </x-buttons.submit-button>
            </form>
        </x-slot:footer>
    </x-modal.modal>
@endforeach
