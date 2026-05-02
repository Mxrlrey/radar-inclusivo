@props([
    'name' => 'images[]',
    'label' => 'Fotos de Evidência',
    'existingImages' => [],
    'ariaLabel' => 'Escolher arquivos de imagens para upload',
    'horizontal' => false,
])

@php
    $cleanId = str_replace(['[', ']'], '', $name);
    $statusId = 'status-' . $cleanId;
@endphp

<div @class([
    'image-uploader mb-3' => !$horizontal,
    'image-uploader form-group-horizontal mb-3' => $horizontal,
])>
    <label
        for="input-{{ $cleanId }}"
        @class([
            'form-label fw-bold text-primary' => !$horizontal,
            'control-label' => $horizontal,
        ])
    >
        {{ $label }}
    </label>

    <div @class(['field-wrapper' => $horizontal])>

        <input type="file"
               id="input-{{ $cleanId }}"
               name="{{ $name }}"
               multiple
               accept="image/*"
               style="position:absolute; width:1px; height:1px; padding:0; margin:-1px; overflow:hidden; clip:rect(0,0,0,0); white-space:nowrap; border:0;"
               aria-describedby="help-{{ $cleanId }}">

        <div class="preview-container d-flex flex-wrap gap-2" role="list" aria-live="polite">
            @foreach($existingImages as $img)
                <div class="position-relative d-inline-block" role="listitem" style="width:70px;height:70px;">
                    <a href="{{ asset('storage/' . $img) }}" target="_blank" rel="noopener noreferrer" class="d-block">
                        <img src="{{ asset('storage/' . $img) }}"
                             alt="Imagem de evidência já cadastrada"
                             class="border"
                             style="width:100%;height:100%;object-fit:cover;">
                    </a>
                </div>
            @endforeach
        </div>

        <button
            type="button"
            class="btn-action upload waves-effect waves-light mt-2 mb-1"
            aria-label="{{ $ariaLabel }}"
            aria-controls="input-{{ $cleanId }}"
            aria-describedby="help-{{ $cleanId }} {{ $statusId }}"
            onclick="const input = document.getElementById('input-{{ $cleanId }}'); if (!input) return; if (typeof input.showPicker === 'function') { input.showPicker(); return; } input.click();"
        >
            <span class="btn-label"><i class="fa fa-file-image-o" aria-hidden="true"></i></span>
            Escolher Arquivos
        </button>

        <div id="help-{{ $cleanId }}" class="d-block text-muted mt-1" style="font-size: 0.75rem;">
            Você pode selecionar múltiplos arquivos de imagem.
        </div>
        <div id="{{ $statusId }}" class="visually-hidden" aria-live="polite" data-upload-status>
            {{ count($existingImages) > 0 ? count($existingImages) . ' imagem(ns) já cadastrada(s).' : 'Nenhuma imagem selecionada.' }}
        </div>
    </div>
</div>

@once
    @vite('resources/js/pages/image-uploader.js')
@endonce
