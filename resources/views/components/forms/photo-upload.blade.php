@props([
    'name' => 'photo',
    'label' => 'Foto',
    'current' => null, // URL da foto existente (edit)
    'size' => '11rem'  // tamanho do círculo
])

@php
    $cleanId = str_replace(['[', ']'], '', $name);
    $inputId = 'photo-upload-input-' . $cleanId;
    $helpId = 'photo-upload-help-' . $cleanId;
    $statusId = 'photo-upload-status-' . $cleanId;
@endphp

<div class="photo-upload text-center mb-4">

    <label class="photo-upload__label text-primary" for="{{ $inputId }}">
        {{ $label }}
    </label>

    <div class="photo-upload__wrapper" style="width: {{ $size }}; height: {{ $size }}">

        <div class="photo-upload__preview"
             data-container
             role="button"
             tabindex="0"
             aria-controls="{{ $inputId }}"
             aria-describedby="{{ $helpId }} {{ $statusId }}"
             aria-label="Selecionar {{ strtolower($label) }}">

            <div class="photo-upload__empty {{ $current ? 'd-none' : '' }}" data-empty>
                <i class="fa fa-camera" aria-hidden="true"></i>
                <span>ADICIONAR<br>FOTO</span>
            </div>

            <img
                src="{{ $current }}"
                class="photo-upload__image {{ $current ? '' : 'd-none' }}"
                alt="{{ $current ? 'Pré-visualização da foto selecionada' : '' }}"
                data-image
            >
        </div>

        <button type="button"
                class="photo-upload__remove {{ $current ? '' : 'd-none' }}"
                aria-label="Remover foto selecionada"
                data-remove>
            <i class="fa fa-eraser" aria-hidden="true"></i>
        </button>

    </div>

    <input type="file"
           id="{{ $inputId }}"
           name="{{ $name }}"
           class="d-none"
           accept="image/*"
           aria-describedby="{{ $helpId }} {{ $statusId }}"
           data-input>

    <input type="hidden" name="remove_photo" value="0" data-remove-flag>

    <div class="photo-upload__help" id="{{ $helpId }}">
        <small>Clique para selecionar</small>
        <small>JPG, PNG. Máx 2MB.</small>
    </div>
    <div class="visually-hidden" id="{{ $statusId }}" aria-live="polite" data-status>
        {{ $current ? 'Foto atual carregada.' : 'Nenhuma foto selecionada.' }}
    </div>
    <div class="photo-upload__divider"></div>
</div>
