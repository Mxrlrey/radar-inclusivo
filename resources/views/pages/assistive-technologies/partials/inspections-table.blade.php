<table class="table-mini">
    <tbody>
    @forelse($inspections as $inspection)
        <tr class="inspection-tr">

            <td class="col-info">
                @can('assistive-technology.inspection.show')
                    <a href="{{ route('tecnologias-assistivas.inspecao.visualizar', [$assistiveTechnology, $inspection]) }}"
                       class="inspection-link"
                       aria-label="Abrir vistoria {{ $inspection->type?->label() ?? 'Vistoria' }} de {{ $inspection->inspection_date->format('d/m/Y') }}">
                        <i class="ion-clipboard" aria-hidden="true"></i>
                        <div class="inspection-details">
                            <span class="inspection-type">
                                {{ $inspection->type?->label() ?? 'Vistoria' }}
                            </span>
                        </div>
                    </a>
                @else
                    <span class="inspection-link">
                        <i class="ion-clipboard" aria-hidden="true"></i>
                        <span class="inspection-type">{{ $inspection->type?->label() ?? 'Vistoria' }}</span>
                    </span>
                @endcan
            </td>

            <td class="col-meta">
                @can('assistive-technology.inspection.show')
                    <a href="{{ route('tecnologias-assistivas.inspecao.visualizar', [$assistiveTechnology, $inspection]) }}"
                       class="inspection-link inspection-link--meta"
                       aria-hidden="true"
                       tabindex="-1">
                        <div class="meta-content">
                            <span class="inspection-date">
                                {{ $inspection->inspection_date->format('d/m/Y') }}
                            </span>

                            @if($inspection->images->isNotEmpty())
                                <i class="ion-image text-success" aria-hidden="true"></i>
                                <span class="visually-hidden">Contém imagens anexadas.</span>
                            @else
                                <i class="ion-close text-danger" aria-hidden="true"></i>
                                <span class="visually-hidden">Sem imagens anexadas.</span>
                            @endif
                        </div>
                    </a>
                @else
                    <span class="inspection-link inspection-link--meta">
                        <span class="inspection-date">{{ $inspection->inspection_date->format('d/m/Y') }}</span>
                    </span>
                @endcan
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
                    <i class="fa fa-chevron-left" aria-hidden="true"></i>
                </span>
            </span>
        @else
            <a href="{{ $inspections->previousPageUrl() }}"
               class="page-item ajax-pagination"
               rel="prev">
                <span class="page-link">
                    <i class="fa fa-chevron-left" aria-hidden="true"></i>
                </span>
            </a>
        @endif

        @if ($inspections->hasMorePages())
            <a href="{{ $inspections->nextPageUrl() }}"
               class="page-item ajax-pagination"
               rel="next">
                <span class="page-link">
                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                </span>
            </a>
        @else
            <span class="page-item disabled">
                <span class="page-link">
                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                </span>
            </span>
        @endif
    </div>
@endif
