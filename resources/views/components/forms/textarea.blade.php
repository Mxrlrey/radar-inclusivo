@props([
    'name',
    'label' => null,
    'value' => '',
    'rows' => 3,
    'required' => false,
    'rich' => true,
    'horizontal' => false,
])

@php
    $elementId = $attributes->get('id') ?? $name;
    $hasError = $errors->has($name);
    $errorId = $elementId . '-error';
    $textareaAttributes = $attributes->except(['class', 'id']);
    $existingDescribedBy = trim((string) $textareaAttributes->get('aria-describedby', ''));
    $describedBy = trim(implode(' ', array_filter([
        $existingDescribedBy,
        $hasError ? $errorId : null,
    ])));
@endphp

@if($horizontal)
    <div class="form-group-horizontal mb-3">
        @if($label)
            <label for="{{ $elementId }}" class="control-label">
                {{ $label }}
                @if($required)<i class="text-danger" aria-hidden="true">*</i>@endif
            </label>
        @endif
        <div class="field-wrapper">
            <textarea
                name="{{ $name }}"
                id="{{ $elementId }}"
                rows="{{ $rows }}"
                @if($required) required aria-required="true" @endif
                @if($hasError) aria-invalid="true" @endif
                @if($describedBy !== '') aria-describedby="{{ $describedBy }}" @endif
                {{ $textareaAttributes->except(['aria-describedby'])->merge([
                    'class' => 'form-control custom-input ' . ($hasError ? 'is-invalid ' : '') . ($rich ? 'rich-editor' : '')
                ]) }}
            >{{ old($name, $value) }}</textarea>
            @error($name)
            <div class="invalid-feedback" id="{{ $errorId }}" role="alert">{{ $message }}</div>
            @enderror
        </div>
    </div>
@else
    <div {{ $attributes->except('id')->merge(['class' => 'mb-3']) }}>
        @if($label)
            <label for="{{ $elementId }}" class="form-label fw-bold text-primary">
                {{ $label }}
                @if($required)<i class="text-danger" aria-hidden="true">*</i>@endif
            </label>
        @endif
        <textarea
            name="{{ $name }}"
            id="{{ $elementId }}"
            rows="{{ $rows }}"
            @if($required) required aria-required="true" @endif
            @if($hasError) aria-invalid="true" @endif
            @if($describedBy !== '') aria-describedby="{{ $describedBy }}" @endif
            {{ $textareaAttributes->except(['aria-describedby'])->merge([
                'class' => 'form-control custom-input ' . ($hasError ? 'is-invalid ' : '') . ($rich ? 'rich-editor' : '')
            ]) }}
        >{{ old($name, $value) }}</textarea>
        @error($name)
        <div class="invalid-feedback" id="{{ $errorId }}" role="alert">{{ $message }}</div>
        @enderror
    </div>
@endif
