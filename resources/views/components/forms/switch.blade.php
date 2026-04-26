@props([
    'name',
    'label',
    'description' => null,
    'checked' => false,
    'horizontal' => false,
])

@php
    $switchId = str_replace(['[', ']'], '', $name);
    $labelId = $switchId . '-label';
    $descriptionId = $description ? $switchId . '-description' : null;
@endphp

@if($horizontal)
    <div class="form-group-horizontal mb-3" data-switch-group>
        <label class="control-label" id="{{ $labelId }}">{{ $label }}</label>

        <div class="field-wrapper" style="padding-top: 6px;">
            <div class="btn-group"
                 role="radiogroup"
                 aria-labelledby="{{ $labelId }}"
                 @if($descriptionId) aria-describedby="{{ $descriptionId }}" @endif>
                <button type="button"
                        class="btn-action switch-sim xs waves-effect {{ $checked ? 'active' : '' }}"
                        role="radio"
                        aria-checked="{{ $checked ? 'true' : 'false' }}"
                        tabindex="{{ $checked ? '0' : '-1' }}"
                        data-switch-value="1">
                    Sim
                </button>

                <button type="button"
                        class="btn-action switch-nao xs waves-effect {{ !$checked ? 'active' : '' }}"
                        role="radio"
                        aria-checked="{{ !$checked ? 'true' : 'false' }}"
                        tabindex="{{ !$checked ? '0' : '-1' }}"
                        data-switch-value="0">
                    Não
                </button>
            </div>

            <input type="hidden"
                   name="{{ $name }}"
                   value="{{ $checked ? '1' : '0' }}"
                   data-switch-input />

            @if($description)
                <small class="d-block text-muted mt-1" id="{{ $descriptionId }}">
                    {{ $description }}
                </small>
            @endif
        </div>
    </div>
@else
    <div class="mb-4 px-4" data-switch-group>
        <label class="form-label fw-bold mb-1" id="{{ $labelId }}">
            {{ $label }}
        </label>

        @if($description)
            <small class="text-muted d-block mb-2" id="{{ $descriptionId }}">
                {{ $description }}
            </small>
        @endif

        <div class="btn-group"
             role="radiogroup"
             aria-labelledby="{{ $labelId }}"
             @if($descriptionId) aria-describedby="{{ $descriptionId }}" @endif>
            <button type="button"
                    class="btn-action switch-sim xs waves-effect {{ $checked ? 'active' : '' }}"
                    role="radio"
                    aria-checked="{{ $checked ? 'true' : 'false' }}"
                    tabindex="{{ $checked ? '0' : '-1' }}"
                    data-switch-value="1">
                Sim
            </button>

            <button type="button"
                    class="btn-action switch-nao xs waves-effect {{ !$checked ? 'active' : '' }}"
                    role="radio"
                    aria-checked="{{ !$checked ? 'true' : 'false' }}"
                    tabindex="{{ !$checked ? '0' : '-1' }}"
                    data-switch-value="0">
                Não
            </button>
        </div>

        <input type="hidden"
               name="{{ $name }}"
               value="{{ $checked ? '1' : '0' }}"
               data-switch-input />
    </div>
@endif

@push('scripts')
    <script>
        (function() {
            function initSwitchGroups() {
                document.querySelectorAll('[data-switch-group]').forEach(group => {
                    const input = group.querySelector('[data-switch-input]');
                    const buttons = group.querySelectorAll('[data-switch-value]');
                    if (!input || buttons.length === 0) return;

                    const setValue = (value, focusButton = false) => {
                        input.value = value;

                        buttons.forEach(button => {
                            const isActive = button.dataset.switchValue === value;
                            button.classList.toggle('active', isActive);
                            button.setAttribute('aria-checked', isActive ? 'true' : 'false');
                            button.setAttribute('tabindex', isActive ? '0' : '-1');

                            if (focusButton && isActive) {
                                button.focus();
                            }
                        });
                    };

                    buttons.forEach(btn => {
                        btn.removeEventListener('click', btn._switchHandler);
                        btn.removeEventListener('keydown', btn._switchKeyHandler);

                        const handler = () => setValue(btn.dataset.switchValue);
                        const keyHandler = (event) => {
                            if (event.key === ' ' || event.key === 'Enter') {
                                event.preventDefault();
                                setValue(btn.dataset.switchValue, true);
                                return;
                            }

                            if (!['ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown'].includes(event.key)) {
                                return;
                            }

                            event.preventDefault();
                            const values = Array.from(buttons);
                            const currentIndex = values.indexOf(btn);
                            const nextIndex = (event.key === 'ArrowLeft' || event.key === 'ArrowUp')
                                ? (currentIndex - 1 + values.length) % values.length
                                : (currentIndex + 1) % values.length;

                            setValue(values[nextIndex].dataset.switchValue, true);
                        };

                        btn._switchHandler = handler;
                        btn._switchKeyHandler = keyHandler;
                        btn.addEventListener('click', handler);
                        btn.addEventListener('keydown', keyHandler);
                    });

                    setValue(input.value === '1' ? '1' : '0');
                });
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initSwitchGroups);
            } else {
                initSwitchGroups();
            }

            document.addEventListener('switch:reload', initSwitchGroups);
        })();
    </script>
@endpush
