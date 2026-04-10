@props(['label', 'value' => null, 'column' => 'col-md-6', 'isBox' => false])

<div {{ $attributes->merge(['class' => $column . ' mb-4 px-4']) }}>
    {{-- Título visual do campo --}}
    <span class="d-block fw-bold text-primary small mb-1 text-uppercase" aria-hidden="true">
        {{ $label }}
    </span>

    @php
        $displayValue = $slot->isNotEmpty() ? $slot : ($value ?? '---');
        $plainTextValue = strip_tags($displayValue);
    @endphp

    <div class="{{ $isBox ? 'custom-display-box' : 'text-base' }}"
         style="{{ !$isBox ? 'color: var(--text-primary); font-size: 1.05rem;' : '' }}"
         role="text"
         aria-label="{{ $label }}: {{ $plainTextValue }}">
        {{ $displayValue }}
    </div>
</div>
