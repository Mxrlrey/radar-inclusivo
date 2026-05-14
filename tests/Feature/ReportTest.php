<?php

namespace Tests\Feature;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Student;
use App\Models\User;
use App\Services\ReportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Arrange
        $this->user = User::factory()->create(['is_admin' => true]);
    }

    public function test_guest_cannot_access_reports_builder()
    {
        // Act
        $response = $this->get(route('relatorios.index'));

        // Assert
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_reports_builder()
    {
        // Act
        $response = $this->actingAs($this->user)->get(route('relatorios.index'));

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.reports.builder');
    }

    public function test_authenticated_user_can_access_reports_create_alias()
    {
        // Act
        $response = $this->actingAs($this->user)->get(route('relatorios.criar'));

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.reports.builder');
    }

    public function test_available_entities_returns_json()
    {
        // Arrange
        $this->mock(ReportService::class, function ($mock) {
            $mock->shouldReceive('availableEntities')
                ->once()
                ->andReturn([
                    ['class' => Student::class, 'label' => 'Estudantes'],
                ]);
        });

        // Act
        $response = $this->actingAs($this->user)->getJson(route('relatorios.dados'));

        // Assert
        $response->assertOk();
        $response->assertJsonFragment(['label' => 'Estudantes']);
    }

    public function test_report_meta_returns_json()
    {
        // Arrange
        $this->mock(ReportService::class, function ($mock) {
            $mock->shouldReceive('meta')
                ->once()
                ->with(Student::class)
                ->andReturn([
                    'class' => Student::class,
                    'label' => 'Estudantes',
                    'columns' => ['id' => 'ID'],
                    'relations' => [],
                ]);
        });

        // Act
        $response = $this->actingAs($this->user)
            ->getJson(route('relatorios.meta', ['model' => Student::class]));

        // Assert
        $response->assertOk();
        $response->assertJsonFragment(['label' => 'Estudantes']);
    }

    public function test_report_meta_returns_bad_request_when_service_fails()
    {
        // Arrange
        $this->mock(ReportService::class, function ($mock) {
            $mock->shouldReceive('meta')
                ->once()
                ->andThrow(new \InvalidArgumentException('Modelo inválido.'));
        });

        // Act
        $response = $this->actingAs($this->user)
            ->getJson(route('relatorios.meta', ['model' => 'Invalid']));

        // Assert
        $response->assertStatus(400);
        $response->assertJson(['error' => 'Modelo inválido.']);
    }

    public function test_report_run_returns_json()
    {
        // Arrange
        $payload = [
            'model' => Student::class,
            'columns' => ['id'],
        ];

        $this->mock(ReportService::class, function ($mock) {
            $mock->shouldReceive('run')
                ->once()
                ->andReturn([
                    'headers' => ['ID'],
                    'rows' => [['id' => 1]],
                ]);
        });

        // Act
        $response = $this->actingAs($this->user)
            ->postJson(route('relatorios.gerar'), $payload);

        // Assert
        $response->assertOk();
        $response->assertJsonFragment(['headers' => ['ID']]);
    }

    public function test_report_run_returns_server_error_when_service_fails()
    {
        // Arrange
        $this->mock(ReportService::class, function ($mock) {
            $mock->shouldReceive('run')
                ->once()
                ->andThrow(new \RuntimeException('Falha no relatório.'));
        });

        // Act
        $response = $this->actingAs($this->user)
            ->postJson(route('relatorios.gerar'), ['model' => Student::class]);

        // Assert
        $response->assertStatus(500);
        $response->assertJsonFragment(['error' => 'Falha no relatório.']);
    }

    public function test_report_pdf_export_returns_error_when_payload_is_invalid()
    {
        // Act
        $response = $this->actingAs($this->user)
            ->post(route('relatorios.exportar.pdf'), ['payload' => '{invalid-json']);

        // Assert
        $response->assertStatus(500);
        $response->assertJsonStructure(['error']);
    }

    public function test_report_pdf_export_downloads_pdf_with_request_payload()
    {
        // Arrange
        $payload = [
            'model' => Student::class,
            'headers' => ['ID'],
            'columns' => ['id'],
        ];

        $this->mock(ReportService::class, function ($mock) {
            $mock->shouldReceive('exportData')
                ->once()
                ->with(Mockery::on(fn ($payload) => $payload['headers'] === ['ID']), 1000)
                ->andReturn(['rows' => [['id' => 1]]]);
        });

        $pdf = Mockery::mock(\Barryvdh\DomPDF\PDF::class);
        $pdf->shouldReceive('download')
            ->once()
            ->with('relatorio.pdf')
            ->andReturn(response('pdf-content', 200, ['content-type' => 'application/pdf']));

        Pdf::shouldReceive('loadView')
            ->once()
            ->with('pages.reports.pdf', Mockery::on(function ($data) {
                return $data['headers'] === ['ID'] && $data['data'] === [['id' => 1]];
            }))
            ->andReturn($pdf);

        // Act
        $response = $this->actingAs($this->user)
            ->post(route('relatorios.exportar.pdf'), $payload);

        // Assert
        $response->assertOk();
        $this->assertSame('pdf-content', $response->getContent());
    }
}
