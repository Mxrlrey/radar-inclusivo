@props([
    'name',
    'label' => null,
    'value' => '',
    'placeholder' => '',
    'rows' => 3,
    'required' => false,
    'rich' => true,
    'horizontal' => false,
])

@if($horizontal)
    <div class="form-group-horizontal mb-3">
        @if($label)
            <label for="{{ $name }}" class="control-label">
                {{ $label }}
                @if($required)<i class="text-danger">*</i>@endif
            </label>
        @endif
        <div class="field-wrapper">
            <textarea
                name="{{ $name }}"
                id="{{ $name }}"
                rows="{{ $rows }}"
                placeholder="{{ $placeholder }}"
                {{ ($required && !$rich) ? 'required' : '' }}
                aria-label="{{ $label }}"
                class="form-control custom-input {{ $errors->has($name) ? 'is-invalid' : '' }} {{ $rich ? 'rich-editor' : '' }}"
            >{{ old($name, $value) }}</textarea>
            @error($name)
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
@else
    <div {{ $attributes->merge(['class' => 'mb-3']) }}>
        @if($label)
            <label for="{{ $name }}" class="form-label fw-bold text-primary">
                {{ $label }}
                @if($required)<i class="text-danger">*</i>@endif
            </label>
        @endif
        <textarea
            name="{{ $name }}"
            id="{{ $name }}"
            rows="{{ $rows }}"
            placeholder="{{ $placeholder }}"
            {{ ($required && !$rich) ? 'required' : '' }}
            aria-label="{{ $label }}"
            class="form-control custom-input {{ $errors->has($name) ? 'is-invalid' : '' }} {{ $rich ? 'rich-editor' : '' }}"
        >{{ old($name, $value) }}</textarea>
        @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
@endif
