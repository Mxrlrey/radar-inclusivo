@extends('layouts.error')

@section('title', '403 | Acesso Restrito')

@section('content')
<div class="error-shell">
    <header class="error-hero">
        <div class="error-hero-inner">
            <a href="/sobre-nos" class="error-brand" aria-label="Radar Inclusivo">
                <span class="error-brand-icon">
                    <i class="bi bi-broadcast-pin"></i>
                </span>
                <span>
                    <span class="error-brand-name">Radar Inclusivo</span>
                    <span class="error-brand-subtitle">Plataforma de gestão e acessibilidade</span>
                </span>
            </a>

            <a href="/painel" class="error-hero-link">
                <i class="bi bi-grid"></i>
                Ir para o painel
            </a>
        </div>
    </header>

    <main class="error-main">
        <section class="error-panel" aria-labelledby="error-title">
            <div class="error-content">
                <div class="error-kicker">
                    <i class="bi bi-shield-lock"></i>
                    Erro de autorização
                </div>

                <div class="error-display">
                    <div class="error-icon-frame" aria-hidden="true">
                        <svg viewBox="0 0 200 200" fill="none">
                            <rect x="60" y="80" width="80" height="60" rx="10" stroke="currentColor" stroke-width="5" />
                            <path d="M75 80V60C75 40 85 30 100 30C115 30 125 40 125 60V80" stroke="currentColor" stroke-width="5" />
                            <circle cx="100" cy="110" r="8" fill="currentColor" />
                            <path d="M100 118V128" stroke="currentColor" stroke-width="5" stroke-linecap="round" />
                        </svg>
                    </div>

                    <div>
                        <h1 class="error-code">403</h1>
                        <h2 class="error-title" id="error-title">Acesso restrito</h2>
                    </div>
                </div>

                <p class="error-description">
                    O sistema reconheceu a solicitação, mas o seu perfil atual não possui permissão para acessar
                    este recurso. Isso normalmente acontece quando a rota exige um papel, módulo ou privilégio
                    administrativo específico.
                </p>

                <div class="error-actions">
                    <a href="/painel" class="error-button error-button--primary">
                        <i class="bi bi-arrow-left"></i>
                        Voltar ao painel
                    </a>

                    <a href="/sobre-nos" class="error-button error-button--secondary">
                        <i class="bi bi-info-circle"></i>
                        Conhecer o projeto
                    </a>
                </div>

                <div class="error-stats" aria-hidden="true">
                    <div class="error-stat">
                        <span class="error-stat-value">403</span>
                        <span class="error-stat-label">Código HTTP</span>
                    </div>
                    <div class="error-stat">
                        <span class="error-stat-value">Perfil</span>
                        <span class="error-stat-label">Permissão insuficiente</span>
                    </div>
                    <div class="error-stat">
                        <span class="error-stat-value">Seguro</span>
                        <span class="error-stat-label">Acesso bloqueado</span>
                    </div>
                </div>
            </div>

            <aside class="error-sidebar">
                <h3 class="error-sidebar-title">Como seguir</h3>
                <p class="error-sidebar-text">
                    Se você esperava visualizar esta área, vale revisar o fluxo antes de tentar novamente.
                </p>

                <ul class="error-list">
                    <li>
                        <i class="bi bi-check2-circle"></i>
                        Confirme se você entrou com a conta correta e se o perfil tem vínculo com o módulo solicitado.
                    </li>
                    <li>
                        <i class="bi bi-check2-circle"></i>
                        Retorne ao painel e acesse o recurso por um atalho oficial do sistema, evitando links antigos.
                    </li>
                    <li>
                        <i class="bi bi-check2-circle"></i>
                        Caso seja uma liberação pendente, entre em contato com o responsável pela administração.
                    </li>
                </ul>

                <div class="error-note">
                    O bloqueio foi mantido para preservar a integridade dos dados e das permissões internas da plataforma.
                </div>
            </aside>
        </section>
    </main>

    <footer class="error-footer">
        Radar Inclusivo • resposta protegida pelo sistema
    </footer>
</div>
@endsection
