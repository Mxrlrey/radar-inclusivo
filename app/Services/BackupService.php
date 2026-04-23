<?php

namespace App\Services;

use App\Models\Backup;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;

class BackupService
{
    protected $disk;

    /**
     * RF: inicializa o disco local usado para armazenar e recuperar backups.
     * Uso: prepara o serviço para operações de geração, sincronização e restauração.
     */
    public function __construct()
    {
        $this->disk = Storage::disk('local');
    }

    /**
     * RF: executa a rotina de backup da aplicação e registra o arquivo gerado no banco.
     * Uso: criação manual de cópias de segurança pelo módulo administrativo.
     */
    public function generate(): Backup
    {
        try {
            $exitCode = Artisan::call('backup:run', ['--disable-notifications' => true]);

            if ($exitCode !== 0) {
                $output = trim(Artisan::output());

                throw new Exception(
                    $output !== ''
                        ? "Falha ao executar backup: {$output}"
                        : "Falha ao executar backup: comando backup:run retornou código {$exitCode}."
                );
            }

            $backupFolder = config('backup.backup.name');
            $files        = $this->disk->allFiles($backupFolder);

            $latestFile = collect($files)
                ->filter(fn($f) => str_ends_with($f, '.zip'))
                ->sortByDesc(fn($f) => $this->disk->lastModified($f))
                ->first();

            if ($latestFile) {
                return Backup::create([
                    'file_name' => basename($latestFile),
                    'file_path' => $latestFile,
                    'size'      => $this->formatBytes($this->disk->size($latestFile)),
                    'status'    => 'success',
                    'user_id'   => Auth::id(),
                ]);
            }

            throw new Exception("Backup executado, mas o ZIP não foi encontrado em: {$backupFolder}");

        } catch (Exception $e) {
            Log::error("BackupService@generate: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * RF: valida e armazena um arquivo de backup enviado manualmente pelo usuário.
     * Uso: importação de backups externos para controle interno do sistema.
     */
    public function storeUploadedFile($file): Backup
    {
        try {
            $zip = new ZipArchive();

            if ($zip->open($file->getRealPath()) !== true) {
                throw new Exception('O arquivo enviado não é um backup ZIP válido.');
            }

            $this->assertSafeZipEntries($zip);
            $zip->close();

            $fileName = basename($file->getClientOriginalName());
            $path     = $this->disk->putFileAs('GNAIbackups', $file, $fileName);

            return Backup::create([
                'file_name' => $fileName,
                'file_path' => $path,
                'size'      => $this->formatBytes($file->getSize()),
                'status'    => 'success',
                'user_id'   => Auth::id() ?? 1,
            ]);
        } catch (Exception $e) {
            Log::error("BackupService@storeUploadedFile: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * RF: remove o backup físico e seu registro persistido.
     * Uso: limpeza administrativa de arquivos de backup já descartados.
     */
    public function delete($id): ?bool
    {
        $backup = Backup::findOrFail($id);

        if ($this->disk->exists($backup->file_path)) {
            $this->disk->delete($backup->file_path);
        }

        return $backup->delete();
    }

    /**
     * RF: sincroniza os arquivos de backup existentes no disco com a tabela de controle.
     * Uso: reconciliação de backups gerados fora do fluxo direto da interface.
     */
    public function sync(): bool
    {
        try {
            $backupFolder = config('backup.backup.name');
            $zipFiles     = array_filter(
                $this->disk->allFiles($backupFolder),
                fn($f) => str_ends_with($f, '.zip')
            );

            foreach ($zipFiles as $file) {
                $fileName = basename($file);
                if (!Backup::where('file_name', $fileName)->exists()) {
                    Backup::create([
                        'file_name' => $fileName,
                        'file_path' => $file,
                        'size'      => $this->formatBytes($this->disk->size($file)),
                        'status'    => 'success',
                        'user_id'   => Auth::id() ?? 1,
                    ]);
                }
            }

            foreach (Backup::all() as $dbBackup) {
                if (!$this->disk->exists($dbBackup->file_path)) {
                    $dbBackup->delete();
                }
            }

            return true;
        } catch (Exception $e) {
            Log::error("BackupService@sync: " . $e->getMessage());
            return false;
        }
    }

    /**
     * RF: restaura banco e arquivos de mídia a partir de um backup validado.
     * Uso: recuperação operacional do sistema em cenários de contingência.
     */
    public function restore($id): bool
    {
        $backup = Backup::findOrFail($id);
        $maintenanceModeEnabled = false;

        $fileName   = $backup->file_name;
        $relativePath = str_replace('\\', '/', $backup->file_path);

        $stripPrefixes = [
            'storage/app/private/',
            'storage/app/',
            'private/',
        ];
        foreach ($stripPrefixes as $prefix) {
            if (str_starts_with($relativePath, $prefix)) {
                $relativePath = substr($relativePath, strlen($prefix));
                break;
            }
        }

        $storageRoot = storage_path('app/private');
        $candidates  = [
            $storageRoot . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath),
            $storageRoot . DIRECTORY_SEPARATOR . 'GNAIbackups' . DIRECTORY_SEPARATOR . $fileName,
            storage_path('app' . DIRECTORY_SEPARATOR . 'GNAIbackups' . DIRECTORY_SEPARATOR . $fileName),
        ];

        $zipPath = null;
        foreach ($candidates as $candidate) {
            if (file_exists($candidate)) {
                $zipPath = $candidate;
                break;
            }
        }

        if (!$zipPath) {
            Log::error("BackupService@restore — arquivo não encontrado: {$fileName}");
            throw new Exception("Arquivo físico não encontrado: {$fileName}");
        }

        $zipPath = $this->assertValidBackupZipPath($zipPath);

        set_time_limit(300);

        $tempPath = storage_path('app' . DIRECTORY_SEPARATOR . 'restore-temp-' . time());
        $zip      = new ZipArchive();

        try {
            $preRestoreBackupExitCode = Artisan::call('backup:run', ['--disable-notifications' => true]);

            if ($preRestoreBackupExitCode !== 0) {
                $output = trim(Artisan::output());

                throw new Exception(
                    $output !== ''
                        ? "Falha ao gerar cópia de segurança pré-restauração: {$output}"
                        : "Falha ao gerar cópia de segurança pré-restauração."
                );
            }

            Artisan::call('down');
            $maintenanceModeEnabled = true;

            if ($zip->open($zipPath) !== true) {
                throw new Exception("Falha ao abrir o ZIP: {$zipPath}");
            }

            $this->assertSafeZipEntries($zip);
            $this->extractZipSafely($zip, $tempPath);

            $zip->close();

            $sqlFile = $this->findSqlFile($tempPath);

            if ($sqlFile) {
                $dbConfig  = config('database.connections.mysql');
                $mysqlBin  = $this->resolveMysqlBinary();
                $optFile   = $this->writeMysqlOptionsFile($dbConfig);
                $extraOptions = trim((string) config('database.connections.mysql.dump.add_extra_option', ''));
                $command   = sprintf(
                    '%s --defaults-extra-file=%s %s %s < %s 2>&1',
                    escapeshellarg($mysqlBin),
                    escapeshellarg($optFile),
                    $extraOptions,
                    escapeshellarg($dbConfig['database']),
                    escapeshellarg($sqlFile)
                );

                exec($command, $output, $returnCode);

                if ($optFile && file_exists($optFile)) {
                    @unlink($optFile);
                }

                if ($returnCode !== 0) {
                    $error = collect($output)
                        ->reject(fn($l) => str_contains($l, 'Warning') || str_contains($l, 'Deprecated'))
                        ->first();
                    $error ??= trim(implode("\n", $output));
                    $error = $error !== '' ? $error : "comando mysql retornou código {$returnCode}";

                    Log::error("BackupService@restore — erro SQL: {$error}");
                    throw new Exception("Erro ao importar SQL: {$error}");
                }
            }

            $sourceStorage = $this->findStorageDir($tempPath);

            if ($sourceStorage && is_dir($sourceStorage)) {
                $destination = storage_path('app');
                $this->copyDirectoryContents($sourceStorage, $destination);
            }

            return true;

        } catch (Exception $e) {
            Log::error("BackupService@restore — falha crítica: " . $e->getMessage());
            throw $e;
        } finally {
            if ($maintenanceModeEnabled) {
                Artisan::call('up');
            }

            $this->removeDirectory($tempPath);
        }
    }

    /**
     * RF: resolve o binário do MySQL configurado para restauração do dump.
     * Uso: monta o comando de importação sem fixar caminho por sistema operacional.
     */
    private function resolveMysqlBinary(): string
    {
        $dir        = config('database.connections.mysql.dump.dump_binary_path', '');
        $binaryName = $this->isWindows() ? 'mysql.exe' : 'mysql';

        if ($dir) {
            $full = rtrim($dir, '/\\') . DIRECTORY_SEPARATOR . $binaryName;
            if (file_exists($full)) {
                return $full;
            }
        }

        return $binaryName;
    }

    /**
     * RF: gera um arquivo temporário de opções do MySQL com as credenciais da conexão.
     * Uso: evita expor senha em linha de comando durante a restauração do banco.
     */
    private function writeMysqlOptionsFile(array $dbConfig): string
    {
        $path    = storage_path('app' . DIRECTORY_SEPARATOR . 'mysql-opts-' . time() . '.cnf');
        $content = "[client]\n"
            . "host={$dbConfig['host']}\n"
            . "port={$dbConfig['port']}\n"
            . "user={$dbConfig['username']}\n"
            . "password={$dbConfig['password']}\n";
        file_put_contents($path, $content);
        return $path;
    }

    /**
     * RF: localiza o arquivo SQL único dentro do backup extraído.
     * Uso: identifica o dump que será importado durante a restauração.
     */
    private function findSqlFile(string $directory): ?string
    {
        $matches = [];
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS)
        );
        foreach ($iterator as $file) {
            if ($file->getExtension() === 'sql') {
                $matches[] = $file->getRealPath();
            }
        }

        if (count($matches) === 0) {
            return null;
        }

        if (count($matches) > 1) {
            throw new Exception('O backup contém mais de um arquivo SQL. A restauração foi bloqueada por segurança.');
        }

        return $matches[0];
    }

    /**
     * RF: encontra a pasta de arquivos de mídia dentro da estrutura extraída do backup.
     * Uso: permite restaurar `storage/app` sem depender do caminho da máquina de origem.
     */
    private function findStorageDir(string $tempPath): ?string
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($tempPath, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($iterator as $item) {
            if (
                $item->isDir()
                && $item->getFilename() === 'app'
                && basename(dirname($item->getRealPath())) === 'storage'
            ) {
                return $item->getRealPath();
            }
        }
        return null;
    }

    /**
     * RF: remove diretórios temporários usados no processo de restauração.
     * Uso: limpeza final do workspace temporário criado para extrair o backup.
     */
    private function removeDirectory(string $path): void
    {
        if (!is_dir($path)) return;

        File::deleteDirectory($path);
    }

    /**
     * RF: valida se o caminho físico do backup aponta para um ZIP permitido.
     * Uso: protege a restauração contra arquivos externos ou formatos inválidos.
     */
    private function assertValidBackupZipPath(string $path): string
    {
        $realPath    = realpath($path);
        $storageRoot = realpath(storage_path('app/private'));

        if ($realPath === false || $storageRoot === false) {
            throw new Exception('Não foi possível validar o caminho físico do backup.');
        }

        if (!str_starts_with($realPath, $storageRoot . DIRECTORY_SEPARATOR)) {
            throw new Exception('O arquivo informado está fora do diretório permitido de backups.');
        }

        if (strtolower(pathinfo($realPath, PATHINFO_EXTENSION)) !== 'zip') {
            throw new Exception('Somente arquivos ZIP podem ser restaurados.');
        }

        return $realPath;
    }

    /**
     * RF: valida entradas do ZIP antes da extração para bloquear caminhos inseguros.
     * Uso: protege a restauração contra traversal e links simbólicos maliciosos.
     */
    private function assertSafeZipEntries(ZipArchive $zip): void
    {
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $name = $zip->getNameIndex($i);

            if ($name === false || $name === '') {
                throw new Exception('O ZIP contém entradas inválidas.');
            }

            $normalizedName = str_replace('\\', '/', $name);

            if (
                str_starts_with($normalizedName, '/')
                || preg_match('/^[A-Za-z]:\//', $normalizedName)
                || str_contains($normalizedName, '../')
                || str_contains($normalizedName, '..\\')
            ) {
                throw new Exception("O ZIP contém caminho inseguro: {$name}");
            }

            $stat = $zip->statIndex($i);
            $mode = ($stat['external_attributes'] ?? 0) >> 16;

            if (($mode & 0xF000) === 0xA000) {
                throw new Exception("O ZIP contém link simbólico não permitido: {$name}");
            }
        }
    }

    /**
     * RF: extrai o ZIP garantindo que todos os arquivos permaneçam dentro do destino temporário.
     * Uso: restauração segura do conteúdo compactado antes da importação do backup.
     */
    private function extractZipSafely(ZipArchive $zip, string $destination): void
    {
        File::ensureDirectoryExists($destination);

        $destinationRoot = realpath($destination);

        if ($destinationRoot === false) {
            throw new Exception('Não foi possível resolver o diretório temporário de restauração.');
        }

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $name = str_replace('\\', '/', $zip->getNameIndex($i));
            $target = $destinationRoot . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $name);
            $targetDirectory = str_ends_with($name, '/')
                ? $target
                : dirname($target);

            File::ensureDirectoryExists($targetDirectory);

            $resolvedDir = realpath($targetDirectory);

            if ($resolvedDir === false || !str_starts_with($resolvedDir, $destinationRoot)) {
                throw new Exception("Falha ao extrair entrada do ZIP com segurança: {$name}");
            }

            if (str_ends_with($name, '/')) {
                continue;
            }

            $stream = $zip->getStream($zip->getNameIndex($i));

            if ($stream === false) {
                throw new Exception("Não foi possível ler a entrada do ZIP: {$name}");
            }

            $targetHandle = fopen($target, 'wb');

            if ($targetHandle === false) {
                fclose($stream);
                throw new Exception("Não foi possível criar o arquivo extraído: {$name}");
            }

            stream_copy_to_stream($stream, $targetHandle);
            fclose($stream);
            fclose($targetHandle);
        }
    }

