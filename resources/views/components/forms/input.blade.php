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
    $hasPickerAddon = in_array($type, ['date', 'time'], true);
    $pickerIcon = $type === 'time' ? 'fa-clock-o' : 'fa-calendar';
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
            @if($hasPickerAddon)
                <div
                    class="picker-input-group"
                    onclick="const input = this.querySelector('.picker-input-control'); if (!input) return; input.focus(); if (event.target === input) return; if (typeof input.showPicker === 'function') { input.showPicker(); }"
                >
                    <input
                        type="{{ $type }}"
                        name="{{ $name }}"
                        id="{{ $cleanId }}"
                        value="{{ old($name, $value) }}"
                        placeholder="{{ $placeholder }}"
                        @if($required) required aria-required="true" @endif
                        autocomplete="off"
                        {{ $inputAttributes->merge(['class' => 'form-control custom-input picker-input-control' . ($errors->has($name) ? ' is-invalid' : '')]) }}
                    >
                    <span class="picker-input-group-label" aria-hidden="true">
                        <i class="fa {{ $pickerIcon }}"></i>
                    </span>
                </div>
            @else
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
            @endif
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
        @if($hasPickerAddon)
            <div
                class="picker-input-group"
                onclick="const input = this.querySelector('.picker-input-control'); if (!input) return; input.focus(); if (event.target === input) return; if (typeof input.showPicker === 'function') { input.showPicker(); }"
            >
                <input
                    type="{{ $type }}"
                    name="{{ $name }}"
                    id="{{ $cleanId }}"
                    value="{{ old($name, $value) }}"
                    placeholder="{{ $placeholder }}"
                    @if($required) required aria-required="true" @endif
                    autocomplete="off"
                    {{ $inputAttributes->merge(['class' => 'form-control custom-input picker-input-control' . ($errors->has($name) ? ' is-invalid' : '')]) }}
                >
                <span class="picker-input-group-label" aria-hidden="true">
                    <i class="fa {{ $pickerIcon }}"></i>
                </span>
            </div>
        @else
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
        @endif
        @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
@endif
