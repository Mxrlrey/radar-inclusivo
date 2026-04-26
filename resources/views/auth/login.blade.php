<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - GNAI</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/css/auth/auth.css'])
</head>
<body>

<div class="login-wrapper">
    <div class="login-split-card">

        <div class="login-brand-side">
            <div class="brand-content">
                <h1 class="welcome-title">
                    Bem-vindo(a) ao <br>
                    <strong>RADAR INCLUSIVO</strong>
                </h1>
                <p class="brand-tagline">
                    Uma plataforma completa para <strong>gestão de recursos nos NAIs</strong>,
                    integrando alunos, equipe, tecnologias assistivas, empréstimos e barreiras
                    em um único ambiente.
                </p>
            </div>
        </div>

        <div class="login-form-side">
            <div class="form-content">

                <div class="mb-4">
                    <h2 class="form-title">Acesse sua conta</h2>
                    <p class="text-muted small">Insira suas credenciais para continuar.</p>
                </div>

                @if(session('error'))
                    <div class="alert alert-danger border-0 small mb-4">
                        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <!-- form-label já tem font-weight e cor via forms.css -->
                        <label class="form-label">E-mail</label>
                        <!-- form-control já aplica altura, borda, foco, bg via forms.css -->
                        <input type="email" name="email"
                               class="form-control"
                               placeholder="exemplo@ifbaiano.edu.br"
                               required autofocus>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Senha</label>
                        <input type="password" name="password"
                               class="form-control"
                               placeholder="••••••••"
                               required>
                    </div>

                    <div class="text-end mb-4">
                        <a href="{{ route('password.request') }}" class="forgot-password-link">
                            Esqueceu sua senha?
                        </a>
                    </div>

                    <!-- btn-action primary lg submit = botão grande, cor primária, peso semibold -->
                    <button type="submit" class="btn-action primary lg submit w-100">
                        Entrar no Sistema <i class="fas fa-arrow-right ms-2"></i>
                    </button>

                </form>

                <div class="mt-5 text-center">
                    <p class="footer-copy">Radar Inclusivo © {{ date('Y') }} | Gestão NAIs</p>
                </div>

            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
