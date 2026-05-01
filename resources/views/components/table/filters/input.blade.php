@props([
    'name',
    'ariaLabel' => null,
    'placeholder' => null
])

<input
    type="text"
    name="{{ $name }}"
    id="{{ $name }}"
    class="search-input"
    aria-label="{{ $ariaLabel ?? 'Filtro de busca' }}"
    placeholder="{{ $placeholder ?? $ariaLabel ?? 'Filtro de busca' }}"
    value="{{ request($name) }}"
    data-filter-input
    {{ $attributes }}
>
