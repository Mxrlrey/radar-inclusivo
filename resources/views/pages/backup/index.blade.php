@extends('layouts.master')

@section('title', 'Gerenciamento de Backups')

@section('content')
    <div class="page-header">
        <div class="page-header-title">
            <x-breadcrumb :items="[
                'Home' => route('dashboard'),
                'Backups' => null
            ]" />
            <h1>Gerenciamento de Backups</h1>
            <p class="text-muted mb-0">
                Visualize e administre as cópias de segurança do sistema.
            </p>
        </div>

        <div class="page-header-actions d-flex gap-2">
            @can('backup.upload')
                <form action="{{ route('copias-seguranca.enviar') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file"
                           name="backup_file"
                           id="input-backup-file"
                           class="d-none"
                           accept=".zip"
                           onchange="if(this.value) this.form.submit()">

                    <x-buttons.link-button
                        type="button"
                        variant="info"
                        onclick="document.getElementById('input-backup-file').click()"
                    >
                        <span class="btn-label">
                            <i class="fa fa-upload"></i>
                        </span>
                        Importar
                    </x-buttons.link-button>
                </form>
            @endcan

            @can('backup.store')
                <form action="{{ route('copias-seguranca.salvar') }}" method="POST">
                    @csrf
                    <x-buttons.submit-button variant="new">
                        <span class="btn-label">
                            <i class="fa fa-database"></i>
                        </span>
                        Gerar Backup
                    </x-buttons.submit-button>
                </form>
            @endcan
        </div>
    </div>

    <div class="card-custom overflow-hidden">
        <div class="px-3 pt-3">
            <x-table.filters.form
                data-url="{{ route('copias-seguranca.index') }}"
                data-target="#backups-table"
                :fields="[
                    [
                        'name' => 'name',
                        'label' => 'Arquivo'
                    ],
                    [
                        'name' => 'user_id',
                        'type' => 'select',
                        'label' => 'Responsável',
                        'options' => $users->mapWithKeys(fn($u) => [$u->id => $u->name])->prepend('Todos', '')
                    ]
                ]"
            />
        </div>

        <div id="backups-table" class="p-3" role="region" aria-label="Listagem de backups">
            @include('pages.backup.partials.table')
        </div>
    </div>

    <div class="mt-4 alert alert-info border-0">
        <strong>Política de armazenamento:</strong>
        Backups ficam em <code>storage/app/GNAI</code>. Arquivos arquivados não são removidos automaticamente.
    </div>

    <script>
        document.querySelectorAll('.form-restore').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const fileName = this.querySelector('.btn-restore').dataset.filename;

                if (confirm(`Restaurar backup ${fileName}? Isso substituirá os dados atuais.`)) {
                    const btn = this.querySelector('.btn-restore');
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
                    this.submit();
                }
            });
        });
    </script>
    @push('scripts')
        @vite('resources/js/components/dynamicFilters.js')
    @endpush
@endsection
