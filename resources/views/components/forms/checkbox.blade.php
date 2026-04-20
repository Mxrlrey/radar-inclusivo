@props([
    'name',
    'label',
    'value' => 1,
    'checked' => false,
    'description' => null,
    'id' => null,
    'required' => false,
    'horizontal' => false,
])

@php $checkboxId = $id ?? $name; @endphp

@if($horizontal)
    <div class="form-group-horizontal mb-3">
        <label class="control-label"></label>
        <div class="field-wrapper">
            <div {{ $attributes->merge(['class' => 'custom-checkbox-wrapper']) }}>
                <input
                    type="checkbox"
                    name="{{ $name }}"
                    id="{{ $checkboxId }}"
                    value="{{ $value }}"
                    {{ $checked ? 'checked' : '' }}
                    class="form-check-input custom-checkbox"
                >
                <label class="form-check-label" for="{{ $checkboxId }}">
                    <span class="fw-bold text-primary">
                        {{ $label }}
                        @if($required)<i class="text-danger">*</i>@endif
                    </span>
                    @if($description)
                        <small class="d-block text-muted" style="font-size: 0.75rem;">{{ $description }}</small>
                    @endif
                </label>
            </div>
        </div>
    </div>
@else
    <div {{ $attributes->merge(['class' => 'custom-checkbox-wrapper']) }}>
        <input
            type="checkbox"
            name="{{ $name }}"
            id="{{ $checkboxId }}"
            value="{{ $value }}"
            {{ $checked ? 'checked' : '' }}
            class="form-check-input custom-checkbox"
        >
        <label class="form-check-label" for="{{ $checkboxId }}">
            <span class="fw-bold text-primary">
                {{ $label }}
                @if($required)<i class="text-danger">*</i>@endif
            </span>
            @if($description)
                <small class="d-block text-muted" style="font-size: 0.75rem;">{{ $description }}</small>
            @endif
        </label>
    </div>
@endif
