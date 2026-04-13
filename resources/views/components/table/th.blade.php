@props([
    'class' => 'align-middle',
    'scope' => 'col'
])
<th {{ $attributes->merge(['class' => $class]) }}
    scope="{{ $scope }}"
    style="padding: var(--table-cell-padding-y) var(--table-cell-padding-x); color: var(--table-header-color); background-color: var(--table-header-bg);"
>
    {{ $slot }}
</th>
