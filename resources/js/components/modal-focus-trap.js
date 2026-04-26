const FOCUSABLE_SELECTOR = [
    'a[href]',
    'area[href]',
    'button:not([disabled])',
    'input:not([disabled]):not([type="hidden"])',
    'select:not([disabled])',
    'textarea:not([disabled])',
    'iframe',
    'object',
    'embed',
    '[contenteditable]',
    '[tabindex]:not([tabindex="-1"])',
].join(', ');

const modalOpeners = new WeakMap();

function enhanceModalTriggers() {
    const modalTriggerPattern = /bootstrap\.Modal\(document\.getElementById\('([^']+)'\)\)\.show\(\)/;

    document.querySelectorAll('[onclick]').forEach((element) => {
        const onclick = element.getAttribute('onclick') || '';
        const match = onclick.match(modalTriggerPattern);

        if (!match) {
            return;
        }

        const modalId = match[1];
        element.setAttribute('aria-haspopup', 'dialog');
        element.setAttribute('aria-controls', modalId);
    });
}

function isVisible(element) {
    return !!(element.offsetWidth || element.offsetHeight || element.getClientRects().length);
}

function getFocusableElements(modal) {
    return Array.from(modal.querySelectorAll(FOCUSABLE_SELECTOR)).filter((element) => {
        return isVisible(element) && !element.hasAttribute('disabled') && element.getAttribute('aria-hidden') !== 'true';
    });
}

function focusInitialElement(modal) {
    const autofocusElement = modal.querySelector('[autofocus]');
    if (autofocusElement && isVisible(autofocusElement)) {
        autofocusElement.focus();
        return;
    }

    const [firstFocusable] = getFocusableElements(modal);
    if (firstFocusable) {
        firstFocusable.focus();
        return;
    }

    const dialog = modal.querySelector('.modal-dialog');
    if (dialog) {
        if (!dialog.hasAttribute('tabindex')) {
            dialog.setAttribute('tabindex', '-1');
        }
        dialog.focus();
    }
}

document.addEventListener('show.bs.modal', (event) => {
    const modal = event.target;
    const activeElement = document.activeElement;

    if (activeElement && !modal.contains(activeElement)) {
        modalOpeners.set(modal, activeElement);
    }
});

document.addEventListener('shown.bs.modal', (event) => {
    focusInitialElement(event.target);
});

document.addEventListener('keydown', (event) => {
    if (event.key !== 'Tab') {
        return;
    }

    const modals = Array.from(document.querySelectorAll('.modal.show'));
    const modal = modals[modals.length - 1];
    if (!modal) {
        return;
    }

    const focusableElements = getFocusableElements(modal);
    if (!focusableElements.length) {
        event.preventDefault();
        focusInitialElement(modal);
        return;
    }

    const firstFocusable = focusableElements[0];
    const lastFocusable = focusableElements[focusableElements.length - 1];
    const activeElement = document.activeElement;

    if (event.shiftKey) {
        if (activeElement === firstFocusable || !modal.contains(activeElement)) {
            event.preventDefault();
            lastFocusable.focus();
        }
        return;
    }

    if (activeElement === lastFocusable || !modal.contains(activeElement)) {
        event.preventDefault();
        firstFocusable.focus();
    }
});

document.addEventListener('hidden.bs.modal', (event) => {
    const modal = event.target;
    const opener = modalOpeners.get(modal);

    if (opener && opener.isConnected) {
        opener.focus();
    }

    modalOpeners.delete(modal);
});

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', enhanceModalTriggers);
} else {
    enhanceModalTriggers();
}
