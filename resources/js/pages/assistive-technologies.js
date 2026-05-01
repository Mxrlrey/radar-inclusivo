/**
 * Lógica dos Campos Digitais
 */
function toggleAssetCodeField() {
    const select = document.querySelector('select[name="is_digital"]');
    if (!select) return;

    const isDigital = select.value == "1";

    const input = document.getElementById('asset_code');
    const wrapper = input?.closest('.form-group-horizontal');

    if (!wrapper) return;

    if (isDigital) {
        wrapper.style.display = 'none';
        input.value = '';
        input.disabled = true;
        input.setAttribute('aria-hidden', 'true');
    } else {
        wrapper.style.display = 'flex';
        input.disabled = false;
        input.removeAttribute('aria-hidden');
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
                })
                .catch(() => {
                    wrapper.style.opacity = '1';
                    wrapper.style.pointerEvents = 'auto';
                });
        }
    });
}

/**
 * Lógica de Redirecionamento da Vistoria
 */
function initInspectionRedirects() {
    const timeline = document.querySelector('.history-timeline');
    if (!timeline) return;

    timeline.onclick = (e) => {
        const card = e.target.closest('.cursor-pointer');
        if (card?.dataset.url) {
            window.location.href = card.dataset.url;
        }
    };

    timeline.onkeydown = (e) => {
        const card = e.target.closest('.cursor-pointer');
        if (card && (e.key === 'Enter' || e.key === ' ')) {
            e.preventDefault();
            window.location.href = card.dataset.url;
        }
    };
}

// Inicialização Geral
document.addEventListener('DOMContentLoaded', function () {
    toggleAssetCodeField();
    toggleQuantityField();

    const selectDigital = document.querySelector('[name="is_digital"]');
    if (selectDigital) {
        selectDigital.addEventListener('change', function () {
            toggleAssetCodeField();
            toggleQuantityField();
        });
    }

    initInspectionRedirects();
    initInspectionPagination();
});
