@props(['title', 'description' => null])

@php
    $sectionId = $attributes->get('id');
    $headingId = $sectionId ? "{$sectionId}-heading" : null;
@endphp

<section
    {{ $attributes->except('id')->merge(['class' => 'form-section']) }}
    @if($sectionId) id="{{ $sectionId }}" aria-labelledby="{{ $headingId }}" @endif
>
    <h4 class="form-section-title" @if($headingId) id="{{ $headingId }}" @endif>{{ $title }}</h4>
    @if($description)
        <p class="form-section-description">{{ $description }}</p>
    @endif
</section>
