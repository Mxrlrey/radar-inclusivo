<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Relatório Dinâmico</title>
    <style>
        {!! file_get_contents(resource_path('css/components/pdf.css')) !!}
        body { font-family: sans-serif; }
    </style>
</head>
<body>
<div class="header">
    <h2>Relatório Dinâmico</h2>
    <p><strong>Gerado em:</strong> {{ now()->format('d/m/Y H:i') }}</p>
    <p><strong>Total de registros:</strong> {{ count($data) }}</p>
</div>

<x-pdf.section-title title="1. Dados do Relatório" />

<table>
    <thead>
    <tr>
        @foreach($headers as $header)
            <td><span class="label">{{ $header }}</span></td>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @forelse($data as $row)
        <tr>
            @foreach((array) $row as $value)
                <td><span class="value">{{ $value !== null && $value !== '' ? $value : '---' }}</span></td>
            @endforeach
        </tr>
    @empty
        <tr>
            <td colspan="{{ max(count($headers), 1) }}">
                <span class="value">Nenhum registro encontrado.</span>
            </td>
        </tr>
    @endforelse
    </tbody>
</table>

<x-pdf.pages />
</body>
</html>
