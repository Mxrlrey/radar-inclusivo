<x-table.table
    :headers="[
        ['label' => 'Nome'],
        ['label' => 'E-mail', 'class' => 'col-hide-md'],
        ['label' => 'Cargo', 'class' => 'col-hide-md'],
        ['label' => 'Status', 'class' => 'col-hide-md'],
        ['label' => 'Ações']
    ]"
    :records="$professionals"
    aria-label="Tabela de profissionais"
>
    @forelse($professionals as $professional)
        @php
            $modalId = "modal-delete-professional-" . $professional->id;
            $isActive = $professional->is_active;
            $statusLabel = $isActive ? 'Ativo' : 'Inativo';
            $statusColor = $isActive ? 'success' : 'danger';
        @endphp

        <tr>
            <x-table.td scope="row" class="font-weight-medium">
                <div class="d-flex align-items-center gap-2">
                    @if ($professional->person->photo_url)
                        <img
                            src="{{ $professional->person->photo_url }}"
                            class="avatar-table"
                            alt="Foto de {{ $professional->person->name }}"
                        >
                    @endif

                    <span>{{ $professional->person->name }}</span>
                </div>
            </x-table.td>

            <x-table.td class="align-middle col-hide-md">
                {{ $professional->person->email ?? '---' }}
            </x-table.td>

            <x-table.td class="align-middle col-hide-md">
                {{ $professional->position->name ?? '---' }}
            </x-table.td>

            <x-table.td class="align-middle col-hide-md">
                @php
                    $isActive = $professional->is_active;
                    $statusColor = $isActive ? 'success' : 'danger';
                    $statusLabel = $isActive ? 'Ativo' : 'Inativo';
                @endphp

                <span class="badge bg-{{ $statusColor }}">
                    {{ $statusLabel }}
                </span>
            </x-table.td>

            <x-table.td>
                <x-table.actions>

                    @can('professional.show')
                        <x-buttons.link-button
                            :href="route('profissionais.visualizar', $professional)"
                            variant="info"
                            size="xs"
                            title="Visualizar {{ $professional->person->name }}"
                        >
                            <i class="fa fa-eye"></i>
                        </x-buttons.link-button>
                    @endcan

                    @can('professional.delete')
                        <x-buttons.submit-button
                            variant="danger"
                            size="xs"
                            type="button"
                            onclick="new bootstrap.Modal(document.getElementById('{{ $modalId }}')).show();"
                            title="Excluir {{ $professional->person->name }}"
                        >
                            <i class="fa fa-eraser"></i>
                        </x-buttons.submit-button>
                    @endcan

                    @if (!auth()->user()->can('professional.show') &&
                         !auth()->user()->can('professional.delete'))
                        <span class="text-muted small">
                            Você não tem permissões
                        </span>
                    @endif
                </x-table.actions>
            </x-table.td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="text-center text-muted py-4" role="status">
                Nenhum profissional encontrado.
            </td>
        </tr>
    @endforelse
</x-table.table>

@foreach($professionals as $professional)
    @php
        $modalId = "modal-delete-professional-" . $professional->id;
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
                Deseja realmente excluir o profissional
                <strong>{{ $professional->person->name }}</strong>?
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

            <form action="{{ route('profissionais.excluir', $professional) }}" method="POST">
                @csrf
                @method('DELETE')

                <x-buttons.submit-button variant="danger">
                    Excluir
                </x-buttons.submit-button>
            </form>
        </x-slot:footer>
    </x-modal.modal>
@endforeach
