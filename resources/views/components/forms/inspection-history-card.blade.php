@props(['inspection'])

@php
    $isBarrier = str_contains($inspection->inspectable_type, 'barrier');
@endphp

<div {{ $attributes->merge(['class' => 'inspection-item']) }}>
    <div class="inspection-header">
        <div class="inspection-date-badge">
            {{ $inspection->inspection_date->format('d/m/Y') }}
        </div>
        <div class="inspection-type-label">
            {{ $inspection->type->label() }}
        </div>
    </div>

    <div class="inspection-content">
        <div class="inspection-info">
            <div class="info-group">
                <label>
                    {{ $isBarrier ? 'Status da Barreira' : 'Estado de Conservação' }}
                </label>

                <div class="info-value {{ $isBarrier ? $inspection->status?->color() : '' }}">
                    {{ $isBarrier
                        ? ($inspection->status?->label() ?? 'Identificada')
                        : ($inspection->state?->label() ?? '---')
                    }}
                </div>
            </div>

            @if($inspection->description)
                <div class="info-group mt-3">
                    <label>Parecer Técnico / Descrição</label>
                    <div class="history-description-text">
                        {!! $inspection->description !!}
                    </div>
                </div>
            @endif
        </div>

        <div class="inspection-evidences">
            <label>Evidências Visuais</label>
            @if($inspection->images && $inspection->images->count() > 0)
                @if($isBarrier)
                    <div class="evidence-grid">
                        @foreach($inspection->images as $img)
                            <a href="{{ asset('storage/' . $img->path) }}" target="_blank" class="evidence-thumb">
                                <img src="{{ asset('storage/' . $img->path) }}" alt="Evidência">
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="evidence-list">
                        @foreach($inspection->images as $img)
                            <a href="{{ asset('storage/' . $img->path) }}" target="_blank" class="evidence-thumb-small">
                                <img src="{{ asset('storage/' . $img->path) }}" alt="Evidência">
                            </a>
                        @endforeach
                    </div>
                @endif
            @else
                <div class="no-evidence">
                    <span>Nenhuma foto registrada</span>
                </div>
            @endif
        </div>
    </div>
</div>
