<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 | Acesso Restrito</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;500;600;700&family=Rajdhani:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --color-primary: #5fbeaa;
            --color-primary-dark: #4aa894;
            --color-primary-rgb: 95, 190, 170;
            --bg-body: #f4f8fb;
            --bg-surface: #ffffff;
            --bg-surface-secondary: #f4f8fb;
            --text-title: #36404a;
            --text-primary: #36404a;
            --text-secondary: #4c5667;
            --text-muted: #98a6ad;
            --border-color: #e3e7ec;
            --border-color-light: #edf0f0;
            --font-family-base: 'Noto Sans', 'Roboto', system-ui, sans-serif;
            --font-family-heading: 'Rajdhani', 'Source Sans Pro', sans-serif;
            --shadow-lg: 0 4px 12px rgba(0, 0, 0, 0.15);
            --error-hero-start: #2a374a;
            --error-hero-mid: #4c5667;
            --error-hero-end: #6c7a8e;
        }

        * { box-sizing: border-box; }
        html, body { min-height: 100%; }
        body {
            margin: 0;
            font-family: var(--font-family-base);
            background:
                radial-gradient(circle at top left, rgba(var(--color-primary-rgb), 0.12), transparent 35%),
                linear-gradient(180deg, #eef3f7 0%, var(--bg-body) 100%);
            color: var(--text-secondary);
            animation: errorBodyFade 0.35s ease-out;
        }
        .error-shell {
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }
        .error-shell::before,
        .error-shell::after {
            content: "";
            position: absolute;
            border-radius: 999px;
            background: rgba(var(--color-primary-rgb), 0.08);
            pointer-events: none;
        }
        .error-shell::before {
            width: 26rem;
            height: 26rem;
            top: -11rem;
            right: -8rem;
            animation: errorOrbFloat 10s ease-in-out infinite alternate;
        }
        .error-shell::after {
            width: 20rem;
            height: 20rem;
            bottom: -9rem;
            left: -7rem;
            animation: errorOrbFloat 12s ease-in-out infinite alternate-reverse;
        }
        .error-hero {
            position: relative;
            padding: 5rem 1.5rem 6rem;
            background: linear-gradient(135deg, var(--error-hero-start) 0%, var(--error-hero-mid) 58%, var(--error-hero-end) 100%);
            overflow: hidden;
        }
        .error-hero::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 18% 24%, rgba(var(--color-primary-rgb), 0.20), transparent 30%),
                radial-gradient(circle at 82% 18%, rgba(255, 255, 255, 0.08), transparent 28%);
        }
        .error-hero::after {
            content: "";
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255, 255, 255, 0.08) 1px, transparent 1px);
            background-size: 20px 20px;
            opacity: 0.25;
        }
        .error-hero-inner,
        .error-main,
        .error-footer {
            position: relative;
            z-index: 1;
            max-width: 1180px;
            margin-left: auto;
            margin-right: auto;
        }
        .error-hero-inner {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            animation: errorSlideDown 0.5s ease-out;
        }
        .error-brand {
            display: inline-flex;
            align-items: center;
            gap: 0.85rem;
            color: #ffffff;
            text-decoration: none;
        }
        .error-brand-icon {
            width: 3rem;
            height: 3rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--color-primary);
            color: #ffffff;
            font-size: 1.25rem;
        }
        .error-brand-name {
            display: block;
            font-family: var(--font-family-heading);
            font-size: 1.5rem;
            font-weight: 700;
            line-height: 1;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }
        .error-brand-subtitle {
            display: block;
            margin-top: 0.15rem;
            font-size: 0.78rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.72);
        }
        .error-hero-link,
        .error-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.65rem;
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }
        .error-hero-link {
            padding: 0.8rem 1.2rem;
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: #ffffff;
            background: rgba(255, 255, 255, 0.08);
        }
        .error-main {
            margin-top: -3.5rem;
            padding: 0 1.5rem 2.5rem;
            animation: errorSlideUp 0.65s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .error-panel {
            display: grid;
            grid-template-columns: minmax(0, 1.3fr) minmax(280px, 0.7fr);
            background: var(--bg-surface);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-lg);
            transform-origin: center top;
        }
        .error-content,
        .error-sidebar {
            padding: 2.5rem;
        }
        .error-content {
            background: linear-gradient(180deg, rgba(var(--color-primary-rgb), 0.03) 0%, rgba(255, 255, 255, 0) 100%);
        }
        .error-sidebar {
            background: linear-gradient(180deg, var(--bg-surface-secondary) 0%, rgba(var(--color-primary-rgb), 0.06) 100%);
            border-left: 1px solid var(--border-color);
        }
        .error-kicker,
        .error-stat-label,
        .error-footer {
            text-transform: uppercase;
            letter-spacing: 0.12em;
        }
        .error-kicker {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
            color: var(--color-primary);
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.18em;
        }
        .error-display {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .error-icon-frame {
            width: 7rem;
            height: 7rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: linear-gradient(135deg, rgba(var(--color-primary-rgb), 0.12), rgba(var(--color-primary-rgb), 0.04));
            border-left: 4px solid var(--color-primary);
            color: var(--color-primary);
            animation: errorIconPulse 1.8s ease-out 0.2s both;
        }
        .error-icon-frame svg {
            width: 4rem;
            height: 4rem;
        }
        .error-code,
        .error-title,
        .error-sidebar-title,
        .error-stat-value {
            font-family: var(--font-family-heading);
            color: var(--text-title);
        }
        .error-code {
            margin: 0;
            font-size: clamp(4.5rem, 10vw, 8rem);
            line-height: 0.9;
            font-weight: 700;
            animation: errorCodeReveal 0.7s cubic-bezier(0.22, 1, 0.36, 1) 0.08s both;
        }
        .error-title {
            margin: 0.35rem 0 0;
            font-size: clamp(1.7rem, 4vw, 2.6rem);
            line-height: 1;
            text-transform: uppercase;
            animation: errorFadeUp 0.5s ease-out 0.22s both;
        }
        .error-description,
        .error-sidebar-text,
        .error-list li,
        .error-note {
            line-height: 1.8;
        }
        .error-description {
            max-width: 44rem;
            margin: 0 0 2rem;
            font-size: 1rem;
            animation: errorFadeUp 0.55s ease-out 0.3s both;
        }
        .error-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.9rem;
            margin-bottom: 2rem;
            animation: errorFadeUp 0.55s ease-out 0.38s both;
        }
        .error-button {
            min-height: 2.9rem;
            padding: 0.85rem 1.35rem;
            border: 1px solid transparent;
        }
        .error-button--primary {
            background: var(--color-primary);
            color: #ffffff;
        }
        .error-button--secondary {
            background: transparent;
            border-color: var(--border-color);
            color: var(--text-primary);
        }
        .error-stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
            animation: errorFadeUp 0.6s ease-out 0.48s both;
        }
        .error-stat {
            padding: 1.1rem 1rem;
            background: var(--bg-surface);
            border-left: 4px solid var(--color-primary);
        }
        .error-stat-value {
            display: block;
            font-size: 1.75rem;
            line-height: 1;
            font-weight: 700;
        }
        .error-stat-label {
            display: block;
            margin-top: 0.45rem;
            font-size: 0.76rem;
            font-weight: 700;
            color: var(--text-muted);
        }
        .error-sidebar-title {
            margin: 0 0 0.65rem;
            font-size: 1.45rem;
            text-transform: uppercase;
        }
        .error-sidebar-text {
            margin: 0 0 1.5rem;
            animation: errorFadeUp 0.55s ease-out 0.34s both;
        }
        .error-list {
            margin: 0;
            padding: 0;
            list-style: none;
        }
        .error-list li {
            display: flex;
            align-items: flex-start;
            gap: 0.8rem;
            padding: 0.9rem 0;
            border-top: 1px solid var(--border-color-light);
            font-size: 0.92rem;
        }
        .error-list li:first-child {
            border-top: 0;
            padding-top: 0;
        }
        .error-list i {
            margin-top: 0.15rem;
            color: var(--color-primary);
            flex-shrink: 0;
        }
        .error-note {
            margin-top: 1.75rem;
            padding: 1rem 1.1rem;
            background: rgba(var(--color-primary-rgb), 0.08);
            border-left: 4px solid var(--color-primary);
            font-size: 0.88rem;
            animation: errorFadeUp 0.6s ease-out 0.5s both;
        }
        .error-footer {
            padding: 0 1.5rem 2rem;
            color: var(--text-muted);
            font-size: 0.82rem;
            text-align: center;
            animation: errorFadeUp 0.55s ease-out 0.58s both;
        }
        @keyframes errorBodyFade {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes errorSlideDown {
            from { opacity: 0; transform: translateY(-18px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes errorSlideUp {
            from { opacity: 0; transform: translateY(28px) scale(0.985); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        @keyframes errorFadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes errorCodeReveal {
            from { opacity: 0; transform: translateY(14px) scale(0.92); letter-spacing: 0.12em; }
            to { opacity: 1; transform: translateY(0) scale(1); letter-spacing: 0; }
        }
        @keyframes errorIconPulse {
            0% { opacity: 0; transform: scale(0.9) rotate(-6deg); }
            60% { opacity: 1; transform: scale(1.04) rotate(0deg); }
            100% { opacity: 1; transform: scale(1); }
        }
        @keyframes errorOrbFloat {
            from { transform: translate3d(0, 0, 0); }
            to { transform: translate3d(0, 16px, 0); }
        }
        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation: none !important;
                transition: none !important;
                scroll-behavior: auto !important;
            }
        }
        @media (max-width: 992px) {
            .error-panel { grid-template-columns: 1fr; }
            .error-sidebar {
                border-left: 0;
                border-top: 1px solid var(--border-color);
            }
        }
        @media (max-width: 768px) {
            .error-hero {
                padding: 1.25rem 1rem 5rem;
            }
            .error-hero-inner {
                flex-direction: column;
                align-items: stretch;
            }
            .error-hero-link,
            .error-button {
                width: 100%;
            }
            .error-main {
                margin-top: -2.75rem;
                padding: 0 1rem 2rem;
            }
            .error-content,
            .error-sidebar {
                padding: 1.5rem;
            }
            .error-display {
                flex-direction: column;
                align-items: flex-start;
            }
            .error-stats {
                grid-template-columns: 1fr;
            }
            .error-actions {
                flex-direction: column;
            }
            .error-footer {
                padding: 0 1rem 1.5rem;
            }
        }
    </style>
</head>
<body>
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
</body>
</html>
