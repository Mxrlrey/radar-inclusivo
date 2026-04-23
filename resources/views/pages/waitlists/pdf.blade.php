<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Solicitação de Fila - #{{ $waitlist->id }}</title>
    <style>
        {!! file_get_contents(resource_path('css/components/pdf.css')) !!}
        body { font-family: sans-serif; }
    </style>
</head>
<body>
@php
    $beneficiario = 'Não informado';
    $documento = '---';

    if ($waitlist->student) {
        $beneficiario = $waitlist->student->person->name ?? '---';
        $documento = $waitlist->student->registration ?? '---';
    } elseif ($waitlist->professional) {
        $beneficiario = $waitlist->professional->person->name ?? '---';
        $documento = $waitlist->professional->registration ?? '---';
    }

    $item = $waitlist->waitlistable;
    $itemType = $item instanceof \App\Models\AssistiveTechnology
        ? 'Tecnologia Assistiva'
        : ($item instanceof \App\Models\AccessibleEducationalMaterial ? 'Material Pedagógico Acessível' : '---');

    $statusEnum = \App\Enums\WaitlistStatus::tryFrom($waitlist->status);
    $statusStyle = 'color: ' . ($statusEnum?->color() === 'warning' ? '#856404' : '#155724');
    $statusLabel = "<span style='{$statusStyle}; font-weight: bold;'>" . ($statusEnum?->label() ?? $waitlist->status ?? '---') . '</span>';
@endphp

<div class="header">
    <h2>Comprovante de Fila de Espera</h2>
    <p><strong>Protocolo:</strong> #{{ $waitlist->id }}</p>
    <p><strong>Emissão:</strong> {{ now()->format('d/m/Y H:i') }}</p>
    <p><strong>Status Atual:</strong> {!! $statusLabel !!}</p>
</div>

<x-pdf.section-title title="1. Identificação do Solicitante" />

<x-pdf.table>
    <x-pdf.row>
        <x-pdf.info-item label="Nome Completo" :value="$beneficiario" colspan="2" />
        <x-pdf.info-item label="Vínculo / Registro" :value="$documento" colspan="2" />
    </x-pdf.row>
</x-pdf.table>

<x-pdf.section-title title="2. Recurso Solicitado" />

<x-pdf.table>
    <x-pdf.row>
        <x-pdf.info-item label="Tipo do Recurso" :value="$itemType" colspan="2" />
        <x-pdf.info-item label="Item / Recurso" :value="$item?->name ?? ($item?->title ?? 'Item Removido')" colspan="2" />
    </x-pdf.row>
    <x-pdf.row>
        <x-pdf.info-item label="Data da Solicitação" :value="$waitlist->requested_at?->format('d/m/Y \\à\\s H:i') ?? '---'" colspan="2" />
        <x-pdf.info-item label="Status Atual" :value="$statusLabel" colspan="2" />
    </x-pdf.row>
</x-pdf.table>

<x-pdf.section-title title="3. Informações Complementares" />

<x-pdf.text-area label="Observações" :value="$waitlist->observation ?: 'Sem observações registradas.'" />

<x-pdf.table style="margin-top: 15px;">
    <x-pdf.row>
        <x-pdf.info-item label="Responsável pelo Registro" :value="$waitlist->user->name ?? 'Sistema'" colspan="2" />
        <x-pdf.info-item label="Criado em" :value="$waitlist->created_at?->format('d/m/Y \\à\\s H:i') ?? '---'" colspan="1" />
        <x-pdf.info-item label="Atualizado em" :value="$waitlist->updated_at?->format('d/m/Y \\à\\s H:i') ?? '---'" colspan="1" />
    </x-pdf.row>
</x-pdf.table>

<div style="margin-top: 60px;">
    <x-pdf.table-signatures>
        <x-pdf.table-signature-label label="Assinatura do Solicitante" />
        <x-pdf.table-signature-label label="Responsável pelo Setor / Carimbo" />
    </x-pdf.table-signatures>
</div>

<x-pdf.pages />
</body>
</html>