    /**
     * RF: replica os arquivos restaurados da mídia para o storage da aplicação.
     * Uso: recompõe imagens e anexos após a importação do backup.
     */
    private function copyDirectoryContents(string $source, string $destination): void
    {
        File::ensureDirectoryExists($destination);

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            $targetPath = $destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName();

            if ($item->isDir()) {
                File::ensureDirectoryExists($targetPath);
                continue;
            }

            File::ensureDirectoryExists(dirname($targetPath));

            if (!copy($item->getRealPath(), $targetPath)) {
                throw new Exception("Falha ao restaurar arquivo de mídia: {$targetPath}");
            }
        }
    }

    /**
     * RF: identifica se a aplicação está executando em ambiente Windows.
     * Uso: ajusta resolução de binários e compatibilidade do processo de restauração.
     */
    private function isWindows(): bool
    {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }

    /**
     * RF: converte bytes para uma representação legível de tamanho.
     * Uso: preenche metadados amigáveis de tamanho nos registros de backup.
     */
    private function formatBytes(int|float $bytes, int $precision = 2): string
    {
        $units  = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes  = (float) max($bytes, 0);

        if ($bytes === 0.0) return '0 B';

        $power   = min((int) floor(log($bytes, 1024)), count($units) - 1);
        $rounded = round($bytes / (1024 ** $power), $precision);

        return $rounded . ' ' . $units[$power];
    }
}
