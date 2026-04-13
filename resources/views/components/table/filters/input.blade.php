@props([
    'name',
    'placeholder' => '',
    'ariaLabel' => null
])

<input
    type="text"
    name="{{ $name }}"
    id="{{ $name }}"
    class="search-input"
    placeholder="{{ $placeholder }}"
    aria-label="{{ $ariaLabel ?? $placeholder }}"
    value="{{ request($name) }}"
    data-filter-input
    {{ $attributes }}
>
