@props([
    'name',
    'label' => null,
    'options' => [],
    'selected' => null,
    'resourceObjects' => null,
    'search' => false,
    'required' => false,
    'horizontal' => false,
])

@php
    $elementId = $attributes->get('id') ?? $name;
    $wrapperClasses = $attributes->get('class', 'mb-3');
    $hasError = $errors->has($name);
    $errorId = $elementId . '-error';
    $selectAttributes = $attributes->except(['class', 'id']);
    $currentValue = old($name, $selected);
    $existingDescribedBy = trim((string) $selectAttributes->get('aria-describedby', ''));
    $describedBy = trim(implode(' ', array_filter([
        $existingDescribedBy,
        $hasError ? $errorId : null,
    ])));
@endphp

@if($horizontal)
    <div class="form-group-horizontal {{ $wrapperClasses }}">
        @if($label)
            <label for="{{ $elementId }}" class="control-label">
                {{ $label }}
                @if($required)<i class="text-danger" aria-hidden="true">*</i>@endif
            </label>
        @endif
        <div class="field-wrapper">
            <select
                name="{{ $name }}"
                id="{{ $elementId }}"
                @if($required) required aria-required="true" @endif
                @if($hasError) aria-invalid="true" @endif
                @if($describedBy !== '') aria-describedby="{{ $describedBy }}" @endif
                {{ $selectAttributes->except(['aria-describedby'])->merge([
                    'class' => 'form-select custom-input ' .
                               ($search ? 'select-search ' : '') .
                               ($hasError ? ' is-invalid' : '')
                ]) }}
            >
                <option value="" {{ (string) $currentValue === '' ? 'selected' : '' }}>
                    Selecione uma opção...
                </option>
                @foreach($options as $value => $labelOption)
                    @php
                        $isDigital = false;
                        if ($resourceObjects && $value) {
                            $item = $resourceObjects->firstWhere('id', $value);
                            $isDigital = $item && isset($item->is_digital) && $item->is_digital;
                        }
                    @endphp
                    <option
                        value="{{ $value }}"
                        data-digital="{{ $isDigital ? '1' : '0' }}"
                        {{ (string) $currentValue === (string) $value ? 'selected' : '' }}
                    >{{ $labelOption }}</option>
                @endforeach
            </select>
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
        <select
            name="{{ $name }}"
            id="{{ $elementId }}"
            @if($required) required aria-required="true" @endif
            @if($hasError) aria-invalid="true" @endif
            @if($describedBy !== '') aria-describedby="{{ $describedBy }}" @endif
            {{ $selectAttributes->except(['aria-describedby'])->merge([
                'class' => 'form-select custom-input ' .
                           ($search ? 'select-search ' : '') .
                           ($hasError ? ' is-invalid' : '')
            ]) }}
        >
            <option value="" {{ (string) $currentValue === '' ? 'selected' : '' }}>
                Selecione uma opção...
            </option>
            @foreach($options as $value => $labelOption)
                @php
                    $isDigital = false;
                    if ($resourceObjects && $value) {
                        $item = $resourceObjects->firstWhere('id', $value);
                        $isDigital = $item && isset($item->is_digital) && $item->is_digital;
                    }
                @endphp
                <option
                    value="{{ $value }}"
                    data-digital="{{ $isDigital ? '1' : '0' }}"
                    {{ (string) $currentValue === (string) $value ? 'selected' : '' }}
                >{{ $labelOption }}</option>
            @endforeach
        </select>
        @error($name)
        <div class="invalid-feedback" id="{{ $errorId }}" role="alert">{{ $message }}</div>
        @enderror
    </div>
@endif
