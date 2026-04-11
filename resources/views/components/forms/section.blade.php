@props(['title', 'description' => null])

<div {{ $attributes->merge(['class' => 'form-section']) }}>
    <h4 class="form-section-title">{{ $title }}</h4>
    @if($description)
        <p class="form-section-description">{{ $description }}</p>
    @endif
</div>
