<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Relatório de Barreira - #{{ $barrier->id }}</title>
    <style>
        {!! file_get_contents(resource_path('css/components/pdf.css')) !!}
        body { font-family: sans-serif; }

        .inspection-images {
            width: 100%;
            border: 1px solid #ccc;
            border-top: none;
            padding: 10px;
            background: #fff;
        }

        .image-item {
            display: inline-block;
            width: 45%;
            margin: 1%;
            border: 1px solid #eee;
            vertical-align: top;
            background: #f9f9f9;
            page-break-inside: avoid;
        }

        .image-item img {
            width: 100%;
            height: auto;
            display: block;
        }

        .image-placeholder {
            padding: 20px;
            text-align: center;
            font-size: 9px;
            color: #999;
        }
    </style>
</head>
<body>
@php
    $priorityColor = match ($barrier->priority?->value) {
        'high' => '#e74c3c',
        'medium' => '#f39c12',
        default => '#7f8c8d',
    };

    if ($barrier->is_anonymous) {
        $reportType = 'Relato Anônimo';
    } elseif ($barrier->not_applicable) {
        $reportType = 'Relato Geral';
    } elseif ($barrier->affectedStudent || $barrier->affectedProfessional) {
        $reportType = 'Relato Identificado';
    } else {
        $reportType = 'Não informado';
    }

    $identifiedPerson = '---';

    if ($barrier->is_anonymous) {
        $identifiedPerson = 'Relato Anônimo';
    } else {
        $parts = [];

        if ($barrier->affectedStudent) {
            $parts[] = 'Estudante: ' . ($barrier->affectedStudent->person->name ?? '---');
        }

        if ($barrier->affectedProfessional) {
            $parts[] = 'Profissional: ' . ($barrier->affectedProfessional->person->name ?? '---');
        }

        if (empty($parts) && $barrier->affected_person_name) {
            $role = $barrier->affected_person_role ? ' (' . $barrier->affected_person_role . ')' : '';
            $parts[] = $barrier->affected_person_name . $role;
        }

        $identifiedPerson = !empty($parts) ? implode(' / ', $parts) : 'Relato Geral';
    }

    $lastInspection = $barrier->inspections->sortByDesc('inspection_date')->first();
@endphp

<div class="header">
    <h2>Ficha de Identificação de Barreira</h2>
    <p><strong>Barreira:</strong> {{ $barrier->name }}</p>
    <p><strong>Gerado em:</strong> {{ now()->format('d/m/Y H:i') }}</p>
    <p><strong>Status no Sistema:</strong> {{ $barrier->is_active ? 'Ativa' : 'Inativa' }}</p>
</div>

<x-pdf.section-title title="1. Localização e Contexto" />

<x-pdf.table>
    <x-pdf.row>
        <x-pdf.info-item label="Campus / Unidade" :value="$barrier->institution->name ?? '---'" colspan="2" />
        <x-pdf.info-item label="Local / Ref." :value="$barrier->location->name ?? '---'" colspan="2" />
    </x-pdf.row>
    <x-pdf.row>
        <x-pdf.info-item label="Coordenadas" :value="($barrier->latitude ?? '—') . ', ' . ($barrier->longitude ?? '—')" colspan="2" />
        <x-pdf.info-item label="Categoria" :value="$barrier->category->name ?? '---'" colspan="2" />
    </x-pdf.row>
</x-pdf.table>

@if($barrier->location_specific_details)
    <x-pdf.text-area label="Complemento do Local" :value="$barrier->location_specific_details" />
@endif

<x-pdf.section-title title="2. Detalhes da Ocorrência" />

