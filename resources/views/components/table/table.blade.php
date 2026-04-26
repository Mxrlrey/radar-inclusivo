@props([
    'headers' => [],
    'tableClass' => 'table table-hover mb-0',
    'records' => null,
    'label' => 'Listagem',
    'caption' => null
])

<div class="table-container">
    <div class="table-responsive">
        <table {{ $attributes->merge(['class' => $tableClass . ' w-100']) }} aria-label="{{ $label }}">
            @if($caption)
                <caption class="visually-hidden">{{ $caption }}</caption>
            @endif
            <thead>
            <tr>
                @foreach($headers as $header)
                    <x-table.th :class="$header['class'] ?? null" scope="col">
                        {{ $header['label'] ?? $header }}
                    </x-table.th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            {{ $slot }}
            </tbody>
        </table>
    </div>

    @if($records instanceof \Illuminate\Pagination\LengthAwarePaginator && $records->hasPages())
        <div class="px-4 py-3 border-top d-flex justify-content-between align-items-center custom-pagination-container">
            <div class="text-muted small fw-medium">
                Mostrando <span class="text-primary">{{ $records->firstItem() }}</span>
                - <span class="text-primary">{{ $records->lastItem() }}</span>
                de <span class="text-primary">{{ $records->total() }}</span>
            </div>

            <nav aria-label="Navegação da página">
                {{ $records->links('pagination::bootstrap-4') }}
            </nav>
        </div>
    @endif
</div>
