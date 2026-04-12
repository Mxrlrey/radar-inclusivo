@props([
    'class' => 'align-middle'
])
<th {{ $attributes->merge(['class' => $class]) }}
    style="
        padding: var(--table-cell-padding-y) var(--table-cell-padding-x);
        color: var(--table-header-color);
        background-color: var(--table-header-bg);
    "
>
    {{ $slot }}
</th>
