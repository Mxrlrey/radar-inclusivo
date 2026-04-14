@props([
    'name',
    'label',
    'description' => null,
    'checked' => false,
    'horizontal' => false,
])

@if($horizontal)
    {{-- LABEL AO LADO --}}
    <div class="form-group-horizontal mb-3" data-switch-group>
        <label class="control-label">{{ $label }}</label>

        <div class="field-wrapper" style="padding-top: 6px;">
            <div class="btn-group" role="group" aria-label="{{ $label }}">
                <button type="button"
                        class="btn-action switch-sim xs waves-effect {{ $checked ? 'active' : '' }}"
                        data-switch-value="1">
                    Sim
                </button>

                <button type="button"
                        class="btn-action switch-nao xs waves-effect {{ !$checked ? 'active' : '' }}"
                        data-switch-value="0">
                    Não
                </button>
            </div>

            <input type="hidden"
                   name="{{ $name }}"
                   value="{{ $checked ? '1' : '0' }}"
                   data-switch-input />

            @if($description)
                <small class="d-block text-muted mt-1">
                    {{ $description }}
                </small>
            @endif
        </div>
    </div>
@else
    {{-- LABEL EM CIMA --}}
    <div class="mb-4 px-4" data-switch-group>
        <label class="form-label fw-bold mb-1">
            {{ $label }}
        </label>

        @if($description)
            <small class="text-muted d-block mb-2">
                {{ $description }}
            </small>
        @endif

        <div class="btn-group" role="group" aria-label="{{ $label }}">
            <button type="button"
                    class="btn-action switch-sim xs waves-effect {{ $checked ? 'active' : '' }}"
                    data-switch-value="1">
                Sim
            </button>

            <button type="button"
                    class="btn-action switch-nao xs waves-effect {{ !$checked ? 'active' : '' }}"
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

                    buttons.forEach(btn => {
                        btn.removeEventListener('click', btn._switchHandler);

                        const handler = () => {
                            const value = btn.dataset.switchValue;
                            input.value = value;

                            buttons.forEach(b => b.classList.remove('active'));
                            btn.classList.add('active');
                        };

                        btn._switchHandler = handler;
                        btn.addEventListener('click', handler);
                    });
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
