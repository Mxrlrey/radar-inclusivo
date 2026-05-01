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

function toggleQuantityField() {
    const select = document.querySelector('[name="is_digital"]');
    const input = document.getElementById('quantity');
    const wrapper = input?.closest('.form-group-horizontal');

    if (!select || !input || !wrapper) return;

    const isDigital = select.value == "1";
    const legacyDigitalQuantitySentinel = input.dataset.legacyDigitalQuantitySentinel === "1";

    if (isDigital) {
        if (input.value && input.value !== "999") {
            input.dataset.lastPhysicalQuantity = input.value;
        }

        wrapper.style.display = 'none';
        input.disabled = true;
        input.required = false;
        return;
    }

    wrapper.style.display = 'flex';
    input.disabled = false;
    input.required = true;

    if (legacyDigitalQuantitySentinel || input.value === "" || input.value === "999") {
        input.value = input.dataset.lastPhysicalQuantity || "1";
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
    toggleQuantityField();

    const selectDigital = document.querySelector('[name="is_digital"]');
    if (selectDigital) {
        selectDigital.addEventListener('change', function () {
            toggleAssetCodeField();
            toggleQuantityField();
        });
    }

    initInspectionPagination();
});