<x-pdf.table>
    <x-pdf.row>
        <x-pdf.info-item label="Nome da Barreira" :value="$barrier->name" colspan="2" />
        <x-pdf.info-item label="Prioridade" :value="'<b style=\'color:' . $priorityColor . '\'>'.($barrier->priority?->label() ?? '---').'</b>'" />
        <x-pdf.info-item label="Data de Identificação" :value="$barrier->identified_at?->format('d/m/Y') ?? '---'" />
    </x-pdf.row>
    <x-pdf.row>
        <x-pdf.info-item label="Tipo de Relato" :value="$reportType" colspan="2" />
        <x-pdf.info-item label="Relator / Identificação" :value="$identifiedPerson" colspan="2" />
    </x-pdf.row>
    <x-pdf.row>
        <x-pdf.info-item label="Relator" :value="$barrier->reporter_display_name" colspan="4" />
    </x-pdf.row>
</x-pdf.table>

<x-pdf.text-area label="Descrição do Problema" :value="$barrier->description ?? 'Sem descrição.'" />

<x-pdf.section-title title="3. Público-Alvo Afetado" />

<x-pdf.table>
    <x-pdf.row>
        <x-pdf.info-item label="Deficiências Relacionadas" :value="$barrier->deficiencies->pluck('name')->join(', ') ?: 'Geral / Não especificado'" colspan="4" />
    </x-pdf.row>
</x-pdf.table>

<x-pdf.section-title title="4. Última Vistoria" />

@if($lastInspection)
    <x-pdf.table>
        <x-pdf.row>
            <x-pdf.info-item label="Data da Vistoria" :value="$lastInspection->inspection_date?->format('d/m/Y') ?? '---'" />
            <x-pdf.info-item label="Estado da Barreira" :value="$lastInspection->status?->label() ?? '---'" />
        </x-pdf.row>
    </x-pdf.table>

    <x-pdf.text-area label="Parecer Técnico" :value="$lastInspection->description ?: 'Sem descrição registrada.'" />

    <div class="inspection-images">
        <strong style="font-size:10px;">Imagens da Vistoria</strong>

        @if($lastInspection->images->count() > 0)
            @foreach($lastInspection->images as $image)
                @php
                    $path = public_path('storage/' . $image->path);
                @endphp

                <div class="image-item">
                    @if(file_exists($path))
                        <img src="{{ $path }}" alt="Imagem da vistoria">
                    @else
                        <div class="image-placeholder">Imagem não encontrada</div>
                    @endif
                </div>
            @endforeach
        @else
            <div class="image-placeholder">Nenhuma imagem registrada.</div>
        @endif
    </div>
@else
    <x-pdf.text-area label="Última Vistoria" :value="'Nenhuma vistoria técnica registrada até o momento.'" />
@endif

<x-pdf.section-title title="5. Registro do Sistema" />

<x-pdf.table>
    <x-pdf.row>
        <x-pdf.info-item label="ID no Sistema" :value="'#' . $barrier->id" />
        <x-pdf.info-item label="Status no Sistema" :value="$barrier->is_active ? 'Ativo' : 'Inativo'" />
        <x-pdf.info-item label="Criado em" :value="$barrier->created_at?->format('d/m/Y \\à\\s H:i') ?? '---'" />
        <x-pdf.info-item label="Última atualização" :value="$barrier->updated_at?->format('d/m/Y \\à\\s H:i') ?? '---'" />
    </x-pdf.row>
</x-pdf.table>

<div style="margin-top: 60px;">
    <x-pdf.table-signatures>
        @if(!$barrier->is_anonymous)
            @if($barrier->affectedStudent)
                <x-pdf.table-signature-label label="Assinatura do Estudante Contribuidor" />
            @endif

            @if($barrier->affectedProfessional)
                <x-pdf.table-signature-label label="Assinatura do Profissional Contribuidor" />
            @endif

            @if(!$barrier->affectedStudent && !$barrier->affectedProfessional && $barrier->affected_person_name)
                <x-pdf.table-signature-label label="Assinatura do Contribuidor" />
            @endif
        @endif

        <x-pdf.table-signature-label label="Responsável pelo Setor / Carimbo" />
    </x-pdf.table-signatures>
</div>

<x-pdf.pages />
</body>
</html>
