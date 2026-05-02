function onlyDigits(value) {
    return String(value ?? '').replace(/\D/g, '');
}

function formatDateDisplay(value) {
    const digits = onlyDigits(value).slice(0, 8);
    const parts = [digits.slice(0, 2), digits.slice(2, 4), digits.slice(4, 8)].filter(Boolean);

    return parts.join('/');
}

function formatTimeDisplay(value) {
    const digits = onlyDigits(value).slice(0, 4);
    const parts = [digits.slice(0, 2), digits.slice(2, 4)].filter(Boolean);

    return parts.join(':');
}

function formatDateTimeDisplay(value) {
    const digits = onlyDigits(value).slice(0, 12);
    const date = formatDateDisplay(digits.slice(0, 8));
    const time = formatTimeDisplay(digits.slice(8, 12));

    return [date, time].filter(Boolean).join(' ');
}

function toHiddenValue(display, type) {
    const digits = onlyDigits(display);

    if (type === 'date' && digits.length === 8) {
        return `${digits.slice(4, 8)}-${digits.slice(2, 4)}-${digits.slice(0, 2)}`;
    }

    if (type === 'time' && digits.length === 4) {
        return `${digits.slice(0, 2)}:${digits.slice(2, 4)}`;
    }

    if (type === 'datetime-local' && digits.length === 12) {
        return `${digits.slice(4, 8)}-${digits.slice(2, 4)}-${digits.slice(0, 2)}T${digits.slice(8, 10)}:${digits.slice(10, 12)}`;
    }

    return '';
}

function toDisplayValue(value, type) {
    if (!value) return '';

    if (type === 'date') {
        const match = String(value).match(/^(\d{4})-(\d{2})-(\d{2})/);
        return match ? `${match[3]}/${match[2]}/${match[1]}` : value;
    }

    if (type === 'datetime-local') {
        const match = String(value).match(/^(\d{4})-(\d{2})-(\d{2})T? ?(\d{2}):(\d{2})/);
        return match ? `${match[3]}/${match[2]}/${match[1]} ${match[4]}:${match[5]}` : value;
    }

    return value;
}

function formatDisplayValue(value, type) {
    if (type === 'date') return formatDateDisplay(value);
    if (type === 'time') return formatTimeDisplay(value);
    if (type === 'datetime-local') return formatDateTimeDisplay(value);

    return value;
}

function caretPositionForDigitCount(value, digitCount) {
    if (digitCount <= 0) return 0;

    let seenDigits = 0;

    for (let index = 0; index < value.length; index += 1) {
        if (/\d/.test(value[index])) {
            seenDigits += 1;
        }

        if (seenDigits >= digitCount) {
            return index + 1;
        }
    }

    return value.length;
}

function syncPickerValue(display, preserveCaret = false) {
    const hidden = document.getElementById(display.dataset.pickerTarget);
    if (!hidden) return;

    const initialValue = display.value;
    const initialCaret = display.selectionStart ?? initialValue.length;
    const digitsBeforeCaret = onlyDigits(initialValue.slice(0, initialCaret)).length;

    display.value = formatDisplayValue(display.value, display.dataset.pickerType);
    hidden.value = toHiddenValue(display.value, display.dataset.pickerType);

    if (preserveCaret && document.activeElement === display) {
        const caret = caretPositionForDigitCount(display.value, digitsBeforeCaret);
        display.setSelectionRange(caret, caret);
    }
}

function openNativePicker(display) {
    if (!display?.dataset.pickerType) return;

    const nativePicker = document.createElement('input');
    nativePicker.type = display.dataset.pickerType;
    nativePicker.value = document.getElementById(display.dataset.pickerTarget)?.value ?? '';
    nativePicker.tabIndex = -1;
    nativePicker.setAttribute('aria-hidden', 'true');
    nativePicker.style.cssText = 'position:fixed;left:-1000px;top:0;width:1px;height:1px;opacity:0;';

    nativePicker.addEventListener('change', () => {
        const hidden = document.getElementById(display.dataset.pickerTarget);
        if (hidden) hidden.value = nativePicker.value;
        display.value = toDisplayValue(nativePicker.value, display.dataset.pickerType);
        display.dispatchEvent(new Event('input', { bubbles: true }));
        nativePicker.remove();
    });

    nativePicker.addEventListener('blur', () => {
        setTimeout(() => nativePicker.remove(), 150);
    });

    document.body.appendChild(nativePicker);
    nativePicker.focus();

    if (typeof nativePicker.showPicker === 'function') {
        nativePicker.showPicker();
    }
}

document.addEventListener('input', (event) => {
    const display = event.target.closest('[data-picker-display]');
    if (display) syncPickerValue(display, true);
});

document.addEventListener('blur', (event) => {
    const display = event.target.closest('[data-picker-display]');
    if (display) syncPickerValue(display);
}, true);

document.addEventListener('click', (event) => {
    const addon = event.target.closest('.picker-input-group-label');
    if (!addon) return;

    const display = addon.closest('.picker-input-group')?.querySelector('[data-picker-display]');
    openNativePicker(display);
});

document.addEventListener('submit', (event) => {
    event.target.querySelectorAll('[data-picker-display]').forEach(syncPickerValue);
}, true);
