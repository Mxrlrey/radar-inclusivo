@extends('layouts.master')

@section('title', 'Registrar Fila de Espera')

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Fila de Espera' => route('waitlists.index'),
                'Cadastrar' => null
            ]" />
            <h1>Nova Solicitação de Fila</h1>
            <p class="text-muted mb-0">
                Registre um beneficiário para um recurso atualmente indisponível.
            </p>
        </div>

        <div class="page-header-actions">
            <x-buttons.link-button
                :href="route('waitlists.index')"
                variant="secondary"
            >
                <x-slot:icon><i class="fa fa-times"></i></x-slot:icon>
                Cancelar
            </x-buttons.link-button>
        </div>
    </div>

    <x-forms.form-card
        action="{{ route('waitlists.store') }}"
        method="POST"
        class="form-horizontal"
    >
        @csrf
        <x-forms.section
            title="Informações do Pedido"
            description="Preencha os dados do recurso e beneficiário para aguardar na fila de espera."
        />

        <x-forms.select
            name="waitlistable_type"
            id="waitlistable_type"
            label="Tipo de Recurso"
            required
            :horizontal="true"
            :options="[
                'assistive_technology' => 'Tecnologia Assistiva',
                'accessible_educational_material' => 'Material Pedagógico'
            ]"
            :selected="old('waitlistable_type')"
        />

        <div id="waitlistable_id_container">
            <x-forms.select
                name="waitlistable_id"
                id="waitlistable_id"
                label="Item Específico"
                required
                :horizontal="true"
                :options="['' => 'Selecione o tipo primeiro']"
            />
        </div>

        <x-forms.select
            name="student_id"
            id="student_id"
            label="Estudante"
            :horizontal="true"
            :options="$students"
            :selected="old('student_id')"
        />

        <x-forms.select
            name="professional_id"
            id="professional_id"
            label="Profissional"
            :horizontal="true"
            :options="$professionals"
            :selected="old('professional_id')"
        />

        <x-forms.input
            name="user_id_display"
            label="Responsável pelo Registro"
            :horizontal="true"
            :value="$authUser->name"
            disabled
        />
        <input type="hidden" name="user_id" value="{{ $authUser->id }}">

        <x-forms.textarea
            name="observation"
            label="Detalhes"
            :horizontal="true"
            rows="3"
            placeholder="Anote detalhes sobre a solicitação..."
            :value="old('observation')"
        />

        <x-forms.form-footer>
            <x-buttons.link-button
                :href="route('waitlists.index')"
                variant="secondary"
            >
                <x-slot:icon><i class="fa fa-times"></i></x-slot:icon>
                Cancelar
            </x-buttons.link-button>

            <x-buttons.submit-button variant="new">
                <x-slot:icon><i class="fa fa-save"></i></x-slot:icon>
                Cadastrar
            </x-buttons.submit-button>
        </x-forms.form-footer>
    </x-forms.form-card>

    <script>
        window.waitlistData = {
            items: {
                'assistive_technology': @json($assistive_technologies ?? []),
                'accessible_educational_material': @json($educational_materials ?? [])
            },
            oldId: "{{ old('waitlistable_id') }}"
        };
    </script>

    @push('scripts')
        @vite('resources/js/pages/waitlists.js')
    @endpush
@endsection
