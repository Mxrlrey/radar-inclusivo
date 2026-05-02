const animatedControls = new WeakMap();

function controlValue(control) {
    if (control.type === 'checkbox' || control.type === 'radio') {
        return control.checked ? control.value : '';
    }

    return control.value ?? '';
}

function select2Container(control) {
    const container = control.nextElementSibling;

    if (container?.classList.contains('select2-container')) {
        return container;
    }

    return null;
}

function restartAnimation(element) {
    element.classList.remove('form-value-animating');
    void element.offsetWidth;
    element.classList.add('form-value-animating');
}

function animateChangedValue(control) {
    const previousValue = animatedControls.get(control);
    const currentValue = controlValue(control);

    animatedControls.set(control, currentValue);

    if (previousValue === undefined || previousValue === currentValue || String(currentValue).trim() === '') {
        return;
    }

    if (control.matches('select.form-control, select.form-select, select.custom-input')) {
        restartAnimation(control);
    }

    const select2 = select2Container(control);
    if (select2) {
        restartAnimation(select2);
    }
}

function rememberInitialValues() {
    document
        .querySelectorAll('input.form-control, input.custom-input, select.form-control, select.form-select, select.custom-input, textarea.form-control, textarea.custom-input')
        .forEach((control) => animatedControls.set(control, controlValue(control)));
}

document.addEventListener('DOMContentLoaded', rememberInitialValues);

document.addEventListener('input', (event) => {
    const control = event.target.closest('input.form-control, input.custom-input, textarea.form-control, textarea.custom-input');
    if (control) {
        control.classList.add('form-value-editing');
        animateChangedValue(control);
    }
});

document.addEventListener('change', (event) => {
    const control = event.target.closest('input.form-control, input.custom-input, select.form-control, select.form-select, select.custom-input, textarea.form-control, textarea.custom-input');
    if (control) {
        animateChangedValue(control);
    }
});

document.addEventListener('blur', (event) => {
    const control = event.target.closest('input.form-control, input.custom-input, textarea.form-control, textarea.custom-input');
    control?.classList.remove('form-value-editing');
}, true);

document.addEventListener('animationend', (event) => {
    event.target.classList.remove('form-value-animating');
    event.target.closest('.select2-container.form-value-animating')?.classList.remove('form-value-animating');
});
