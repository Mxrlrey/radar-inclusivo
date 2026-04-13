@extends('layouts.master')

@section('title', 'Cadastrar - Cargo')

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Cargos' => route('positions.index'),
                'Cadastrar' => null
            ]" />
            <h1>Novo Cargo</h1>
            <p class="text-muted mb-0">
                Defina as atribuições, responsabilidades e permissões vinculadas ao cargo.
            </p>
        </div>

        <div class="page-header-actions">
            <x-buttons.link-button
                :href="route('positions.index')"
                variant="secondary"
            >
                <x-slot:icon><i class="fa fa-times"></i></x-slot:icon>
                Cancelar
            </x-buttons.link-button>
        </div>
    </div>

    <x-forms.form-card
        action="{{ route('positions.store') }}"
        method="POST"
        class="form-horizontal"
    >
        @csrf

        <x-forms.section
            title="Identificação do Cargo"
            description="Informe os dados básicos e a descrição da função."
        />

        <x-forms.input
            name="name"
            label="Nome do Cargo"
            required
            :horizontal="true"
            placeholder="Ex: Professor AEE, Psicólogo..."
            :value="old('name')"
        />

        <x-forms.textarea
            name="description"
            label="Descrição / Atribuições"
            :horizontal="true"
            rows="4"
            placeholder="Descreva as responsabilidades deste cargo..."
            :value="old('description')"
        />

        <x-forms.separator />

        <x-forms.section
            title="Segurança e Acessos"
            description="Selecione as permissões que este cargo terá no sistema."
        />

        <div class="form-group-horizontal mb-4">
            <label class="control-label">Permissões</label>
            <div class="field-wrapper">

                <div class="mb-3 p-3 bg-surface-secondary border d-flex align-items-center justify-content-between">
                    <span class="text-muted small fw-bold text-uppercase">Controle de Acesso Global</span>
                    <x-forms.checkbox
                        name="check_all_global"
                        id="check-all-global"
                        label="Selecionar Todas as Permissões"
                        class="check-all-master"
                    />
                </div>

                <div class="permissions-container border">
                    @foreach($permissions as $group => $groupPermissions)
                        <div class="permission-group-block border-bottom">
                            <div class="px-3 py-2 bg-light border-bottom">
                                <h6 class="mb-0 fw-bold text-uppercase small text-primary" style="letter-spacing: 1px;">
                                    {{ ucfirst(str_replace('-', ' ', $group)) }}
                                </h6>
                            </div>

                            <div class="p-3">
                                <div class="row g-2">
                                    @foreach($groupPermissions as $permission)
                                        <div class="col-md-4 col-lg-3">
                                            <x-forms.checkbox
                                                name="permissions[]"
                                                :value="$permission->id"
                                                :id="'permission-'.$permission->id"
                                                class="permission-checkbox"
                                                :checked="is_array(old('permissions')) && in_array($permission->id, old('permissions'))"
                                                :label="$permission->name"
                                            />
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <x-forms.form-footer>
            <x-buttons.link-button
                :href="route('positions.index')"
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
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const masterCheckbox = document.getElementById('check-all-global');

                if (masterCheckbox) {
                    masterCheckbox.addEventListener('change', function () {
                        const isChecked = this.checked;
                        // Seleciona todos os inputs que fazem parte do array de permissões
                        document.querySelectorAll('.permission-checkbox input[type="checkbox"]').forEach(cb => {
                            cb.checked = isChecked;
                        });
                    });
                }
            });
        </script>
    @endpush
@endsection
