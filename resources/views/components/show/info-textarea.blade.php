@props([
    'label',
    'value' => null,
    'column' => null,
    'rich' => true,
])

<dl class="show-field {{ $column }}">
    <dt class="show-label">{{ $label }}</dt>
    <dd class="show-value">
        @php $content = $slot->isNotEmpty() ? $slot : ($value ?? '---'); @endphp
        @if($rich)
            {!! $content !!}
        @else
            {!! nl2br(e($content)) !!}
        @endif
    </dd>
</dl>
