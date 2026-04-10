@props([
    'class' => 'align-middle fw-bold text-primary'
])
<th {{ $attributes->merge(['class' => $class]) }} style="padding: var(--table-cell-padding-y) var(--table-cell-padding-x);">
    {{ $slot }}
</th>
