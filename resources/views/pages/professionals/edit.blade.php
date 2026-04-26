@extends('layouts.master')

@section('title', "Editar - {$professional->person->name}")

@section('content')

    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Profissionais' => route('profissionais.index'),
                $professional->person->name => route('profissionais.visualizar', $professional),
                'Editar' => null
            ]" />

            <h1>Editar Profissional</h1>
            <p class="text-muted mb-0">
                Atualize as informações cadastrais e funcionais do profissional.
            </p>
        </div>

        <div class="page-header-actions">
            <x-buttons.link-button
                :href="route('profissionais.visualizar', $professional)"
                variant="secondary"
            >
                <x-slot:icon><i class="fa fa-times" aria-hidden="true"></i></x-slot:icon>
                Cancelar
            </x-buttons.link-button>
        </div>
    </div>

    <x-forms.form-card
        action="{{ route('profissionais.atualizar', $professional) }}"
        method="POST"
        enctype="multipart/form-data"
        class="form-horizontal"
    >
        @method('PUT')

        <x-forms.section
            title="Dados Pessoais"
            description="Atualize as informações básicas do profissional."
        />

        <x-forms.photo-upload
            name="photo"
            label="Foto do Profissional"
            :current="$professional->person->photo_url"
        />

        <x-forms.input
            name="name"
            label="Nome Completo"
            required
            :horizontal="true"
            :value="old('name', $professional->person->name)"
        />

        <x-forms.input
            name="document"
            label="CPF"
            class="cpf-mask"
            maxlength="14"
            placeholder="000.000.000-00"
            required
            :horizontal="true"
            :value="old('document', $professional->person->document)"
        />

        <x-forms.input
            name="registration"
            label="Matrícula"
            required
            :horizontal="true"
            :value="old('registration', $professional->registration)"
        />

        <x-forms.input
            name="email"
            label="E-mail"
            type="email"
            required
            :horizontal="true"
            :value="old('email', $professional->person->email)"
        />

        <x-forms.select
            name="position_id"
            label="Cargo"
            required
            :horizontal="true"
            :options="$positions->pluck('name', 'id')"
            :selected="old('position_id', $professional->position_id)"
        />

        <x-forms.input
            name="birth_date"
            label="Data de Nascimento"
            type="date"
            required
            :horizontal="true"
            :value="old('birth_date', optional($professional->person->birth_date)->format('Y-m-d'))"
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
            :selected="old('gender', $professional->person->gender?->value ?? $professional->person->gender)"
        />

        <x-forms.input
            name="phone"
            label="Telefone"
            class="phone-mask"
            maxlength="15"
            placeholder="(00) 00000-0000"
            :horizontal="true"
            :value="old('phone', $professional->person->phone)"
        />

        <x-forms.textarea
            name="address"
            label="Endereço"
            rows="3"
            :horizontal="true"
            :value="old('address', $professional->person->address)"
        />

        @can('system.admin.view')
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
                :value="old('entry_date', $professional->entry_date?->format('Y-m-d'))"
            />

            <x-forms.switch
                name="is_active"
                label="Profissional Ativo"
                :horizontal="true"
                :checked="old('is_active', $professional->is_active)"
            />

            <x-forms.switch
                name="is_admin"
                label="Administrador do sistema"
                :horizontal="true"
                :checked="old('is_admin', optional($professional->user)->is_admin)"
            />
        @endcan

        <x-forms.form-footer>
            <x-buttons.link-button
                :href="route('profissionais.visualizar', $professional)"
                variant="secondary"
            >
                <x-slot:icon><i class="fa fa-times" aria-hidden="true"></i></x-slot:icon>
                Cancelar
            </x-buttons.link-button>

            <x-buttons.submit-button variant="new">
                <x-slot:icon><i class="fa fa-save" aria-hidden="true"></i></x-slot:icon>
                Salvar
            </x-buttons.submit-button>
        </x-forms.form-footer>
    </x-forms.form-card>
@endsection

@push('scripts')
    @vite(['resources/js/components/photos.js'])
@endpush
