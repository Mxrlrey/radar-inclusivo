<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Relatório - Empréstimo {{ $loan->id }}</title>
    <style>
        {!! file_get_contents(resource_path('css/components/pdf.css')) !!}
        body { font-family: sans-serif; }
    </style>
</head>
<body>
@php
    $currentStatus = $loan->status instanceof \App\Enums\LoanStatus
        ? $loan->status
        : \App\Enums\LoanStatus::tryFrom($loan->status);

    $isOverdue = $currentStatus === \App\Enums\LoanStatus::ACTIVE && $loan->due_date?->isPast();
    $statusLabel = $isOverdue ? 'Em Atraso' : ($currentStatus?->label() ?? '---');
    $loanable = $loan->loanable;
    $loanableType = $loanable instanceof \App\Models\AssistiveTechnology
        ? 'Tecnologia Assistiva'
        : ($loanable instanceof \App\Models\AccessibleEducationalMaterial ? 'Material Pedagógico Acessível' : '---');
@endphp

<div class="header">
    <h2>Ficha de Empréstimo de Recurso</h2>
    <p><strong>ID:</strong> #{{ $loan->id }}</p>
    <p><strong>Gerado em:</strong> {{ now()->format('d/m/Y H:i') }}</p>
    <p><strong>Status:</strong> {{ $statusLabel }}</p>
</div>

<x-pdf.section-title title="1. Identificação do Empréstimo" />

<x-pdf.table>
    <x-pdf.row>
        <x-pdf.info-item label="Data de Saída" :value="$loan->loan_date?->format('d/m/Y H:i') ?? '---'" />
        <x-pdf.info-item label="Previsão de Devolução" :value="$loan->due_date?->format('d/m/Y') ?? '---'" />
    </x-pdf.row>
    <x-pdf.row>
        <x-pdf.info-item label="Data de Retorno" :value="$loan->return_date?->format('d/m/Y H:i') ?? 'Ainda não devolvido'" />
        <x-pdf.info-item label="Situação" :value="$statusLabel" />
    </x-pdf.row>
</x-pdf.table>

<x-pdf.section-title title="2. Recurso Emprestado" />

<x-pdf.table>
    <x-pdf.row>
        <x-pdf.info-item label="Item" :value="$loanable?->name ?? ($loanable?->title ?? '---')" colspan="2" />
        <x-pdf.info-item label="Tipo do Recurso" :value="$loanableType" colspan="2" />
    </x-pdf.row>
    <x-pdf.row>
        <x-pdf.info-item label="Patrimônio / Tombamento" :value="$loanable?->asset_code ?? 'Não informado'" colspan="2" />
        <x-pdf.info-item label="Quantidade" :value="$loan->quantity ?? 1" colspan="2" />
    </x-pdf.row>
</x-pdf.table>

<x-pdf.section-title title="3. Envolvidos" />

<x-pdf.table>
    @if($loan->student)
        <x-pdf.row>
            <x-pdf.info-item label="Estudante (Beneficiário)" :value="$loan->student->person->name ?? '---'" colspan="2" />
            <x-pdf.info-item label="Matrícula" :value="$loan->student->registration ?? '---'" colspan="2" />
        </x-pdf.row>
    @elseif($loan->professional)
        <x-pdf.row>
            <x-pdf.info-item label="Profissional (Beneficiário)" :value="$loan->professional->person->name ?? '---'" colspan="2" />
            <x-pdf.info-item label="Registro" :value="$loan->professional->registration ?? '---'" colspan="2" />
        </x-pdf.row>
    @endif

    <x-pdf.row>
        <x-pdf.info-item label="Usuário Responsável" :value="$loan->user->name ?? '---'" colspan="4" />
    </x-pdf.row>
</x-pdf.table>

<x-pdf.section-title title="4. Observações" />

<x-pdf.text-area label="Observações do Empréstimo" :value="$loan->observation ?: 'Nenhuma observação registrada.'" />

<x-pdf.section-title title="5. Registro do Sistema" />

<x-pdf.table>
    <x-pdf.row>
        <x-pdf.info-item label="ID no Sistema" :value="'#' . $loan->id" />
        <x-pdf.info-item label="Criado em" :value="$loan->created_at?->format('d/m/Y \\à\\s H:i') ?? '---'" />
        <x-pdf.info-item label="Atualizado em" :value="$loan->updated_at?->format('d/m/Y \\à\\s H:i') ?? '---'" />
        <x-pdf.info-item label="Atrasado" :value="$isOverdue ? 'Sim' : 'Não'" />
    </x-pdf.row>
</x-pdf.table>

<x-pdf.pages />
</body>
</html>
