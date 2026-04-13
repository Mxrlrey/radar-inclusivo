@props([
    'class' => 'align-middle text-base text-nowrap',
    'scope' => null
])

<td {{ $attributes->merge(['class' => $class]) }}
    @if($scope === 'row') role="rowheader" @endif
    style="color: var(--text-secondary); padding: var(--table-cell-padding-y) var(--table-cell-padding-x);"
>
    {{ $slot }}
</td>
