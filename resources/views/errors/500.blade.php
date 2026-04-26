@extends('layouts.error')

@section('title', '500 | Erro Interno')

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
                    <i class="bi bi-exclamation-octagon"></i>
                    Erro interno
                </div>

                <div class="error-display">
                    <div class="error-icon-frame" aria-hidden="true">
                        <svg viewBox="0 0 200 200" fill="none">
                            <path d="M100 28L168 150H32L100 28Z" stroke="currentColor" stroke-width="5" />
                            <path d="M100 74V112" stroke="currentColor" stroke-width="5" stroke-linecap="round" />
                            <circle cx="100" cy="128" r="4" fill="currentColor" />
                        </svg>
                    </div>

                    <div>
                        <h1 class="error-code">500</h1>
                        <h2 class="error-title" id="error-title">Falha ao processar a solicitação</h2>
                    </div>
                </div>

                <p class="error-description">
                    {{ $message ?? 'Ocorreu um erro inesperado.' }}
                    O problema foi interrompido antes da conclusão da resposta para preservar a estabilidade da aplicação.
                </p>

                <div class="error-actions">
                    <a href="/painel" class="error-button error-button--primary">
                        <i class="bi bi-arrow-left"></i>
                        Voltar ao painel
                    </a>

                    <a href="/sobre-nos" class="error-button error-button--secondary">
                        <i class="bi bi-info-circle"></i>
                        Ver sobre o sistema
                    </a>
                </div>

                <div class="error-stats" aria-hidden="true">
                    <div class="error-stat">
                        <span class="error-stat-value">500</span>
                        <span class="error-stat-label">Código HTTP</span>
                    </div>
                    <div class="error-stat">
                        <span class="error-stat-value">Servidor</span>
                        <span class="error-stat-label">Processamento interrompido</span>
                    </div>
                    <div class="error-stat">
                        <span class="error-stat-value">Seguro</span>
                        <span class="error-stat-label">Resposta controlada</span>
                    </div>
                </div>
            </div>

            <aside class="error-sidebar">
                <h3 class="error-sidebar-title">O que fazer agora</h3>
                <p class="error-sidebar-text">
                    Esse tipo de falha costuma ser temporário ou depender de um fluxo específico que gerou exceção.
                </p>

                <ul class="error-list">
                    <li>
                        <i class="bi bi-check2-circle"></i>
                        Retorne ao painel e tente repetir a ação a partir da navegação principal do sistema.
                    </li>
                    <li>
                        <i class="bi bi-check2-circle"></i>
                        Caso o erro persista sempre no mesmo ponto, o ideal é revisar logs e tratamento da exceção correspondente.
                    </li>
                    <li>
                        <i class="bi bi-check2-circle"></i>
                        Se o problema envolver dados específicos, valide permissões, parâmetros e estado do registro acessado.
                    </li>
                </ul>

                <div class="error-note">
                    A resposta foi encerrada com tratamento controlado para evitar páginas quebradas ou redirecionamentos inconsistentes.
                </div>
            </aside>
        </section>
    </main>

    <footer class="error-footer">
        Radar Inclusivo • monitoramento e tratamento centralizado de exceções
    </footer>
</div>
@endsection
