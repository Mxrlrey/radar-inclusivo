@props([
    'href' => null,
    'variant' => 'primary',
    'size' => 'md',
    'label' => null,
    'type' => 'button'
])

@php
    // Mapeia o tamanho para a classe correta (sm, lg, ou vazio para md)
    $sizeClass = match ($size) {
        'sm' => 'sm',
        'lg' => 'lg',
        default => '',
    };
    $classes = "btn-action {$variant} {$sizeClass}";
    $tag = $href ? 'a' : 'button';
@endphp

<{{ $tag }}
    {{ $href ? "href=$href role=button" : "type=$type" }}
    {{ $attributes->merge([
        'class' => $classes,
        'aria-label' => $label ?? strip_tags($slot)
    ]) }}
>
    {{ $slot }}
</{{ $tag }}>
