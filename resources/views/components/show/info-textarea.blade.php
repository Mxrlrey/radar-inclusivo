@props([
    'label',
    'value' => null,
    'column' => null,
    'rich' => true,
])

<div class="show-field {{ $column }}">
    <span class="show-label">{{ $label }}</span>
    <div class="show-value">
        @php $content = $slot->isNotEmpty() ? $slot : ($value ?? '---'); @endphp
        @if($rich)
            {!! $content !!}
        @else
            {!! nl2br(e($content)) !!}
        @endif
    </div>
</div>
