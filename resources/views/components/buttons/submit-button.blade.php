@props([
    'variant' => 'primary',
    'size' => 'md',
    'label' => null,
    'disabled' => false,
    'icon' => null,
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
    type="submit"
    {{ $attributes->merge([
        'class' => $classes,
        'aria-label' => $label ?? 'Enviar formulário',
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
