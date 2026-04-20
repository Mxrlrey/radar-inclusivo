@props([
    'name' => null,
    'label' => null,
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'horizontal' => false,
])

@php
    $cleanId = str_replace(['[', ']'], '', $name);
    $wrapperClasses = $attributes->get('class', 'mb-4');
    $inputAttributes = $attributes->except(['class']);
@endphp

@if($horizontal)
    <div class="form-group-horizontal {{ $wrapperClasses }}">
        @if($label)
            <label for="{{ $cleanId }}" class="control-label">
                {{ $label }}
                @if($required)<i class="text-danger">*</i>@endif
            </label>
        @endif
        <div class="field-wrapper">
            <input
                type="{{ $type }}"
                name="{{ $name }}"
                id="{{ $cleanId }}"
                value="{{ old($name, $value) }}"
                placeholder="{{ $placeholder }}"
                @if($required) required aria-required="true" @endif
                autocomplete="off"
                {{ $inputAttributes->merge(['class' => 'form-control custom-input' . ($errors->has($name) ? ' is-invalid' : '')]) }}
            >
            @error($name)
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
@else
    <div class="{{ $wrapperClasses }}">
        @if($label)
            <label for="{{ $cleanId }}" class="form-label fw-bold text-primary">
                {{ $label }}
                @if($required)<i class="text-danger">*</i>@endif
            </label>
        @endif
        <input
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $cleanId }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            @if($required) required aria-required="true" @endif
            autocomplete="off"
            {{ $inputAttributes->merge(['class' => 'form-control custom-input' . ($errors->has($name) ? ' is-invalid' : '')]) }}
        >
        @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
@endif
