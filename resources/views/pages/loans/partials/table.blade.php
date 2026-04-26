<x-table.table
    :headers="[
        ['label' => 'Item'],
        ['label' => 'Beneficiário'],
        ['label' => 'Prazo', 'class' => 'col-hide-md'],
        ['label' => 'Status', 'class' => 'col-hide-md'],
        ['label' => 'Ações']
    ]"
    :records="$loans"
    aria-label="Tabela de Empréstimos"
>
    @forelse($loans as $loan)
        @php
            $modalId = "modal-delete-loan-" . $loan->id;

            $currentStatus = $loan->status instanceof \App\Enums\LoanStatus
                ? $loan->status
                : \App\Enums\LoanStatus::tryFrom($loan->status);

            $isOverdue = ($currentStatus === \App\Enums\LoanStatus::ACTIVE && $loan->due_date->isPast());

            $statusLabel = $isOverdue ? 'Em Atraso' : ($currentStatus?->label() ?? $loan->status);
            $statusColor = $isOverdue ? 'danger' : ($currentStatus?->color() ?? 'secondary');
        @endphp

        <tr>
            <x-table.td scope="row" class="font-weight-medium">
                {{ $loan->loanable->name ?? ($loan->loanable->title ?? 'Item Removido') }}
            </x-table.td>

            <x-table.td class="align-middle">
                @if($loan->student)
                    <span class="d-block">{{ $loan->student->person->name }}</span>
                    <small class="text-muted">Matrícula: {{ $loan->student->registration }}</small>
                @elseif($loan->professional)
                    {{ $loan->professional->person->name }}
                @else
                    <span class="text-muted small italic">Não informado</span>
                @endif
            </x-table.td>

            <x-table.td class="align-middle col-hide-md">
                <span class="{{ $isOverdue ? 'text-danger fw-bold' : '' }}">
                    {{ $loan->due_date->format('d/m/Y') }}
                </span>
            </x-table.td>

            <x-table.td class="align-middle col-hide-md">
                <span class="badge bg-{{ $statusColor }}">
                    {{ $statusLabel }}
                </span>
            </x-table.td>

            <x-table.td>
                <x-table.actions>
                    <x-buttons.link-button
                        :href="route('emprestimos.visualizar', $loan)"
                        variant="info"
                        size="xs"
                        title="Visualizar Empréstimo"
                    >
                        <i class="fa fa-eye" aria-hidden="true"></i>
                    </x-buttons.link-button>

                    <x-buttons.submit-button
                        variant="danger"
                        size="xs"
                        type="button"
                        onclick="new bootstrap.Modal(document.getElementById('{{ $modalId }}')).show();"
                        title="Excluir Empréstimo"
                    >
                        <i class="fa fa-eraser" aria-hidden="true"></i>
                    </x-buttons.submit-button>
                </x-table.actions>
            </x-table.td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="text-center text-muted py-4" role="status">
                Nenhum empréstimo encontrado.
            </td>
        </tr>
    @endforelse
</x-table.table>

@foreach($loans as $loan)
    @php
        $modalId = "modal-delete-loan-" . $loan->id;
        $itemName = $loan->loanable->name ?? ($loan->loanable->title ?? 'este item');
    @endphp

    <x-modal.modal
        :id="$modalId"
        title="Confirmar Exclusão"
        size="sm"
    >
        <div class="p-3">
            <p class="mb-2 text-danger fw-bold">Esta ação não pode ser desfeita.</p>
            <p class="mb-0 text-muted">
                Deseja realmente excluir o registro de empréstimo do item <strong>{{ $itemName }}</strong>?
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

            <form action="{{ route('emprestimos.excluir', $loan) }}" method="POST">
                @csrf
                @method('DELETE')
                <x-buttons.submit-button variant="danger">
                    Excluir
                </x-buttons.submit-button>
            </form>
        </x-slot:footer>
    </x-modal.modal>
@endforeach
