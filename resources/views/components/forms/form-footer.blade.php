@props([
    'leftContent' => null,
])

<div class="border-top p-4 d-flex justify-content-between align-items-center mt-4">
    <div class="text-muted small">
        {{ $leftContent ?? '' }}
    </div>

    <div class="d-flex gap-3">
        {{ $slot }}
    </div>
</div>
