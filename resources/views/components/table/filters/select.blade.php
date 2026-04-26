@props([
    'name',
    'options' => [],
    'ariaLabel' => null
])

<select
    name="{{ $name }}"
    id="{{ $name }}"
    class="filter-select {{ filled(request()->query($name)) ? 'is-active' : '' }}"
    data-filter-input
    aria-label="{{ $ariaLabel }}"
>
    @foreach($options as $value => $labelOption)
        <option value="{{ $value }}"
            @selected(request()->query($name) !== null && request()->query($name) == (string)$value)>
            {{ $labelOption }}
        </option>
    @endforeach
</select>
