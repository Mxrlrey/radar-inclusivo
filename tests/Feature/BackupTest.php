<?php

namespace Tests\Feature;

use App\Models\Backup;
use App\Models\User;
use App\Services\BackupService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BackupTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Arrange
        $this->admin = User::factory()->create(['is_admin' => true]);
    }

    public function test_guest_cannot_access_backups_index()
    {
        // Act
        $response = $this->get(route('copias-seguranca.index'));

        // Assert
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_list_backups()
    {
        // Arrange
        $this->mock(BackupService::class, function ($mock) {
            $mock->shouldReceive('sync')->once()->andReturnTrue();
        });

        Backup::create([
            'file_name' => 'backup-001.zip',
            'file_path' => 'GNAIbackups/backup-001.zip',
            'size' => '1 KB',
            'status' => 'success',
            'user_id' => $this->admin->id,
        ]);

        // Act
        $response = $this->actingAs($this->admin)->get(route('copias-seguranca.index'));

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.backup.index');
        $response->assertViewHas('backups');
        $response->assertViewHas('users');
    }

    public function test_backups_index_returns_partial_when_ajax()
    {
        // Arrange
        $this->mock(BackupService::class, function ($mock) {
            $mock->shouldReceive('sync')->once()->andReturnTrue();
        });

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('copias-seguranca.index'), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.backup.partials.table');
    }

    public function test_authenticated_user_can_generate_backup()
    {
        // Arrange
        $this->mock(BackupService::class, function ($mock) {
            $mock->shouldReceive('generate')->once()->andReturn(new Backup());
        });

        // Act
        $response = $this->actingAs($this->admin)
            ->post(route('copias-seguranca.salvar'));

        // Assert
        $response->assertRedirect(route('copias-seguranca.index'));
        $response->assertSessionHas('success', 'Backup realizado com sucesso!');
    }

    public function test_authenticated_user_can_view_backup()
    {
        // Arrange
        $backup = Backup::create([
            'file_name' => 'backup-show.zip',
            'file_path' => 'GNAIbackups/backup-show.zip',
            'size' => '1 KB',
            'status' => 'success',
            'user_id' => $this->admin->id,
        ]);

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('copias-seguranca.visualizar', $backup->id));

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.backup.show');
        $response->assertViewHas('backup');
    }

    public function test_authenticated_user_can_download_backup_file()
    {
        // Arrange
        Storage::fake('local');
        Storage::disk('local')->put('GNAIbackups/backup-download.zip', 'conteudo');

        $backup = Backup::create([
            'file_name' => 'backup-download.zip',
            'file_path' => 'GNAIbackups/backup-download.zip',
            'size' => '8 B',
            'status' => 'success',
            'user_id' => $this->admin->id,
        ]);

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('copias-seguranca.baixar', $backup->id));

        // Assert
        $response->assertOk();
        $this->assertSame('attachment; filename=backup-download.zip', $response->headers->get('content-disposition'));
    }

    public function test_download_returns_error_when_backup_file_does_not_exist()
    {
        // Arrange
        Storage::fake('local');

        $backup = Backup::create([
            'file_name' => 'backup-missing.zip',
            'file_path' => 'GNAIbackups/backup-missing.zip',
            'size' => '1 KB',
            'status' => 'success',
            'user_id' => $this->admin->id,
        ]);

        // Act
        $response = $this->actingAs($this->admin)
            ->from(route('copias-seguranca.visualizar', $backup->id))
            ->get(route('copias-seguranca.baixar', $backup->id));

        // Assert
        $response->assertRedirect(route('copias-seguranca.visualizar', $backup->id));
        $response->assertSessionHas('error', 'O arquivo físico não existe no servidor.');
    }

    public function test_upload_requires_backup_file()
    {
        // Act
        $response = $this->actingAs($this->admin)
            ->from(route('copias-seguranca.index'))
            ->post(route('copias-seguranca.enviar'));

        // Assert
        $response->assertRedirect(route('copias-seguranca.index'));
        $response->assertSessionHas('error');
    }

    public function test_authenticated_user_can_upload_backup_file()
    {
        // Arrange
        $this->mock(BackupService::class, function ($mock) {
            $mock->shouldReceive('storeUploadedFile')->once()->andReturn(new Backup());
        });

        $file = UploadedFile::fake()->create('backup-upload.zip', 10, 'application/zip');

        // Act
        $response = $this->actingAs($this->admin)
            ->from(route('copias-seguranca.index'))
            ->post(route('copias-seguranca.enviar'), ['backup_file' => $file]);

        // Assert
        $response->assertRedirect(route('copias-seguranca.index'));
        $response->assertSessionHas('success', 'Backup importado com sucesso!');
    }

    public function test_authenticated_user_can_delete_backup()
    {
        // Arrange
        $backup = Backup::create([
            'file_name' => 'backup-delete.zip',
            'file_path' => 'GNAIbackups/backup-delete.zip',
            'size' => '1 KB',
            'status' => 'success',
            'user_id' => $this->admin->id,
        ]);

        $this->mock(BackupService::class, function ($mock) use ($backup) {
            $mock->shouldReceive('delete')->once()->with($backup->id)->andReturnTrue();
        });

        // Act
        $response = $this->actingAs($this->admin)
            ->delete(route('copias-seguranca.excluir', $backup->id));

        // Assert
        $response->assertRedirect(route('copias-seguranca.index'));
        $response->assertSessionHas('success', 'Registro e arquivo removidos permanentemente.');
    }

    public function test_authenticated_user_can_restore_backup()
    {
        // Arrange
        $backup = Backup::create([
            'file_name' => 'backup-restore.zip',
            'file_path' => 'GNAIbackups/backup-restore.zip',
            'size' => '1 KB',
            'status' => 'success',
            'user_id' => $this->admin->id,
        ]);

        $this->mock(BackupService::class, function ($mock) use ($backup) {
            $mock->shouldReceive('restore')->once()->with($backup->id)->andReturnTrue();
        });

        // Act
        $response = $this->actingAs($this->admin)
            ->post(route('copias-seguranca.restaurar', $backup->id));

        // Assert
        $response->assertRedirect(route('copias-seguranca.index'));
        $response->assertSessionHas('success', 'Sistema restaurado com sucesso para a versão selecionada!');
    }
}
