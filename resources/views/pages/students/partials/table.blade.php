<x-table.table
    :headers="[
        ['label' => 'Nome'],
        ['label' => 'Contato', 'class' => 'col-hide-md'],
        ['label' => 'Matrícula', 'class' => 'col-hide-md'],
        ['label' => 'Status', 'class' => 'col-hide-md'],
        ['label' => 'Ingresso', 'class' => 'col-hide-md'],
        ['label' => 'Ações']
    ]"
    :records="$students"
    aria-label="Tabela de Alunos"
>
    @forelse($students as $student)
        @php
            $modalId = "modal-delete-student-" . $student->id;
        @endphp

        <tr>
            <x-table.td scope="row" class="font-weight-medium">
                <div class="d-flex align-items-center gap-2">
                    @if ($student->person->photo_url)
                        <img
                            src="{{ $student->person->photo_url }}"
                            class="avatar-table"
                            alt="Foto de {{ $student->person->name }}"
                        >
                    @endif
                    <span>{{ $student->person->name }}</span>
                </div>
            </x-table.td>

            <x-table.td class="align-middle col-hide-md">
                {{ $student->person->email ?? '---' }}
            </x-table.td>

            <x-table.td class="align-middle col-hide-md">
                {{ $student->registration ?? '---' }}
            </x-table.td>

            <x-table.td class="align-middle col-hide-md">
                @php
                    $variant = $student->is_active ? 'success' : 'danger';
                    $label = $student->is_active ? 'Ativo' : 'Inativo';
                @endphp

                <span class="badge bg-{{ $variant }}">
                    {{ $label }}
                </span>
            </x-table.td>

            <x-table.td class="align-middle col-hide-md">
                {{ $student->entry_date ? \Carbon\Carbon::parse($student->entry_date)->format('d/m/Y') : '---' }}
            </x-table.td>

            <x-table.td>
                <x-table.actions>
                    <x-buttons.link-button
                        :href="route('estudantes.visualizar', $student)"
                        variant="info"
                        size="xs"
                        title="Visualizar {{ $student->person->name }}"
                        aria-label="Visualizar detalhes de {{ $student->person->name }}"
                    >
                        <i class="fa fa-eye" aria-hidden="true"></i>
                    </x-buttons.link-button>

                    <x-buttons.submit-button
                        variant="danger"
                        size="xs"
                        type="button"
                        onclick="new bootstrap.Modal(document.getElementById('{{ $modalId }}')).show();"
                        title="Excluir {{ $student->person->name }}"
                        aria-label="Abrir confirmação para excluir o aluno {{ $student->person->name }}"
                    >
                        <i class="fa fa-eraser" aria-hidden="true"></i>
                    </x-buttons.submit-button>
                </x-table.actions>
            </x-table.td>
        </tr>
    @empty
        <tr>
            <td colspan="6" class="text-center text-muted py-4" role="status">
                Nenhum aluno encontrado.
            </td>
        </tr>
    @endforelse
</x-table.table>

@foreach($students as $student)
    @php
        $modalId = "modal-delete-student-" . $student->id;
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
                Deseja realmente excluir o aluno
                <strong>{{ $student->person->name }}</strong>?
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

            <form action="{{ route('estudantes.excluir', $student) }}" method="POST">
                @csrf
                @method('DELETE')

                <x-buttons.submit-button variant="danger">
                    Excluir
                </x-buttons.submit-button>
            </form>
        </x-slot:footer>
    </x-modal.modal>
@endforeach
