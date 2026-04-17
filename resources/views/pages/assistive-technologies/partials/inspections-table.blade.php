<table class="table-mini">
    <tbody>
    @forelse($inspections as $inspection)
        <tr class="inspection-tr"
            onclick="window.location='{{ route('assistive-technologies.inspection.show', [$assistiveTechnology, $inspection]) }}'">

            <td class="col-info">
                <i class="ion-clipboard"></i>
                <div class="inspection-details">
                    <span class="inspection-type">
                        {{ $inspection->type?->label() ?? 'Vistoria' }}
                    </span>
                </div>
            </td>

            <td class="col-meta">
                <div class="meta-content">
                    <span class="inspection-date">
                        {{ $inspection->inspection_date->format('d/m/Y') }}
                    </span>

                    @if($inspection->images->isNotEmpty())
                        <i class="ion-image text-success"></i>
                    @else
                        <i class="ion-close text-danger"></i>
                    @endif
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="2" class="text-center p-4 text-muted">
                Nenhuma vistoria registrada.
            </td>
        </tr>
    @endforelse
    </tbody>
</table>

@if($inspections->hasPages())
    <div class="inspection-simple-pagination">

        @if ($inspections->onFirstPage())
            <span class="page-item disabled">
                <span class="page-link">
                    <i class="fa fa-chevron-left"></i>
                </span>
            </span>
        @else
            <a href="{{ $inspections->previousPageUrl() }}"
               class="page-item ajax-pagination"
               rel="prev">
                <span class="page-link">
                    <i class="fa fa-chevron-left"></i>
                </span>
            </a>
        @endif

        @if ($inspections->hasMorePages())
            <a href="{{ $inspections->nextPageUrl() }}"
               class="page-item ajax-pagination"
               rel="next">
                <span class="page-link">
                    <i class="fa fa-chevron-right"></i>
                </span>
            </a>
        @else
            <span class="page-item disabled">
                <span class="page-link">
                    <i class="fa fa-chevron-right"></i>
                </span>
            </span>
        @endif
    </div>
@endif
