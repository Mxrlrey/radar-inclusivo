@props([
    'class' => 'align-middle text-base text-nowrap'
])
<td {{ $attributes->merge(['class' => $class]) }} style="color: var(--text-secondary); padding: var(--table-cell-padding-y) var(--table-cell-padding-x);">
    {{ $slot }}
</td>
