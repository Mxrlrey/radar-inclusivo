document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.photo-upload').forEach(component => {
        const container = component.querySelector('[data-container]');
        const input = component.querySelector('[data-input]');
        const image = component.querySelector('[data-image]');
        const empty = component.querySelector('[data-empty]');
        const removeBtn = component.querySelector('[data-remove]');
        const removeFlag = component.querySelector('[data-remove-flag]');
        const status = component.querySelector('[data-status]');

        if (!container || !input || !image || !empty || !removeBtn) return;

        const setStatus = (message) => {
            if (status) {
                status.textContent = message;
            }
        };

        const resetPreview = (announce = true) => {
            input.value = '';
            image.src = '';
            image.alt = '';
            image.classList.add('d-none');
            empty.classList.remove('d-none');
            removeBtn.classList.add('d-none');

            if (removeFlag) {
                removeFlag.value = 1;
            }

            if (announce) {
                setStatus('Foto removida.');
            }
        };

        container.addEventListener('click', () => {
            input.click();
        });

        container.addEventListener('keydown', (event) => {
            if (event.key !== ' ' && event.key !== 'Enter') return;
            event.preventDefault();
            input.click();
        });

        input.addEventListener('change', () => {
            const file = input.files[0];
            if (!file) return;

            if (!file.type.startsWith('image/')) {
                resetPreview(false);
                if (removeFlag) {
                    removeFlag.value = 0;
                }
                setStatus('Selecione uma imagem válida.');
                return;
            }

            const reader = new FileReader();

            reader.onload = e => {
                image.src = e.target.result;
                image.alt = `Pré-visualização da imagem ${file.name}`;
                image.classList.remove('d-none');
                empty.classList.add('d-none');
                removeBtn.classList.remove('d-none');

                if (removeFlag) {
                    removeFlag.value = 0;
                }

                setStatus(`Foto ${file.name} selecionada.`);
            };

            reader.readAsDataURL(file);
        });

        removeBtn.addEventListener('click', e => {
            e.stopPropagation();
            resetPreview();
        });
    });
});
