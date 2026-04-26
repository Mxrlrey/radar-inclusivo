@extends('layouts.error')

@section('title', '404 | Página Não Encontrada')

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
                    <i class="bi bi-search"></i>
                    Erro de navegação
                </div>

                <div class="error-display">
                    <div class="error-icon-frame" aria-hidden="true">
                        <svg viewBox="0 0 200 200" fill="none">
                            <path d="M140 80C155 80 155 60 140 60V40C140 33 135 30 130 30H110C110 20 104 15 100 15C96 15 90 20 90 30H70C65 30 60 33 60 40V60C45 60 45 80 60 80V100C60 107 65 110 70 110H130C135 110 140 107 140 100V80Z" stroke="currentColor" stroke-width="5" />
                            <circle cx="100" cy="70" r="16" fill="currentColor" opacity="0.15" />
                            <path d="M100 58V78" stroke="currentColor" stroke-width="5" stroke-linecap="round" />
                            <circle cx="100" cy="88" r="3.5" fill="currentColor" />
                        </svg>
                    </div>

                    <div>
                        <h1 class="error-code">404</h1>
                        <h2 class="error-title" id="error-title">Página não encontrada</h2>
                    </div>
                </div>

                <p class="error-description">
                    A rota solicitada não foi localizada. O endereço pode ter sido alterado, removido ou acessado
                    por um link desatualizado. O sistema continua disponível, mas este ponto específico não existe
                    mais no mapa atual da aplicação.
                </p>

                <div class="error-actions">
                    <a href="/painel" class="error-button error-button--primary">
                        <i class="bi bi-arrow-left"></i>
                        Voltar ao painel
                    </a>

                    <a href="/sobre-nos" class="error-button error-button--secondary">
                        <i class="bi bi-compass"></i>
                        Ver sobre o sistema
                    </a>
                </div>

                <div class="error-stats" aria-hidden="true">
                    <div class="error-stat">
                        <span class="error-stat-value">404</span>
                        <span class="error-stat-label">Código HTTP</span>
                    </div>
                    <div class="error-stat">
                        <span class="error-stat-value">Link</span>
                        <span class="error-stat-label">Rota indisponível</span>
                    </div>
                    <div class="error-stat">
                        <span class="error-stat-value">Mapa</span>
                        <span class="error-stat-label">Destino ausente</span>
                    </div>
                </div>
            </div>

            <aside class="error-sidebar">
                <h3 class="error-sidebar-title">Sugestões rápidas</h3>
                <p class="error-sidebar-text">
                    Você pode retomar a navegação por um ponto seguro e reencontrar o conteúdo desejado.
                </p>

                <ul class="error-list">
                    <li>
                        <i class="bi bi-check2-circle"></i>
                        Revise o endereço digitado e confirme se não houve erro de digitação na URL.
                    </li>
                    <li>
                        <i class="bi bi-check2-circle"></i>
                        Retorne ao painel principal para acessar os módulos usando a navegação oficial do sistema.
                    </li>
                    <li>
                        <i class="bi bi-check2-circle"></i>
                        Se o link veio de um favorito antigo, substitua-o por uma rota atualizada após reencontrar a página.
                    </li>
                </ul>

                <div class="error-note">
                    Quando uma página muda de lugar, o restante da plataforma continua preservado e pronto para uso.
                </div>
            </aside>
        </section>
    </main>

    <footer class="error-footer">
        Radar Inclusivo • navegação orientada por acessibilidade e contexto
    </footer>
</div>
@endsection
