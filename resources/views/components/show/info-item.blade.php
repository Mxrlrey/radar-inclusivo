@props([
    'label',
    'value' => null,
    'column' => null,
])

<div class="show-field {{ $column }}">
    <span class="show-label">{{ $label }}</span>
    <div class="show-value">
        @if($slot->isNotEmpty())
            {{ $slot }}
        @else
            {{ $value ?? '---' }}
        @endif
    </div>
</div>
