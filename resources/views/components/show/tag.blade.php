@props([
    'color' => 'light',
])

@php
    $style = $color === 'dark'
        ? 'background-color: var(--color-primary); color: var(--text-on-primary); border-color: var(--color-primary);'
        : 'background-color: var(--bg-soft-primary); color: var(--color-primary); border-color: var(--color-primary);';
@endphp

<span {{ $attributes->merge(['class' => 'tag-item', 'style' => $style]) }}>
    {{ $slot }}
</span>
