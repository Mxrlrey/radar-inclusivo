@props([
    'name' => 'images[]',
    'label' => 'Fotos de Evidência',
    'existingImages' => [],
    'ariaLabel' => 'Escolher arquivos de imagens para upload',
    'horizontal' => false,
])

@php
    $cleanId = str_replace(['[', ']'], '', $name);
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
               class="d-none"
               aria-describedby="help-{{ $cleanId }}">

        <div class="preview-container d-flex flex-wrap gap-2" role="list" aria-live="polite">
            @foreach($existingImages as $img)
                <div class="position-relative d-inline-block" role="listitem" style="width:70px;height:70px;">
                    <a href="{{ asset('storage/' . $img) }}" target="_blank" class="d-block">
                        <img src="{{ asset('storage/' . $img) }}"
                             alt="Miniatura da imagem de evidência"
                             class="border"
                             style="width:100%;height:100%;object-fit:cover;">
                    </a>
                </div>
            @endforeach
        </div>

        <x-buttons.link-button
            href="javascript:void(0)"
            class="mt-2 mb-1"
            onclick="document.getElementById('input-{{ $cleanId }}').click()"
            variant="primary"
            :label="$ariaLabel"
        >
            <x-slot:icon><i class="fa fa-file-image-o"></i></x-slot:icon>
            Escolher Arquivos
        </x-buttons.link-button>

        <div id="help-{{ $cleanId }}" class="d-block text-muted mt-1" style="font-size: 0.75rem;">
            Você pode selecionar múltiplos arquivos de imagem.
        </div>
    </div>
</div>

@vite('resources/js/pages/image-uploader.js')
