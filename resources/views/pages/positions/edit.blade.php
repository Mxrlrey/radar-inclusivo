@extends('layouts.master')

@section('title', "Editar - $position->name")

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Cargos' => route('cargos.index'),
                $position->name => route('cargos.visualizar', $position),
                'Editar' => null
            ]" />

            <h1>Editar Cargo</h1>
            <p class="text-muted mb-0">
                Altere as atribuições e gerencie as permissões do cargo <strong>{{ $position->name }}</strong>.
            </p>
        </div>

        <div class="page-header-actions">
            <x-buttons.link-button :href="route('cargos.visualizar', $position)" variant="secondary">
                <x-slot:icon><i class="fa fa-times"></i></x-slot:icon>
                Cancelar
            </x-buttons.link-button>
        </div>
    </div>

    <x-forms.form-card
        action="{{ route('cargos.atualizar', $position) }}"
        method="POST"
        class="form-horizontal"
    >
        @csrf
        @method('PUT')

        <x-forms.section
            title="Identificação do Cargo"
            description="Atualize os dados básicos e o status do cargo."
        />

        <x-forms.input
            name="name"
            label="Nome do Cargo"
            required
            :horizontal="true"
            :value="old('name', $position->name)"
        />

        <x-forms.switch
            name="is_active"
            label="Cargo Ativo"
            :horizontal="true"
            :checked="old('is_active', $position->is_active)"
        />

        <x-forms.textarea
            name="description"
            label="Descrição / Atribuições"
            :horizontal="true"
            rows="4"
            :value="old('description', $position->description)"
        />

        <x-forms.separator />

        <x-forms.section
            title="Segurança e Acessos"
            description="Selecione as permissões vinculadas a esta função."
        />

        <fieldset class="form-group-horizontal mb-4">
            <legend class="control-label mb-0">Permissões</legend>
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

                <div
                    class="permissions-container border @error('permissions') border-danger @enderror"
                    @error('permissions') aria-describedby="permissions-error" @enderror
                >
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
                                                :checked="in_array($permission->id, old('permissions', $position->permissions->pluck('id')->toArray()))"
                                                :label="$permission->name"
                                            />
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @error('permissions')
                <small class="text-danger d-block mt-1" id="permissions-error">{{ $message }}</small>
                @enderror
            </div>
        </fieldset>

        <x-forms.form-footer>
            <x-buttons.link-button :href="route('cargos.visualizar', $position)" variant="secondary">
                <x-slot:icon><i class="fa fa-times"></i></x-slot:icon>
                Cancelar
            </x-buttons.link-button>

            <x-buttons.submit-button variant="new">
                <x-slot:icon><i class="fa fa-save"></i></x-slot:icon>
                Salvar
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
                        document.querySelectorAll('.permission-checkbox input[type="checkbox"]').forEach(cb => {
                            cb.checked = isChecked;
                        });
                    });
                }
            });
        </script>
    @endpush
@endsection
