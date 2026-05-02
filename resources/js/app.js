import './bootstrap';
import './pages/messages.js';
import './pages/type-attributes.js';
import './components/search-filter.js';
import './utils/cpf.js';
import './utils/phone.js';
import './partials/sidebar.js';
import './components/collapsible-section';
import './components/modal-focus-trap.js';
import './components/form-value-animation.js';
import './effects/waves.js';
import './effects/modal-blur.js';

class App {
    constructor() {
        this.init();
    }

    init() {
        this.initSidebar();
        this.initActiveMenu();
        this.initBootstrapDropdowns();
    }

    initSidebar() {
        this.sidebar = document.querySelector('.sidebar');
        this.sidebarToggle = document.querySelector('#sidebarToggle');

        if (this.sidebarToggle) {
            this.sidebarToggle.addEventListener('click', (e) => {
                e.preventDefault();
                this.toggleSidebar();
            });
        }

        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 1024 &&
                this.sidebar && this.sidebar.classList.contains('show') &&
                !this.sidebar.contains(e.target) &&
                this.sidebarToggle && !this.sidebarToggle.contains(e.target)) {
                this.sidebar.classList.remove('show');
                this.removeOverlay();
            }
        });
    }

    toggleSidebar() {
        if (!this.sidebar) return;

        if (this.sidebarToggle && window.innerWidth > 1024) {
            this.sidebarToggle.setAttribute('aria-expanded', 'false');
        }

        if (window.innerWidth <= 1024) {
            this.sidebar.classList.toggle('show');
            const isOpen = this.sidebar.classList.contains('show');
            if (this.sidebarToggle) {
                this.sidebarToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            }

            if (isOpen) {
                this.addOverlay();
                document.body.style.overflow = 'hidden';
            } else {
                this.removeOverlay();
                document.body.style.overflow = '';
            }
        }
    }

    initBootstrapDropdowns() {
        document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(el => {
            if (!bootstrap.Dropdown.getInstance(el)) {
                new bootstrap.Dropdown(el);
            }
        });
    }

    addOverlay() {
        if (!document.querySelector('.sidebar-overlay')) {
            const overlay = document.createElement('div');
            overlay.className = 'sidebar-overlay';
            overlay.style.cssText = `
                position: fixed; top: 0; left: 0; right: 0; bottom: 0;
                background: rgba(0,0,0,0.5); z-index: 999; display: block;
            `;
            overlay.addEventListener('click', () => this.toggleSidebar());
            document.body.appendChild(overlay);
        }
    }

    removeOverlay() {
        const overlay = document.querySelector('.sidebar-overlay');
        if (overlay) {
            overlay.remove();
            document.body.style.overflow = '';
        }
    }

    initActiveMenu() {
        const currentPath = window.location.pathname;
        document.querySelectorAll('.sidebar-menu a').forEach(item => {
            const href = item.getAttribute('href');
            if (href && currentPath.includes(href.replace('/', ''))) {
                item.classList.add('active');
                const parentGroup = item.closest('.menu-group');
                if (parentGroup) parentGroup.classList.add('expanded');
            }
        });
    }

    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show`;
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        const container = document.querySelector('.main-content');
        if (container) {
            container.insertBefore(notification, container.firstChild);
            setTimeout(() => { if (notification.parentNode) notification.remove(); }, 5000);
        }
    }
}

// Notificações (deduplicado — mantém só uma versão)
document.addEventListener('click', function(e) {
    const item = e.target.closest('.notify-item');
    if (!item) return;

    e.preventDefault();
    const id = item.dataset.id;
    const url = item.getAttribute('href');

    axios.post(`/notifications/${id}/read`)
        .then(() => {
            const badge = document.getElementById('notif-count');
            if (badge) {
                const value = parseInt(badge.textContent) - 1;
                if (value <= 0) badge.remove();
                else badge.textContent = value;
            }
            if (url && url !== '#') window.location = url;
        })
        .catch(() => {
            if (url && url !== '#') window.location = url;
        });
});

document.addEventListener('DOMContentLoaded', () => {
    window.app = new App();

    // Expõe toggleSidebar globalmente via instância
    window.toggleSidebar = () => window.app.toggleSidebar();

    const yearElement = document.querySelector('[data-year]');
    if (yearElement) yearElement.textContent = new Date().getFullYear();

    [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        .map(el => new bootstrap.Tooltip(el));

    [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
        .map(el => new bootstrap.Popover(el));
});
