@props([
    'class' => 'align-middle text-base text-nowrap',
    'scope' => null
])

@if($scope === 'row')
    <th {{ $attributes->merge(['class' => $class]) }}
        scope="row"
        style="color: var(--text-secondary); padding: var(--table-cell-padding-y) var(--table-cell-padding-x);"
    >
        {{ $slot }}
    </th>
@else
    <td {{ $attributes->merge(['class' => $class]) }}
        style="color: var(--text-secondary); padding: var(--table-cell-padding-y) var(--table-cell-padding-x);"
    >
        {{ $slot }}
    </td>
@endif
