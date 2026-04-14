@extends('layouts.master')

@section('title', "Editar - Solicitação #$waitlist->id")

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Fila de Espera' => route('waitlists.index'),
                $waitlist->id => route('waitlists.show', $waitlist),
                'Editar' => null
            ]" />
            <h1>Editar Solicitação de Fila</h1>
            <p class="text-muted mb-0">
                Atualize as notas e prioridades da solicitação. Campos de identificação são bloqueados para integridade do histórico.
            </p>
        </div>

        <div class="page-header-actions">
            <x-buttons.link-button :href="route('waitlists.show', $waitlist)" variant="secondary">
                <x-slot:icon><i class="fa fa-times"></i></x-slot:icon>
                Cancelar
            </x-buttons.link-button>
        </div>
    </div>

    <x-forms.form-card
        action="{{ route('waitlists.update', $waitlist) }}"
        method="POST"
        class="form-horizontal"
    >
        @csrf
        @method('PUT')

        <input type="hidden" name="waitlistable_id" value="{{ $waitlist->waitlistable_id }}">
        <input type="hidden" name="waitlistable_type" value="{{ $waitlist->waitlistable_type }}">

        <x-forms.section
            title="Informações do Pedido"
            description="Preencha os dados do recurso e beneficiário para colocar na fila de espera."
        />

        <x-forms.input
            name="item_type_display"
            label="Tipo do Recurso"
            :horizontal="true"
            :value="$waitlist->waitlistable_type === 'assistive_technology' ? 'Tecnologia Assistiva' : 'Material Pedagógico'"
            disabled
        />

        <x-forms.input
            name="item_display"
            label="Item"
            :horizontal="true"
            :value="$waitlist->waitlistable->name ?? ($waitlist->waitlistable->title ?? 'Item não identificado')"
            disabled
        />

        @if($waitlist->student_id)
            <x-forms.input
                name="student_display"
                label="Estudante"
                :horizontal="true"
                :value="$waitlist->student->person->name"
                disabled
            />
        @else
            <x-forms.input
                name="professional_display"
                label="Profissional"
                :horizontal="true"
                :value="$waitlist->professional->person->name"
                disabled
            />
        @endif

        <x-forms.input
            name="user_id_display"
            label="Responsável pelo Registro"
            :horizontal="true"
            :value="$waitlist->user->name ?? $authUser->name"
            disabled
        />

        <x-forms.input
            name="status_display"
            label="Status Atual"
            :horizontal="true"
            :value="$statusLabel"
            disabled
        />

        <x-forms.textarea
            name="observation"
            label="Notas e Prioridades"
            :horizontal="true"
            rows="4"
            :value="old('observation', $waitlist->observation)"
            placeholder="Adicione informações relevantes sobre esta solicitação..."
        />

        <x-forms.form-footer>
            <x-buttons.link-button
                :href="route('waitlists.show', $waitlist)"
                variant="secondary"
            >
                <x-slot:icon><i class="fa fa-times"></i></x-slot:icon>
                Cancelar
            </x-buttons.link-button>

            <x-buttons.submit-button variant="new">
                <x-slot:icon><i class="fa fa-save"></i></x-slot:icon>
                Salvar
            </x-buttons.submit-button>
        </x-forms.form-footer>
    </x-forms.form-card>
@endsection
