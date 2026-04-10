@props([
    'variant' => 'primary',
    'size' => 'md',
    'label' => null,
    'disabled' => false,
])

@php
    $sizeClass = match ($size) {
        'sm' => 'sm',
        'lg' => 'lg',
        default => '',
    };
    $classes = "btn-action {$variant} {$sizeClass} d-inline-flex align-items-center justify-content-center";
@endphp

<button
    type="submit"
    {{ $attributes->merge([
        'class' => $classes,
        'aria-label' => $label ?? 'Enviar formulário',
        'disabled' => $disabled,
    ]) }}
>
    {{ $slot }}
</button>
