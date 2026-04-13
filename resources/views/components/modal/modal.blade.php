@props([
    'id',
    'title' => null,
    'size' => 'md'
])

@php
    $sizeClass = match($size) {
        'sm' => 'modal-sm',
        'lg' => 'modal-lg',
        'xl' => 'modal-xl',
        default => ''
    };
@endphp

@push('modals')
    <div class="modal fade"
         id="{{ $id }}"
         tabindex="-1"
         aria-labelledby="{{ $id }}-label"
         aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered {{ $sizeClass }}">
            <div class="modal-content text-start">

                @if($title)
                    <div class="modal-header">
                        <h5 class="modal-title" id="{{ $id }}-label">
                            {{ $title }}
                        </h5>

                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Fechar modal">
                        </button>
                    </div>
                @endif

                <div class="modal-body p-0">
                    {{ $slot }}
                </div>

                @isset($footer)
                    <div class="modal-footer">
                        {{ $footer }}
                    </div>
                @endisset
            </div>
        </div>
    </div>
@endpush
