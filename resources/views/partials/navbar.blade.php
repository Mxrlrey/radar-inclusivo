<nav class="navbar-custom" role="navigation" aria-label="Navegação Principal">
    <div class="navbar-inner">
        <div class="navbar-left">
            <button id="sidebarToggle"
                    class="btn-theme-toggle button-menu-mobile waves-effect"
                    type="button"
                    title="Abrir ou fechar menu lateral">
                <i class="ion-navicon" aria-hidden="true"></i>
            </button>

            <div class="navbar-left-content">
                <div class="navbar-heading">
                    <a class="navbar-brand"
                       href="{{ route('instituicoes.index') }}"
                       title="Gerenciar Instituição: {{ $institution?->name ?? 'GNAI' }}">
                        {{ $institution?->name ?? 'GNAI' }}
                    </a>
                </div>

                @if(session()->has('impersonator_id'))
                    <div class="alert alert-warning py-1 px-3 mb-0 d-flex align-items-center navbar-impersonation">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <small>
                            Você está navegando como <strong>{{ auth()->user()->name }}</strong>
                        </small>
                    </div>
                @endif
            </div>
        </div>

        <div class="navbar-right">
            <div role="region" aria-label="Notificações">
                @include('partials._notifications')
            </div>

            <button id="themeToggle"
                    class="btn-theme-toggle waves-effect"
                    type="button"
                    title="Alternar modo escuro">
                <i class="fa fa-moon-o" id="themeIcon" aria-hidden="true"></i>
            </button>

            <button id="contrastToggle"
                    class="btn-theme-toggle waves-effect"
                    type="button"
                    title="Alternar alto contraste">
                <i class="fa fa-low-vision" id="contrastIcon" aria-hidden="true"></i>
            </button>

            <div role="region" aria-label="Menu do usuário">
                @include('partials._user_menu')
            </div>
        </div>
    </div>
</nav>
<script>
    (function() {
        const initNavbarTools = () => {
            // 1. Seletores (Usando documentElement para sincronizar com o script do head)
            const html = document.documentElement;
            const themeToggle = document.getElementById('themeToggle');
            const themeIcon = document.getElementById('themeIcon');

            if (!themeToggle || !themeIcon) return;

            // 2. Sincronização Inicial de Ícone
            // Já que o script do head já aplicou a classe no <html>, só ajustamos o ícone
            if (html.classList.contains('theme-dark')) {
                themeIcon.className = 'fa fa-sun-o';
            }

            // 3. Evento de Clique do Tema (Sem conflitos)
            themeToggle.onclick = function(e) {
                e.preventDefault();
                const isDark = html.classList.toggle('theme-dark');

                // Atualiza Ícone
                themeIcon.className = isDark ? 'fa fa-sun-o' : 'fa fa-moon-o';

                // Salva Preferência
                localStorage.setItem('theme', isDark ? 'dark' : 'light');

                // Opcional: Se estiver usando Bootstrap 5.3 nativo também
                html.setAttribute('data-bs-theme', isDark ? 'dark' : 'light');
            };

            // 4. Alto contraste
            const contrastToggle = document.getElementById('contrastToggle');
            const contrastIcon = document.getElementById('contrastIcon');

            if (contrastToggle && contrastIcon) {

                const html = document.documentElement;

                const enabled = localStorage.getItem('contrast') === 'high';

                if (enabled) {
                    html.classList.add('high-contrast');
                    contrastIcon.className = 'fa fa-eye';
                }

                contrastToggle.onclick = function () {
                    const isOn = html.classList.toggle('high-contrast');

                    contrastIcon.className = isOn ? 'fa fa-eye' : 'fa fa-adjust';

                    localStorage.setItem('contrast', isOn ? 'high' : 'normal');
                };
            }
        };

        // Executa imediatamente se o DOM já estiver pronto, senão aguarda.
        // Isso evita que o CKEditor "travado" impeça o botão de funcionar.
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initNavbarTools);
        } else {
            initNavbarTools();
        }
    })();
</script>
