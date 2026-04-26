@props([
    'href' => null,
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
    'type' => 'button'
])

@php
    $sizeClass = match ($size) {
        'sm' => 'sm',
        'lg' => 'lg',
        'xs' => 'xs',
        default => '',
    };

    $hasIcon = !empty($icon);
    $hasText = trim($slot) !== '';

    $isLight = in_array($variant, ['primary','danger','success','new','info','dark','warning']);
    $classes = "btn-action {$variant} {$sizeClass} waves-effect" . ($isLight ? ' waves-light' : '');
    $tag = $href ? 'a' : 'button';
@endphp

<{{ $tag }}
    {{ $href ? "href=$href" : "type=$type" }}
    {{ $attributes->merge([
        'class' => $classes,
    ]) }}
>
@if($hasIcon)
    @if($hasText)
        <span class="btn-label">{{ $icon }}</span>
    @else
        {{ $icon }}
    @endif
@endif

@if($hasText)
    {{ $slot }}
@endif
</{{ $tag }}>
