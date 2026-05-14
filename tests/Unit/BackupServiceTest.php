<?php

namespace Tests\Unit;

use App\Models\Backup;
use App\Models\User;
use App\Services\BackupService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use ZipArchive;
use ReflectionMethod;
use ReflectionProperty;

class BackupServiceTest extends TestCase
{
    use RefreshDatabase;

    protected BackupService $service;
    protected User $user;
    protected array $pathsToDelete = [];

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
        $this->service = app(BackupService::class);
    }

    protected function tearDown(): void
    {
        foreach (array_reverse($this->pathsToDelete) as $path) {
            if (is_link($path) || is_file($path)) {
                @unlink($path);
                continue;
            }

            if (is_dir($path)) {
                File::deleteDirectory($path);
            }
        }

        parent::tearDown();
    }

    public function test_generate_registers_latest_zip_created_by_backup_command(): void
    {
        // Arrange
        config(['backup.backup.name' => 'GNAIbackups']);
        Storage::disk('local')->put('GNAIbackups/backup-latest.zip', str_repeat('a', 2048));

        Artisan::shouldReceive('call')
            ->once()
            ->with('backup:run', ['--disable-notifications' => true])
            ->andReturn(0);

        // Act
        $backup = $this->service->generate();

        // Assert
        $this->assertDatabaseHas('backups', [
            'id' => $backup->id,
            'file_name' => 'backup-latest.zip',
            'file_path' => 'GNAIbackups/backup-latest.zip',
            'size' => '2 KB',
            'status' => 'success',
            'user_id' => $this->user->id,
        ]);
    }

    public function test_generate_throws_when_artisan_backup_command_fails(): void
    {
        // Arrange
        Artisan::shouldReceive('call')
            ->once()
            ->with('backup:run', ['--disable-notifications' => true])
            ->andReturn(1);
        Artisan::shouldReceive('output')
            ->once()
            ->andReturn('permissao negada');

        // Assert
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Falha ao executar backup: permissao negada');

        // Act
        $this->service->generate();
    }

    public function test_generate_throws_when_no_zip_is_created(): void
    {
        // Arrange
        config(['backup.backup.name' => 'GNAIbackups']);

        Artisan::shouldReceive('call')
            ->once()
            ->with('backup:run', ['--disable-notifications' => true])
            ->andReturn(0);

        // Assert
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Backup executado, mas o ZIP não foi encontrado em: GNAIbackups');

        // Act
        $this->service->generate();
    }

    public function test_store_uploaded_file_persists_safe_zip_and_registers_metadata(): void
    {
        // Arrange
        $file = $this->makeUploadedZip('backup-upload.zip', [
            'db-dumps/mysql.sql' => 'select 1;',
            'storage/app/public/readme.txt' => 'arquivo',
        ]);

        // Act
        $backup = $this->service->storeUploadedFile($file);

        // Assert
        Storage::disk('local')->assertExists('GNAIbackups/backup-upload.zip');

        $this->assertDatabaseHas('backups', [
            'id' => $backup->id,
            'file_name' => 'backup-upload.zip',
            'file_path' => 'GNAIbackups/backup-upload.zip',
            'status' => 'success',
            'user_id' => $this->user->id,
        ]);
        $this->assertNotSame('0 B', $backup->size);
    }

    public function test_store_uploaded_file_rejects_invalid_zip(): void
    {
        // Arrange
        $path = tempnam(sys_get_temp_dir(), 'invalid-backup-');
        file_put_contents($path, 'nao e zip');
        $file = new UploadedFile($path, 'invalid.zip', 'application/zip', null, true);

        // Assert
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('O arquivo enviado não é um backup ZIP válido.');

        // Act
        $this->service->storeUploadedFile($file);
    }

    public function test_store_uploaded_file_rejects_zip_with_path_traversal_entry(): void
    {
        // Arrange
        $file = $this->makeUploadedZip('unsafe.zip', [
            '../evil.txt' => 'bad',
        ]);

        // Assert
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('O ZIP contém caminho inseguro: ../evil.txt');

        // Act
        $this->service->storeUploadedFile($file);
    }

    public function test_delete_removes_physical_file_and_database_record(): void
    {
        // Arrange
        Storage::disk('local')->put('GNAIbackups/remove-me.zip', 'zip');
        $backup = Backup::create([
            'file_name' => 'remove-me.zip',
            'file_path' => 'GNAIbackups/remove-me.zip',
            'size' => '3 B',
            'status' => 'success',
            'user_id' => $this->user->id,
        ]);

        // Act
        $deleted = $this->service->delete($backup->id);

        // Assert
        $this->assertTrue($deleted);
        Storage::disk('local')->assertMissing('GNAIbackups/remove-me.zip');
        $this->assertDatabaseMissing('backups', ['id' => $backup->id]);
    }

    public function test_sync_creates_records_for_zip_files_and_ignores_existing_records(): void
    {
        // Arrange
        config(['backup.backup.name' => 'GNAIbackups']);
        Storage::disk('local')->put('GNAIbackups/new-backup.zip', str_repeat('a', 1024));
        Storage::disk('local')->put('GNAIbackups/existing-backup.zip', str_repeat('b', 512));
        Storage::disk('local')->put('GNAIbackups/readme.txt', 'ignore');

        Backup::create([
            'file_name' => 'existing-backup.zip',
            'file_path' => 'GNAIbackups/existing-backup.zip',
            'size' => 'old',
            'status' => 'success',
            'user_id' => $this->user->id,
        ]);

        // Act
        $synced = $this->service->sync();

        // Assert
        $this->assertTrue($synced);
        $this->assertDatabaseHas('backups', [
            'file_name' => 'new-backup.zip',
            'file_path' => 'GNAIbackups/new-backup.zip',
            'size' => '1 KB',
            'status' => 'success',
            'user_id' => $this->user->id,
        ]);
        $this->assertSame(1, Backup::where('file_name', 'existing-backup.zip')->count());
        $this->assertDatabaseMissing('backups', ['file_name' => 'readme.txt']);
    }

    public function test_sync_removes_database_records_for_missing_files(): void
    {
        // Arrange
        config(['backup.backup.name' => 'GNAIbackups']);
        $backup = Backup::create([
            'file_name' => 'missing.zip',
            'file_path' => 'GNAIbackups/missing.zip',
            'size' => '1 KB',
            'status' => 'success',
            'user_id' => $this->user->id,
        ]);

        // Act
        $synced = $this->service->sync();

        // Assert
        $this->assertTrue($synced);
        $this->assertDatabaseMissing('backups', ['id' => $backup->id]);
    }

    public function test_restore_throws_when_physical_file_does_not_exist(): void
    {
        // Arrange
        $backup = Backup::create([
            'file_name' => 'missing-restore.zip',
            'file_path' => 'GNAIbackups/missing-restore.zip',
            'size' => '1 KB',
            'status' => 'success',
            'user_id' => $this->user->id,
        ]);

        // Assert
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Arquivo físico não encontrado: missing-restore.zip');

        // Act
        $this->service->restore($backup->id);
    }

    public function test_restore_copies_storage_files_from_valid_zip_without_sql(): void
    {
        // Arrange
        $zipPath = $this->makePhysicalBackupZip('restore-copy.zip', [
            'storage/' => null,
            'storage/app/' => null,
            'storage/app/public/' => null,
            'storage/app/public/restore-test/copied.txt' => 'conteudo restaurado',
        ]);
        $destination = storage_path('app/public/restore-test');
        $this->pathsToDelete[] = $destination;

        $backup = Backup::create([
            'file_name' => 'restore-copy.zip',
            'file_path' => 'storage/app/private/GNAIbackups/restore-copy.zip',
            'size' => '1 KB',
            'status' => 'success',
            'user_id' => $this->user->id,
        ]);

        Artisan::shouldReceive('call')
            ->once()
            ->with('backup:run', ['--disable-notifications' => true])
            ->andReturn(0)
            ->ordered();
        Artisan::shouldReceive('call')
            ->once()
            ->with('down')
            ->andReturn(0)
            ->ordered();
        Artisan::shouldReceive('call')
            ->once()
            ->with('up')
            ->andReturn(0)
            ->ordered();

        // Act
        $restored = $this->service->restore($backup->id);

        // Assert
        $this->assertTrue($restored);
        $this->assertFileExists(storage_path('app/public/restore-test/copied.txt'));
        $this->assertSame('conteudo restaurado', file_get_contents(storage_path('app/public/restore-test/copied.txt')));
        $this->assertFileExists($zipPath);
    }

    public function test_restore_throws_when_pre_restore_backup_fails(): void
    {
        // Arrange
        $this->makePhysicalBackupZip('restore-pre-fail.zip', [
            'storage/app/public/file.txt' => 'conteudo',
        ]);

        $backup = Backup::create([
            'file_name' => 'restore-pre-fail.zip',
            'file_path' => 'GNAIbackups/restore-pre-fail.zip',
            'size' => '1 KB',
            'status' => 'success',
            'user_id' => $this->user->id,
        ]);

        Artisan::shouldReceive('call')
            ->once()
            ->with('backup:run', ['--disable-notifications' => true])
            ->andReturn(1);
        Artisan::shouldReceive('output')
            ->once()
            ->andReturn('sem permissao no storage');

        // Assert
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Falha ao gerar cópia de segurança pré-restauração: sem permissao no storage');

        // Act
        $this->service->restore($backup->id);
    }

    public function test_restore_rejects_zip_with_unsafe_entry_and_exits_maintenance_mode(): void
    {
        // Arrange
        $this->makePhysicalBackupZip('restore-unsafe.zip', [
            '../evil.txt' => 'bad',
        ]);

        $backup = Backup::create([
            'file_name' => 'restore-unsafe.zip',
            'file_path' => 'GNAIbackups/restore-unsafe.zip',
            'size' => '1 KB',
            'status' => 'success',
            'user_id' => $this->user->id,
        ]);

        Artisan::shouldReceive('call')
            ->once()
            ->with('backup:run', ['--disable-notifications' => true])
            ->andReturn(0)
            ->ordered();
        Artisan::shouldReceive('call')
            ->once()
            ->with('down')
            ->andReturn(0)
            ->ordered();
        Artisan::shouldReceive('call')
            ->once()
            ->with('up')
            ->andReturn(0)
            ->ordered();

        // Assert
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('O ZIP contém caminho inseguro: ../evil.txt');

        // Act
        $this->service->restore($backup->id);
    }

    public function test_restore_rejects_backup_with_more_than_one_sql_file(): void
    {
        // Arrange
        $this->makePhysicalBackupZip('restore-many-sql.zip', [
            'db-dumps/first.sql' => 'select 1;',
            'db-dumps/second.sql' => 'select 2;',
        ]);

        $backup = Backup::create([
            'file_name' => 'restore-many-sql.zip',
            'file_path' => 'GNAIbackups/restore-many-sql.zip',
            'size' => '1 KB',
            'status' => 'success',
            'user_id' => $this->user->id,
        ]);

        Artisan::shouldReceive('call')
            ->once()
            ->with('backup:run', ['--disable-notifications' => true])
            ->andReturn(0)
            ->ordered();
        Artisan::shouldReceive('call')
            ->once()
            ->with('down')
            ->andReturn(0)
            ->ordered();
        Artisan::shouldReceive('call')
            ->once()
            ->with('up')
            ->andReturn(0)
            ->ordered();

        // Assert
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('O backup contém mais de um arquivo SQL. A restauração foi bloqueada por segurança.');

        // Act
        $this->service->restore($backup->id);
    }

    public function test_restore_rejects_non_zip_file(): void
    {
        // Arrange
        $path = $this->backupDirectory() . DIRECTORY_SEPARATOR . 'restore-not-zip.txt';
        file_put_contents($path, 'conteudo');
        $this->pathsToDelete[] = $path;

        $backup = Backup::create([
            'file_name' => 'restore-not-zip.txt',
            'file_path' => 'GNAIbackups/restore-not-zip.txt',
            'size' => '1 KB',
            'status' => 'success',
            'user_id' => $this->user->id,
        ]);

        // Assert
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Somente arquivos ZIP podem ser restaurados.');

        // Act
        $this->service->restore($backup->id);
    }

    public function test_restore_rejects_symlink_that_points_outside_private_storage(): void
    {
        // Arrange
        $outsideZip = tempnam(sys_get_temp_dir(), 'outside-backup-');
        $this->pathsToDelete[] = $outsideZip;

        $zip = new ZipArchive();
        $zip->open($outsideZip, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $zip->addFromString('storage/app/public/file.txt', 'conteudo');
        $zip->close();

        $link = $this->backupDirectory() . DIRECTORY_SEPARATOR . 'restore-link.zip';
        if (!@symlink($outsideZip, $link)) {
            $this->markTestSkipped('O ambiente não permite criar links simbólicos.');
        }
        $this->pathsToDelete[] = $link;

        $backup = Backup::create([
            'file_name' => 'restore-link.zip',
            'file_path' => 'GNAIbackups/restore-link.zip',
            'size' => '1 KB',
            'status' => 'success',
            'user_id' => $this->user->id,
        ]);

        // Assert
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('O arquivo informado está fora do diretório permitido de backups.');

        // Act
        $this->service->restore($backup->id);
    }

    public function test_sync_returns_false_when_disk_listing_fails(): void
    {
        $disk = new class {
            public function allFiles($path): array
            {
                throw new Exception('disk failure');
            }
        };

        $property = new ReflectionProperty($this->service, 'disk');
        $property->setAccessible(true);
        $property->setValue($this->service, $disk);

        $this->assertFalse($this->service->sync());
    }

    public function test_restore_rejects_zip_that_cannot_be_opened_after_maintenance_mode(): void
    {
        $path = $this->backupDirectory() . DIRECTORY_SEPARATOR . 'broken.zip';
        file_put_contents($path, 'not really a zip');
        $this->pathsToDelete[] = $path;

        $backup = Backup::create([
            'file_name' => 'broken.zip',
            'file_path' => 'GNAIbackups/broken.zip',
            'size' => '1 KB',
            'status' => 'success',
            'user_id' => $this->user->id,
        ]);

        Artisan::shouldReceive('call')->once()->with('backup:run', ['--disable-notifications' => true])->andReturn(0)->ordered();
        Artisan::shouldReceive('call')->once()->with('down')->andReturn(0)->ordered();
        Artisan::shouldReceive('call')->once()->with('up')->andReturn(0)->ordered();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Falha ao abrir o ZIP');

        $this->service->restore($backup->id);
    }

    public function test_restore_reports_sql_import_errors_without_warning_lines(): void
    {
        $binDir = storage_path('app/mysql-test-bin-' . uniqid());
        File::ensureDirectoryExists($binDir);
        $mysql = $binDir . DIRECTORY_SEPARATOR . 'mysql';
        file_put_contents($mysql, "#!/bin/sh\necho 'Warning: noisy'\necho 'ERROR 1064 syntax'\nexit 1\n");
        chmod($mysql, 0755);
        $this->pathsToDelete[] = $binDir;

        config([
            'database.connections.mysql.dump.dump_binary_path' => $binDir,
            'database.connections.mysql.dump.add_extra_option' => '',
        ]);

        $this->makePhysicalBackupZip('restore-sql-fail.zip', [
            'db-dumps/database.sql' => 'select broken;',
        ]);

        $backup = Backup::create([
            'file_name' => 'restore-sql-fail.zip',
            'file_path' => 'GNAIbackups/restore-sql-fail.zip',
            'size' => '1 KB',
            'status' => 'success',
            'user_id' => $this->user->id,
        ]);

        Artisan::shouldReceive('call')->once()->with('backup:run', ['--disable-notifications' => true])->andReturn(0)->ordered();
        Artisan::shouldReceive('call')->once()->with('down')->andReturn(0)->ordered();
        Artisan::shouldReceive('call')->once()->with('up')->andReturn(0)->ordered();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Erro ao importar SQL: ERROR 1064 syntax');

        $this->service->restore($backup->id);
    }

    public function test_private_backup_helpers_cover_edge_branches(): void
    {
        $temp = storage_path('app/backup-helper-' . uniqid());
        File::ensureDirectoryExists($temp);
        $this->pathsToDelete[] = $temp;

        $mysqlDir = $temp . DIRECTORY_SEPARATOR . 'bin';
        File::ensureDirectoryExists($mysqlDir);
        $mysql = $mysqlDir . DIRECTORY_SEPARATOR . 'mysql';
        file_put_contents($mysql, '');
        config(['database.connections.mysql.dump.dump_binary_path' => $mysqlDir]);

        $this->assertSame($mysql, $this->invokeBackupPrivate('resolveMysqlBinary'));

        config(['database.connections.mysql.dump.dump_binary_path' => '']);
        $this->assertSame('mysql', $this->invokeBackupPrivate('resolveMysqlBinary'));

        $options = $this->invokeBackupPrivate('writeMysqlOptionsFile', [
            'host' => '127.0.0.1',
            'port' => 3306,
            'username' => 'radar',
            'password' => 'secret',
        ]);
        $this->pathsToDelete[] = $options;
        $this->assertStringContainsString('password=secret', file_get_contents($options));

        $sqlDir = $temp . DIRECTORY_SEPARATOR . 'sql';
        File::ensureDirectoryExists($sqlDir);
        file_put_contents($sqlDir . DIRECTORY_SEPARATOR . 'dump.sql', 'select 1');
        $this->assertSame(
            realpath($sqlDir . DIRECTORY_SEPARATOR . 'dump.sql'),
            $this->invokeBackupPrivate('findSqlFile', $sqlDir)
        );

        $this->assertNull($this->invokeBackupPrivate('findStorageDir', $sqlDir));

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Não foi possível validar o caminho físico do backup.');
        $this->invokeBackupPrivate('assertValidBackupZipPath', $temp . DIRECTORY_SEPARATOR . 'missing.zip');
    }

    public function test_backup_zip_and_copy_helpers_cover_defensive_error_branches(): void
    {
        $zipPath = tempnam(sys_get_temp_dir(), 'symlink-zip-');
        $this->pathsToDelete[] = $zipPath;

        $zip = new ZipArchive();
        $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $zip->addFromString('link', 'target');
        $zip->setExternalAttributesName('link', ZipArchive::OPSYS_UNIX, 0120000 << 16);
        $zip->close();

        $zip = new ZipArchive();
        $zip->open($zipPath);

        try {
            $this->invokeBackupPrivate('assertSafeZipEntries', $zip);
        } catch (Exception $exception) {
            $this->assertSame('O ZIP contém link simbólico não permitido: link', $exception->getMessage());
        } finally {
            $zip->close();
        }

        $source = storage_path('app/copy-source-' . uniqid());
        $destination = storage_path('app/copy-destination-' . uniqid());
        File::ensureDirectoryExists($source);
        File::ensureDirectoryExists($destination . DIRECTORY_SEPARATOR . 'file.txt');
        file_put_contents($source . DIRECTORY_SEPARATOR . 'file.txt', 'content');
        $this->pathsToDelete[] = $source;
        $this->pathsToDelete[] = $destination;

        $this->expectException(\ErrorException::class);
        $this->expectExceptionMessage('cannot be a directory');

        $this->invokeBackupPrivate('copyDirectoryContents', $source, $destination);
    }

    private function makeUploadedZip(string $originalName, array $entries): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'backup-zip-');

        $zip = new ZipArchive();
        $zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        foreach ($entries as $name => $content) {
            $zip->addFromString($name, $content);
        }

        $zip->close();

        return new UploadedFile($path, $originalName, 'application/zip', null, true);
    }

    private function makePhysicalBackupZip(string $fileName, array $entries): string
    {
        $path = $this->backupDirectory() . DIRECTORY_SEPARATOR . $fileName;
        $this->pathsToDelete[] = $path;

        $zip = new ZipArchive();
        $zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        foreach ($entries as $name => $content) {
            if ($content === null) {
                $zip->addEmptyDir($name);
                continue;
            }

            $zip->addFromString($name, $content);
        }

        $zip->close();

        return $path;
    }

    private function backupDirectory(): string
    {
        $directory = storage_path('app/private/GNAIbackups');
        File::ensureDirectoryExists($directory);

        return $directory;
    }

    private function invokeBackupPrivate(string $method, mixed ...$arguments): mixed
    {
        $reflection = new ReflectionMethod($this->service, $method);
        $reflection->setAccessible(true);

        return $reflection->invoke($this->service, ...$arguments);
    }
}
