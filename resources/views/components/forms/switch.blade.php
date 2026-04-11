@props([
    'name',
    'label',
    'description' => null,
    'checked' => false
])

<div class="mb-4" style="padding-left: var(--spacing-6); padding-right: var(--spacing-6);" data-switch-group>
    <div class="d-flex align-items-center gap-3">
        <span class="fw-bold" style="color: var(--text-secondary);">{{ $label }}</span>
        <div class="btn-group" role="group" aria-label="{{ $label }}">
            <button type="button"
                    class="btn-action switch-sim xs waves-effect {{ $checked ? 'active' : '' }}"
                    data-switch-value="1">Sim</button>
            <button type="button"
                    class="btn-action switch-nao xs waves-effect {{ !$checked ? 'active' : '' }}"
                    data-switch-value="0">Não</button>
        </div>
    </div>
    <input type="hidden" name="{{ $name }}" value="{{ $checked ? '1' : '0' }}" data-switch-input />
    @if($description)
        <small class="d-block text-muted mt-1">{{ $description }}</small>
    @endif
</div>

@push('scripts')
    <script>
        (function() {
            function initSwitchGroups() {
                document.querySelectorAll('[data-switch-group]').forEach(group => {
                    const input = group.querySelector('[data-switch-input]');
                    const buttons = group.querySelectorAll('[data-switch-value]');
                    if (!input || buttons.length === 0) return;

                    // Remove listeners antigos para evitar duplicação
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

            // Executa quando o DOM estiver pronto
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initSwitchGroups);
            } else {
                initSwitchGroups();
            }

            // Reexecuta se houver conteúdo carregado dinamicamente (ex.: modais)
            document.addEventListener('switch:reload', initSwitchGroups);
        })();
    </script>
@endpush
