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

@php
    $checkboxId = $id ?? $name;
    $checkboxAttributes = $attributes->except('class');
    $descriptionId = $description ? $checkboxId . '-description' : null;
    $existingDescribedBy = trim((string) $checkboxAttributes->get('aria-describedby', ''));
    $describedBy = trim(implode(' ', array_filter([
        $existingDescribedBy,
        $descriptionId,
    ])));
@endphp

@if($horizontal)
    <div class="form-group-horizontal mb-3">
        <label class="control-label"></label>
        <div class="field-wrapper">
            <div {{ $attributes->only('class')->merge(['class' => 'custom-checkbox-wrapper']) }}>
                <input
                    type="checkbox"
                    name="{{ $name }}"
                    id="{{ $checkboxId }}"
                    value="{{ $value }}"
                    {{ $checked ? 'checked' : '' }}
                    class="form-check-input custom-checkbox"
                    @if($describedBy !== '') aria-describedby="{{ $describedBy }}" @endif
                    {{ $checkboxAttributes }}
                >
                <label class="form-check-label" for="{{ $checkboxId }}">
                    <span class="fw-bold text-primary">
                        {{ $label }}
                        @if($required)<i class="text-danger" aria-hidden="true">*</i>@endif
                    </span>
                    @if($description)
                        <small class="d-block text-muted" style="font-size: 0.75rem;" id="{{ $descriptionId }}">{{ $description }}</small>
                    @endif
                </label>
            </div>
        </div>
    </div>
@else
    <div {{ $attributes->only('class')->merge(['class' => 'custom-checkbox-wrapper']) }}>
        <input
            type="checkbox"
            name="{{ $name }}"
            id="{{ $checkboxId }}"
            value="{{ $value }}"
            {{ $checked ? 'checked' : '' }}
            class="form-check-input custom-checkbox"
            @if($describedBy !== '') aria-describedby="{{ $describedBy }}" @endif
            {{ $checkboxAttributes }}
        >
        <label class="form-check-label" for="{{ $checkboxId }}">
            <span class="fw-bold">
                {{ $label }}
                @if($required)<i class="text-danger" aria-hidden="true">*</i>@endif
            </span>
            @if($description)
                <small class="d-block text-muted" style="font-size: 0.75rem;" id="{{ $descriptionId }}">{{ $description }}</small>
            @endif
        </label>
    </div>
@endif
