@props([
    'title',
    'subtitle' => null,
    'actionButton' => null
])

<div class="page-header">
    <div class="page-header-title">
        <h1>{{ $title }}</h1>
        @if($subtitle)
            <p class="text-muted mb-0">{{ $subtitle }}</p>
        @endif
    </div>

    @if($slot->isNotEmpty())
        <div class="page-header-actions">
            {{ $slot }}
        </div>
    @endif
</div>
