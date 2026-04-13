// Timeline animation on scroll (Ubold style)
function initTimelineAnimation() {
    document.documentElement.classList.add('cssanimations');
    const timelineBlocks = document.querySelectorAll('.cd-timeline-block');
    if (!timelineBlocks.length) return;

    // Oculta blocos que estão fora da viewport inicialmente
    function hideOffscreenBlocks() {
        timelineBlocks.forEach(block => {
            const img = block.querySelector('.cd-timeline-img');
            const content = block.querySelector('.cd-timeline-content');
            if (!img || !content) return;

            const blockTop = block.getBoundingClientRect().top;
            const windowHeight = window.innerHeight;

            if (blockTop > windowHeight * 0.75) {
                img.classList.add('is-hidden');
                content.classList.add('is-hidden');
            }
        });
    }

    // Revela blocos quando entram na viewport
    function revealBlocksOnScroll() {
        timelineBlocks.forEach(block => {
            const img = block.querySelector('.cd-timeline-img');
            const content = block.querySelector('.cd-timeline-content');
            if (!img || !content) return;

            const blockTop = block.getBoundingClientRect().top;
            const windowHeight = window.innerHeight;

            if (blockTop <= windowHeight * 0.75 && img.classList.contains('is-hidden')) {
                img.classList.remove('is-hidden');
                img.classList.add('bounce-in');
                content.classList.remove('is-hidden');
                content.classList.add('bounce-in');
            }
        });
    }

    // Inicialização
    hideOffscreenBlocks();
    window.addEventListener('scroll', revealBlocksOnScroll);
    // Reexecuta em redimensionamento para garantir
    window.addEventListener('resize', hideOffscreenBlocks);
}

// Executa quando o DOM estiver pronto
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initTimelineAnimation);
} else {
    initTimelineAnimation();
}
