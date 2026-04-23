const body = document.body;
const sidebar = document.querySelector('.sidebar');
const toggleBtn = document.getElementById('sidebarToggle');

// Early exit sem return no topo do módulo
if (sidebar && toggleBtn) {
    let isAnimating = false;
    const ANIMATION_TIME = 300;
    const STATE_KEY = 'sidebar-state';
    const SCROLL_KEY = 'sidebar-scroll';
    const DESKTOP_BREAKPOINT = 1024;

    function isDesktop() {
        return window.innerWidth > DESKTOP_BREAKPOINT;
    }

    function restoreState() {
        if (!isDesktop()) {
            body.classList.remove('sidebar-collapsed');
            sidebar.classList.remove('hover-open');
            return;
        }

        const saved = localStorage.getItem(STATE_KEY);
        if (!saved || saved === 'collapsed') {
            body.classList.add('sidebar-collapsed');
        } else {
            body.classList.remove('sidebar-collapsed');
        }
    }

    function saveState() {
        const isCollapsed = body.classList.contains('sidebar-collapsed');
        localStorage.setItem(STATE_KEY, isCollapsed ? 'collapsed' : 'expanded');
    }

    toggleBtn.addEventListener('click', () => {
        if (!isDesktop()) return;
        if (isAnimating) return;

        isAnimating = true;
        sidebar.classList.add('animating');
        body.classList.toggle('sidebar-collapsed');
        saveState();

        setTimeout(() => {
            sidebar.classList.remove('animating');
            isAnimating = false;
        }, ANIMATION_TIME);
    });

    function saveSidebarScroll() {
        localStorage.setItem(SCROLL_KEY, sidebar.scrollTop);
    }

    document.querySelectorAll('.sidebar a').forEach(link => {
        link.addEventListener('click', saveSidebarScroll);
    });

    window.addEventListener('beforeunload', saveSidebarScroll);

    window.addEventListener('load', () => {
        restoreState();

        const saved = localStorage.getItem(SCROLL_KEY);
        if (!saved) return;

        smoothScrollSidebar(parseInt(saved, 10), 400);
    });

    window.addEventListener('resize', restoreState);

    function smoothScrollSidebar(target, duration) {
        const start = sidebar.scrollTop;
        const change = target - start;
        const startTime = performance.now();

        function animateScroll(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const ease = progress < 0.5
                ? 2 * progress * progress
                : 1 - Math.pow(-2 * progress + 2, 2) / 2;

            sidebar.scrollTop = start + change * ease;

            if (progress < 1) requestAnimationFrame(animateScroll);
        }

        requestAnimationFrame(animateScroll);
    }
}
