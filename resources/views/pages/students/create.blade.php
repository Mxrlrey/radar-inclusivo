@extends('layouts.master')

@section('title', 'Cadastrar - Aluno')

@section('content')

    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Alunos' => route('estudantes.index'),
                'Cadastrar' => null
            ]" />

            <h1>Novo Aluno</h1>
            <p class="text-muted mb-0">
                Cadastre um novo estudante com seus dados pessoais e acadêmicos.
            </p>
        </div>

        <div class="page-header-actions">
            <x-buttons.link-button
                :href="route('estudantes.index')"
                variant="secondary"
            >
                <x-slot:icon><i class="fa fa-times"></i></x-slot:icon>
                Cancelar
            </x-buttons.link-button>
        </div>
    </div>

    <x-forms.form-card
        action="{{ route('estudantes.salvar') }}"
        method="POST"
        enctype="multipart/form-data"
        class="form-horizontal"
    >
        @csrf

        <x-forms.section
            title="Dados Pessoais"
            description="Informações básicas de identificação do aluno."
        />

        <x-forms.photo-upload
            name="photo"
            label="Foto do Aluno"
        />

        <x-forms.input
            name="name"
            label="Nome Completo"
            required
            :horizontal="true"
            :value="old('name')"
        />

        <x-forms.input
            name="document"
            label="CPF"
            required
            class="cpf-mask"
            maxlength="14"
            placeholder="000.000.000-00"
            :horizontal="true"
            :value="old('document')"
        />

        <x-forms.input
            name="registration"
            label="Matrícula"
            required
            :horizontal="true"
            :value="old('registration')"
        />

        <x-forms.input
            name="email"
            label="E-mail"
            type="email"
            required
            :horizontal="true"
            :value="old('email')"
        />

        <x-forms.input
            name="birth_date"
            label="Data de Nascimento"
            type="date"
            required
            :horizontal="true"
            :value="old('birth_date')"
        />

        <x-forms.select
            name="gender"
            label="Gênero"
            required
            :horizontal="true"
            :options="[
                'male' => 'Masculino',
                'female' => 'Feminino',
                'other' => 'Outro',
                'not_specified' => 'Não informado'
            ]"
            :selected="old('gender', 'not_specified')"
        />

        <x-forms.input
            name="phone"
            label="Telefone"
            class="phone-mask"
            maxlength="15"
            placeholder="(00) 00000-0000"
            :horizontal="true"
            :value="old('phone')"
        />

        <x-forms.textarea
            name="address"
            label="Endereço"
            rows="3"
            :horizontal="true"
            :value="old('address')"
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
            :value="old('entry_date', date('Y-m-d'))"
        />

        <x-forms.switch
            name="is_active"
            label="Aluno Ativo"
            :horizontal="true"
            :checked="old('is_active', true)"
        />

        <x-forms.form-footer>
            <x-buttons.link-button
                :href="route('estudantes.index')"
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
    @push('scripts')
        @vite(['resources/js/components/photos.js'])
    @endpush
@endsection

