@props([
    'name',
    'ariaLabel' => null
])

<input
    type="text"
    name="{{ $name }}"
    id="{{ $name }}"
    class="search-input"
    aria-label="{{ $ariaLabel ?? 'Filtro de busca' }}"
    value="{{ request($name) }}"
    data-filter-input
    {{ $attributes }}
>
