@props([
    'variant' => 'primary',
    'size' => 'md',
    'label' => null,
    'disabled' => false,
    'icon' => null,
    'type' => 'submit',
])

@php
    $sizeClass = match ($size) {
        'sm' => 'sm',
        'lg' => 'lg',
        'xs' => 'xs',
        default => '',
    };

    $hasIcon = !empty($icon);
    $hasText = trim($slot) !== '';
    $isLight = in_array($variant, ['primary','danger','success','new','info','dark','warning']);
    $classes = "btn-action {$variant} {$sizeClass} waves-effect" . ($isLight ? ' waves-light' : '');
@endphp

<button
    type="{{ $type }}"
    {{ $attributes->merge([
        'class' => $classes,
        'aria-label' => $label ?? ($hasText ? trim(strip_tags((string) $slot)) : 'Enviar formulário'),
        'disabled' => $disabled,
    ]) }}
>
    @if($hasIcon)
        @if($hasText)
            <span class="btn-label">{{ $icon }}</span>
        @else
            {{ $icon }}
        @endif
    @endif

    @if($hasText)
        {{ $slot }}
    @endif
</button>
