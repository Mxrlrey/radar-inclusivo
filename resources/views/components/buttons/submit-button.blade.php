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
        'xs' => 'xs',
        default => '',
    };
    $classes = "btn-action {$variant} {$sizeClass} waves-effect";
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
