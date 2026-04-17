function initInspectionPagination() {
    const wrapper = document.getElementById('inspections-table-wrapper-barrier');
    if (!wrapper) return;

    wrapper.addEventListener('click', function (e) {
        const link = e.target.closest('.ajax-pagination');

        if (!link) return;

        e.preventDefault();
        const url = link.getAttribute('href');

        wrapper.style.opacity = '0.5';
        wrapper.style.pointerEvents = 'none';

        fetch(url, {
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            }
        })
            .then(res => res.text())
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
    });
}

document.addEventListener('DOMContentLoaded', function () {
    initInspectionPagination();
});
