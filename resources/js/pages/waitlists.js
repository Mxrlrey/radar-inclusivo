document.addEventListener('DOMContentLoaded', function () {
    const studentSelect = document.getElementById('student_id');
    const professionalSelect = document.getElementById('professional_id');
    const typeSelect = document.getElementById('waitlistable_type');
    const itemSelect = document.getElementById('waitlistable_id');
    const waitlistData = window.waitlistData;

    // --- Popula itens conforme tipo selecionado ---
    function updateItems() {
        if (!typeSelect || !itemSelect || !waitlistData) return;

        const selectedType = typeSelect.value;

        if (!selectedType) {
            itemSelect.innerHTML = '<option value=""></option>';
            itemSelect.disabled = true;
            return;
        }

        itemSelect.disabled = false;
        itemSelect.innerHTML = '<option value=""></option>';

        const availableItems = waitlistData.items[selectedType] || [];

        if (availableItems.length > 0) {
            availableItems.forEach(item => {
                const option = document.createElement('option');
                option.value = String(item.id);
                const displayName = item.name || item.title || item.description || 'Item sem identificação';
                const assetCode = item.asset_code || 'S/N';
                option.text = `${displayName} (${assetCode})`;
                if (String(item.id) === String(waitlistData.oldId)) {
                    option.selected = true;
                }
                itemSelect.appendChild(option);
            });
        } else {
            itemSelect.innerHTML = '<option value=""></option>';
        }
    }

    if (typeSelect) {
        typeSelect.addEventListener('change', updateItems);
        updateItems(); // inicializa se já houver valor
    }

    // --- Bloqueia aluno/profissional mutuamente ---
    function setupToggle(primary, secondary) {
        if (!primary || !secondary) return;

        primary.addEventListener('change', () => {
            if (primary.value) {
                secondary.value = '';
                secondary.disabled = true;
                secondary.parentElement.style.opacity = '0.6';
            } else {
                secondary.disabled = false;
                secondary.parentElement.style.opacity = '1';
            }
        });
    }

    setupToggle(studentSelect, professionalSelect);
    setupToggle(professionalSelect, studentSelect);

    if (studentSelect?.value) {
        professionalSelect.disabled = true;
        professionalSelect.parentElement.style.opacity = '0.6';
    }
    if (professionalSelect?.value) {
        studentSelect.disabled = true;
        studentSelect.parentElement.style.opacity = '0.6';
    }
});
