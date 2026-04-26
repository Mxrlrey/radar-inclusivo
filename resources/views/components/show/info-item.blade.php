@props([
    'label',
    'value' => null,
    'column' => null,
])

<dl class="show-field {{ $column }}">
    <dt class="show-label">{{ $label }}</dt>
    <dd class="show-value">
        @if($slot->isNotEmpty())
            {{ $slot }}
        @else
            {{ $value ?? '---' }}
        @endif
    </dd>
</dl>
