@props([
    'title',
    'subtitle' => null
])

<div class="page-header">
    <div class="page-header-title" role="heading" aria-level="1">
        <h1>{{ $title }}</h1>
        @if($subtitle)
            <p class="text-muted mb-0">{{ $subtitle }}</p>
        @endif
    </div>

    @if($slot->isNotEmpty())
        <div class="page-header-actions" aria-label="Ações da página">
            {{ $slot }}
        </div>
    @endif
</div>
