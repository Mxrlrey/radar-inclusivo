@extends('layouts.master')

@section('title', 'Registrar Empréstimo')

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Empréstimos' => route('emprestimos.index'),
                'Cadastrar' => null
            ]" />
            <h1>Registrar Novo Empréstimo</h1>
            <p class="text-muted mb-0">
                Vincule um recurso de acessibilidade a um beneficiário e defina os prazos de devolução.
            </p>
        </div>

        <div class="page-header-actions">
            <x-buttons.link-button
                :href="route('emprestimos.index')"
                variant="secondary"
            >
                <x-slot:icon><i class="fa fa-times"></i></x-slot:icon>
                Cancelar
            </x-buttons.link-button>
        </div>
    </div>

    <x-forms.form-card
        action="{{ route('emprestimos.salvar') }}"
        method="POST"
        class="form-horizontal"
    >
        @csrf

        <x-forms.section
            title="Informações do Empréstimo"
            description="Identificação do recurso, beneficiário e controle de prazos."
        />

        <x-forms.select
            name="loanable_type"
            id="loanable_type"
            label="Tipo de Recurso"
            required
            :horizontal="true"
            :options="[
                'assistive_technology' => 'Tecnologia Assistiva',
                'accessible_educational_material' => 'Material Pedagógico'
            ]"
            :selected="old('loanable_type', $selectedItemType)"
        />

        <div id="loanable_id_container">
            <x-forms.select
                name="loanable_id"
                id="loanable_id"
                label="Item"
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
            :selected="old('student_id', $selectedStudentId)"
        />

        <x-forms.select
            name="professional_id"
            id="professional_id"
            label="Profissional"
            :horizontal="true"
            :options="$professionals"
            :selected="old('professional_id', $selectedProfessionalId)"
        />

        <x-forms.input
            name="user_id_display"
            label="Responsável Técnico"
            :horizontal="true"
            :value="$authUser->name"
            disabled
        />
        <input type="hidden" name="user_id" value="{{ $authUser->id }}">

        <x-forms.input
            name="loan_date"
            label="Data de Saída"
            type="datetime-local"
            required
            :horizontal="true"
            :value="old('loan_date', now()->format('Y-m-d\TH:i'))"
        />

        <x-forms.input
            name="due_date"
            label="Previsão de Devolução"
            type="date"
            required
            :horizontal="true"
            :value="old('due_date')"
        />

        <x-forms.textarea
            name="observation"
            label="Observações"
            :horizontal="true"
            rows="3"
            placeholder="Anote detalhes sobre o estado de conservação no momento da entrega..."
            :value="old('observation')"
        />

        <x-forms.form-footer>
            <x-buttons.link-button
                :href="route('emprestimos.index')"
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
        window.loanData = {
            items: {
                'assistive_technology': @json($assistive_technologies),
                'accessible_educational_material': @json($educational_materials)
            },
            targetId: "{{ old('loanable_id', $selectedItemId) }}"
        };
    </script>

    @push('scripts')
        @vite('resources/js/pages/loans.js')
    @endpush
@endsection
