<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha - GNAI</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    @vite(['resources/css/app.css'])
</head>
<body>

<div class="login-wrapper">
    <div class="login-split-card">

        {{-- LADO MARCA --}}
        <div class="login-brand-side">
            <div class="brand-content">
                <h1 class="welcome-title">
                    Recuperação de acesso
                </h1>
                <p class="brand-tagline">
                    Informe seu e-mail institucional e enviaremos um link seguro para redefinir sua senha.
                </p>
            </div>
        </div>

        {{-- FORM --}}
        <div class="login-form-side">
            <div class="form-content">

                <div class="mb-4">
                    <h2 class="form-title">Esqueceu sua senha?</h2>
                    <p class="text-muted small">
                        Digite seu e-mail para receber o link de redefinição.
                    </p>
                </div>

                @if(session('status'))
                    <div class="alert alert-success border-0 small mb-4">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label">E-mail</label>
                        <input type="email"
                               name="email"
                               class="form-control"
                               placeholder="exemplo@ifbaiano.edu.br"
                               required>
                    </div>

                    <button type="submit" class="btn-action primary lg submit w-100">
                        Enviar link <i class="fas fa-paper-plane ms-2"></i>
                    </button>
                </form>

                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="small text-muted">
                        Voltar para login
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
