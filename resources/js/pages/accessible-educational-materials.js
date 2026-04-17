/**
 * Lógica dos Campos Digitais
 */
function toggleAssetCodeField() {
    const select = document.querySelector('[name="is_digital"]');
    if (!select) return;

    const isDigital = select.value == "1";

    const input = document.getElementById('asset_code');
    const wrapper = input?.closest('.form-group-horizontal');

    if (!wrapper) return;

    if (isDigital) {
        wrapper.style.display = 'none';
        input.value = '';
    } else {
        wrapper.style.display = 'flex';
    }
}

/**
 * Lógica de Paginação AJAX para Inspeções
 */
function initInspectionPagination() {
    const wrapper = document.getElementById('inspections-table-wrapper');
    if (!wrapper) return;

    wrapper.addEventListener('click', function (e) {
        const link = e.target.closest('.ajax-pagination');

        if (link) {
            e.preventDefault();
            const url = link.getAttribute('href');

            wrapper.style.opacity = '0.5';
            wrapper.style.pointerEvents = 'none';

            fetch(url, {
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                }
            })
                .then(response => response.text())
                .then(html => {
                    wrapper.innerHTML = html;
                    wrapper.style.opacity = '1';
                    wrapper.style.pointerEvents = 'auto';
                    wrapper.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                })
                .catch(err => {
                    console.error('Erro ao carregar inspeções:', err);
                    wrapper.style.opacity = '1';
                    wrapper.style.pointerEvents = 'auto';
                });
        }
    });
}

/**
 * Inicialização Geral
 */
document.addEventListener('DOMContentLoaded', function () {
    // Inicializa campos digitais
    toggleAssetCodeField();
    const selectDigital = document.querySelector('[name="is_digital"]');
    if (selectDigital) {
        selectDigital.addEventListener('change', toggleAssetCodeField);
    }

    initInspectionPagination();
});
