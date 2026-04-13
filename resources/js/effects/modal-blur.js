// Efeito de blur no fundo ao abrir modais (estilo Custombox)

function initModalBlur() {
    const modals = document.querySelectorAll('.modal');
    // Selecionamos os dois containers que devem "sumir" no fundo
    const blurTargets = document.querySelectorAll('.main-content, .sidebar, .navbar-custom');

    if (!modals.length) return;

    modals.forEach(modal => {
        modal.addEventListener('show.bs.modal', function() {
            document.documentElement.classList.add('custombox-open-blur');
            blurTargets.forEach(el => el.classList.add('modal-blur-backdrop'));
        });

        modal.addEventListener('hidden.bs.modal', function() {
            // Só remove o blur se não houver outros modais abertos (caso de modais sobrepostos)
            if (document.querySelectorAll('.modal.show').length === 0) {
                document.documentElement.classList.remove('custombox-open-blur');
                blurTargets.forEach(el => el.classList.remove('modal-blur-backdrop'));
            }
        });
    });
}

// Executa quando o DOM estiver pronto
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initModalBlur);
} else {
    initModalBlur();
}
