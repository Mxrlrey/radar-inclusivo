<?php

namespace App\Console\Commands;

use App\Services\BackupService;
use Illuminate\Console\Command;

class BackupAutomatic extends Command
{
    protected $signature = 'backup:automatic';
    protected $description = 'Executa backup automático do sistema';

    public function handle(BackupService $backupService)
    {
        $this->info('Iniciando backup automático...');

        $backupService->generate();

        $this->info('Backup concluído com sucesso.');

        return Command::SUCCESS;
    }
}
