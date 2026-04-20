@extends('layouts.master')

@section('title', "Editar - {$student->person->name}")

@section('content')

    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
            'Home' => route('dashboard'),
            'Alunos' => route('estudantes.index'),
            $student->person->name => route('estudantes.visualizar', $student),
            'Editar' => null
        ]" />

            <h1>Editar Aluno</h1>
            <p class="text-muted mb-0">
                Atualize as informações cadastrais e acadêmicas do estudante.
            </p>
        </div>

        <div class="page-header-actions">
            <x-buttons.link-button
                :href="route('estudantes.visualizar', $student)"
                variant="secondary"
            >
                <x-slot:icon><i class="fa fa-times"></i></x-slot:icon>
                Cancelar
            </x-buttons.link-button>
        </div>
    </div>

    <x-forms.form-card
        action="{{ route('estudantes.atualizar', $student) }}"
        method="POST"
        enctype="multipart/form-data"
        class="form-horizontal"
    >
        @csrf
        @method('PUT')

        <x-forms.section
            title="Dados Pessoais"
            description="Atualize as informações básicas do aluno."
        />

        <x-forms.photo-upload
            name="photo"
            label="Foto do Aluno"
            :current="$student->person->photo_url"
        />

        <x-forms.input
            name="name"
            label="Nome Completo"
            required
            :horizontal="true"
            :value="old('name', $student->person->name)"
        />

        <x-forms.input
            name="document"
            label="Documento (CPF)"
            class="cpf-mask"
            maxlength="14"
            required
            :horizontal="true"
            :value="old('document', $student->person->document)"
        />

        <x-forms.input
            name="registration"
            label="Matrícula"
            required
            :horizontal="true"
            :value="old('registration', $student->registration)"
        />

        <x-forms.input
            name="email"
            label="E-mail"
            type="email"
            required
            :horizontal="true"
            :value="old('email', $student->person->email)"
        />

        <x-forms.input
            name="birth_date"
            label="Data de Nascimento"
            type="date"
            required
            :horizontal="true"
            :value="old('birth_date', optional($student->person->birth_date)->format('Y-m-d'))"
        />

        <x-forms.select
            name="gender"
            label="Gênero"
            :horizontal="true"
            required
            :options="[
            'not_specified' => 'Não informado',
            'male' => 'Masculino',
            'female' => 'Feminino',
            'other' => 'Outro'
        ]"
            :selected="old('gender', $student->person->gender?->value ?? $student->person->gender)"
        />

        <x-forms.input
            name="phone"
            label="Telefone"
            class="phone-mask"
            maxlength="15"
            :horizontal="true"
            :value="old('phone', $student->person->phone)"
        />

        <x-forms.textarea
            name="address"
            label="Endereço"
            rows="3"
            :horizontal="true"
            :value="old('address', $student->person->address)"
        />

        <x-forms.separator />

        <x-forms.section
            title="Gestão e Público"
        />

        <x-forms.input
            name="entry_date"
            label="Data de Ingresso"
            type="date"
            required
            :horizontal="true"
            :value="old('entry_date', $student->entry_date?->format('Y-m-d'))"
        />

        <x-forms.switch
            name="is_active"
            label="Aluno Ativo"
            :horizontal="true"
            :checked="old('is_active', $student->is_active)"
        />

        <x-forms.form-footer>
            <x-buttons.link-button
                :href="route('estudantes.visualizar', $student)"
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

@push('scripts')
    @vite(['resources/js/components/photos.js'])
@endpush
