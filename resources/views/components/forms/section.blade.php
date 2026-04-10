@props(['title'])

<div {{ $attributes->merge(['class' => 'col-12 mb-4']) }} role="region">
    <div class="form-section-divider">
        <h3 class="ms-4 fw-bold mb-0 section-title">
            {{ $title }}
        </h3>
    </div>
</div>
