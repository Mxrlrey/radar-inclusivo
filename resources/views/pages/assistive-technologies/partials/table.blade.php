<x-table.table
    :headers="[
        ['label' => 'Nome'],
        ['label' => 'Natureza', 'class' => 'col-hide-md'],
        ['label' => 'Estoque', 'class' => 'col-hide-md'],
        ['label' => 'Status', 'class' => 'col-hide-md'],
        ['label' => 'Ações']
    ]"
    :records="$assistiveTechnologies"
    aria-label="Tabela de Tecnologias Assistivas"
    class="table-striped"
>
    @forelse($assistiveTechnologies as $tech)
        @php
            $modalId = "modal-delete-tech-" . $tech->id;
        @endphp

        <tr>
            <x-table.td scope="row" class="font-weight-medium">
                {{ $tech->name }}
            </x-table.td>

            <x-table.td class="align-middle col-hide-md">
                {{ $tech->is_digital ? 'Digital' : 'Físico' }}
            </x-table.td>

            <x-table.td class="align-middle col-hide-md">
                @if($tech->is_digital)
                    <span class="badge bg-info">
                        Ilimitado
                    </span>
                @else
                    <span class="{{ $tech->quantity_available > 0 ? 'text-success' : 'text-danger' }} fw-bold">
                        {{ $tech->quantity_available ?? 0 }}
                    </span>
                    <span class="text-muted">/ {{ $tech->quantity ?? 0 }}</span>
                @endif
            </x-table.td>

            <x-table.td class="align-middle col-hide-md">
                @php
                    $isUnavailable = !$tech->is_digital && ($tech->quantity_available <= 0);
                    $variant = $isUnavailable ? 'danger' : ($tech->is_active ? 'success' : 'secondary');
                    $label = $isUnavailable ? 'Esgotado' : ($tech->is_active ? 'Ativo' : 'Inativo');
                @endphp

                <span class="badge bg-{{ $variant }}">
                    {{ $label }}
                </span>
            </x-table.td>

            <x-table.td>
                <x-table.actions>
                    <x-buttons.link-button
                        :href="route('assistive-technologies.show', $tech)"
                        variant="info"
                        size="xs"
                        title="Visualizar {{ $tech->name }}"
                        aria-label="Visualizar detalhes de {{ $tech->name }}"
                    >
                        <i class="fa fa-eye" aria-hidden="true"></i>
                    </x-buttons.link-button>

                    <x-buttons.submit-button
                        variant="danger"
                        size="xs"
                        type="button"
                        onclick="new bootstrap.Modal(document.getElementById('{{ $modalId }}')).show();"
                        title="Excluir {{ $tech->name }}"
                        aria-label="Abrir confirmação para excluir a tecnologia {{ $tech->name }}"
                    >
                        <i class="fa fa-eraser" aria-hidden="true"></i>
                    </x-buttons.submit-button>
                </x-table.actions>
            </x-table.td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="text-center text-muted py-4" role="status">
                Nenhuma tecnologia assistiva encontrada.
            </td>
        </tr>
    @endforelse
</x-table.table>

@foreach($assistiveTechnologies as $tech)
    @php
        $modalId = "modal-delete-tech-" . $tech->id;
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
                Deseja realmente excluir a tecnologia
                <strong>{{ $tech->name }}</strong>?
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

            <form action="{{ route('assistive-technologies.destroy', $tech) }}" method="POST">
                @csrf
                @method('DELETE')

                <x-buttons.submit-button variant="danger">
                    Excluir
                </x-buttons.submit-button>
            </form>
        </x-slot:footer>
    </x-modal.modal>
@endforeach
