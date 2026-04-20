<x-table.table
    :headers="[
        ['label' => 'Item'],
        ['label' => 'Beneficiário'],
        ['label' => 'Solicitado em', 'class' => 'col-hide-md'],
        ['label' => 'Status', 'class' => 'col-hide-md'],
        ['label' => 'Ações']
    ]"
    :records="$waitlists"
    aria-label="Tabela da Fila de Espera"
>
    @forelse($waitlists as $waitlist)
        @php
            $modalId = "modal-delete-waitlist-" . $waitlist->id;

            // Lógica de Status seguindo o padrão de Badges
            $currentStatus = \App\Enums\WaitlistStatus::tryFrom($waitlist->status);
            $statusLabel = $currentStatus?->label() ?? $waitlist->status;
            $statusColor = $currentStatus?->color() ?? 'secondary';
        @endphp

        <tr>
            <x-table.td class="align-middle col-hide-md">
                {{ $waitlist->waitlistable->name ?? ($waitlist->waitlistable->title ?? 'Item Removido') }}
            </x-table.td>

            <x-table.td class="align-middle">
                @if($waitlist->student)
                    <span class="d-block">{{ $waitlist->student->person->name }}</span>
                    <small class="text-muted">Matrícula: {{ $waitlist->student->registration }}</small>
                @elseif($waitlist->professional)
                    {{ $waitlist->professional->person->name }}
                @else
                    <span class="text-muted small italic">Não informado</span>
                @endif
            </x-table.td>

            <x-table.td class="align-middle col-hide-md">
                {{ $waitlist->requested_at->format('d/m/Y') }}
            </x-table.td>

            <x-table.td class="align-middle col-hide-md">
                <span class="badge bg-{{ $statusColor }}">
                    {{ $statusLabel }}
                </span>
            </x-table.td>

            <x-table.td>
                <x-table.actions>
                    <x-buttons.link-button
                        :href="route('filas-de-espera.visualizar', $waitlist)"
                        variant="info"
                        size="xs"
                        title="Visualizar Solicitação"
                    >
                        <i class="fa fa-eye" aria-hidden="true"></i>
                    </x-buttons.link-button>

                    <x-buttons.submit-button
                        variant="danger"
                        size="xs"
                        type="button"
                        onclick="new bootstrap.Modal(document.getElementById('{{ $modalId }}')).show();"
                        title="Excluir Solicitação"
                    >
                        <i class="fa fa-eraser" aria-hidden="true"></i>
                    </x-buttons.submit-button>
                </x-table.actions>
            </x-table.td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="text-center text-muted py-4" role="status">
                Nenhuma solicitação encontrada na fila.
            </td>
        </tr>
    @endforelse
</x-table.table>

{{-- Modais de Exclusão --}}
@foreach($waitlists as $waitlist)
    @php
        $modalId = "modal-delete-waitlist-" . $waitlist->id;
        $itemName = $waitlist->waitlistable->name ?? ($waitlist->waitlistable->title ?? 'este item');
    @endphp

    <x-modal.modal
        :id="$modalId"
        title="Confirmar Exclusão"
        size="sm"
    >
        <div class="p-3">
            <p class="mb-2 text-danger fw-bold">Esta ação não pode ser desfeita.</p>
            <p class="mb-0 text-muted">
                Deseja realmente remover a solicitação do item <strong>{{ $itemName }}</strong> da fila de espera?
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

            <form action="{{ route('filas-de-espera.excluir', $waitlist) }}" method="POST">
                @csrf
                @method('DELETE')
                <x-buttons.submit-button variant="danger">
                    Excluir
                </x-buttons.submit-button>
            </form>
        </x-slot:footer>
    </x-modal.modal>
@endforeach
