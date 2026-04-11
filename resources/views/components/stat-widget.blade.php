@props([
    'title',
    'value',
    'icon' => 'bi-people',
    'color' => 'primary',
    'href' => null,
])

@php
    $tag = $href ? 'a' : 'div';
    $extraAttributes = [];
    if ($href) $extraAttributes['href'] = $href;
@endphp

<{{ $tag }}
    {{ $attributes->merge(array_merge(['class' => 'stat-widget'], $extraAttributes)) }}
>
<div class="stat-widget-content">
    <span class="stat-widget-value">{{ $value }}</span>
    <span class="stat-widget-title">{{ $title }}</span>
</div>
<div class="stat-widget-icon text-{{ $color }}">
    <i class="{{ $icon }}"></i>
</div>
</{{ $tag }}>
