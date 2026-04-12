@props([
    'editRoute' => null,
    'editLabel' => 'Editar',
    'deleteRoute' => null,
    'deleteConfirm' => 'Deseja excluir permanentemente?',
    'backRoute' => null,
    'backLabel' => 'Voltar',
])

<hr class="show-divider">

<div class="show-field">
    <span class="show-label"></span>
    <div class="show-value d-flex gap-2 flex-wrap">

        {{ $slot }}

        @if($backRoute)
            <x-buttons.link-button :href="$backRoute" variant="secondary">
                <span class="btn-label"><i class="fa fa-arrow-left"></i></span> {{ $backLabel }}
            </x-buttons.link-button>
        @endif

        @if($editRoute)
            <x-buttons.link-button :href="$editRoute" variant="info">
                <span class="btn-label"><i class="fa fa-pencil"></i></span> {{ $editLabel }}
            </x-buttons.link-button>
        @endif

        @if($deleteRoute)
            <form action="{{ $deleteRoute }}" method="POST"
                  onsubmit="return confirm('{{ $deleteConfirm }}')">
                @csrf
                @method('DELETE')
                <x-buttons.submit-button variant="danger">
                    <span class="btn-label"><i class="fa fa-eraser"></i></span> Excluir
                </x-buttons.submit-button>
            </form>
        @endif
    </div>
</div>
