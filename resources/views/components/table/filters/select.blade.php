@props([
    'name',
    'options' => [],
    'ariaLabel' => null
])

<select
    name="{{ $name }}"
    id="{{ $name }}"
    class="filter-select"
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
