<?php

namespace Tests\Unit;

use App\Enums\BarrierStatus;
use App\Enums\Gender;
use App\Enums\InspectionType;
use App\Models\Barrier;
use App\Models\BarrierCategory;
use App\Models\Inspection;
use App\Models\Person;
use App\Models\Professional;
use App\Models\Student;
use App\Models\User;
use App\Services\ReportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class ReportServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ReportService $service;

    protected function setUp(): void
    {
        parent::setUp();

        // Arrange
        $this->service = app(ReportService::class);
    }

    public function test_it_lists_available_reportable_entities()
    {
        // Act
        $entities = $this->service->availableEntities();

        // Assert
        $classes = collect($entities)->pluck('class');

        $this->assertContains(Student::class, $classes);
        $this->assertContains(Professional::class, $classes);
        $this->assertContains(Inspection::class, $classes);
    }

    public function test_it_returns_metadata_for_reportable_model()
    {
        // Act
        $meta = $this->service->meta(Student::class);

        // Assert
        $this->assertSame(Student::class, $meta['class']);
        $this->assertSame('Alunos', $meta['label']);
        $this->assertSame('students', $meta['table']);
        $this->assertArrayHasKey('person.name', $meta['columns']);
        $this->assertContains('person', collect($meta['relations'])->pluck('name')->all());
    }

    public function test_it_returns_metadata_for_polymorphic_report_relations()
    {
        // Act
        $meta = $this->service->meta(Inspection::class);

        // Assert
        $relations = collect($meta['relations'])->keyBy('name');

        $this->assertTrue($relations->has('barrier'));
        $this->assertSame('MorphTo', $relations['barrier']['type']);
        $this->assertSame(Barrier::class, $relations['barrier']['related_class']);
    }

    public function test_it_rejects_invalid_model()
    {
        // Assert
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Modelo inválido');

        // Act
        $this->service->meta('App\\Models\\DoesNotExist');
    }

    public function test_it_rejects_non_reportable_model()
    {
        // Assert
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Modelo não reportável');

        // Act
        $this->service->run(['model' => User::class]);
    }

    public function test_it_runs_report_with_simple_and_related_columns()
    {
        // Arrange
        $matching = $this->createStudent([
            'name' => 'Maria Relatorio',
            'email' => 'maria.relatorio@example.com',
            'document' => '52998224725',
            'registration' => 'MAT-REL-001',
            'is_active' => true,
            'entry_date' => '2024-02-01',
        ]);

        $this->createStudent([
            'name' => 'Joao Outro',
            'email' => 'joao.outro@example.com',
            'document' => '15350946056',
            'registration' => 'MAT-REL-002',
        ]);

        $payload = [
            'model' => Student::class,
            'columns' => ['id', 'person.name', 'registration', 'is_active', 'entry_date'],
            'filters' => [
                ['column' => 'person.name', 'operator' => 'like', 'value' => 'Maria'],
            ],
        ];

        // Act
        $result = $this->service->run($payload);

        // Assert
        $this->assertSame(1, $result['total']);
        $this->assertSame($matching->id, $result['rows'][0]['id']);
        $this->assertSame('Maria Relatorio', $result['rows'][0]['person__name']);
        $this->assertSame('MAT-REL-001', $result['rows'][0]['registration']);
        $this->assertSame('Sim', $result['rows'][0]['is_active']);
        $this->assertSame('01/02/2024', $result['rows'][0]['entry_date']);
    }

    public function test_it_runs_report_with_many_to_many_relation_collection()
    {
        // Arrange
        $barrier = Barrier::factory()->create(['name' => 'Barreira com públicos']);
        $barrier->deficiencies()->attach([
            \App\Models\Deficiency::factory()->create(['name' => 'Deficiência Visual'])->id => [
                'created_at' => now(),
                'updated_at' => now(),
            ],
            \App\Models\Deficiency::factory()->create(['name' => 'Deficiência Auditiva'])->id => [
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $payload = [
            'model' => Barrier::class,
            'columns' => ['name', 'deficiencies.name'],
            'filters' => [],
        ];

        // Act
        $result = $this->service->run($payload);

        // Assert
        $this->assertSame(1, $result['total']);
        $this->assertSame('Barreira com públicos', $result['rows'][0]['name']);
        $this->assertStringContainsString('Deficiência Visual', $result['rows'][0]['deficiencies__name']);
        $this->assertStringContainsString('Deficiência Auditiva', $result['rows'][0]['deficiencies__name']);
    }

    public function test_it_runs_report_with_polymorphic_relation_selection()
    {
        // Arrange
        $category = BarrierCategory::factory()->create();
        $barrier = Barrier::factory()->create([
            'name' => 'Rampa bloqueada',
            'barrier_category_id' => $category->id,
        ]);

        Inspection::factory()->forBarrier($barrier)->create([
            'status' => BarrierStatus::IDENTIFIED->value,
            'type' => InspectionType::INITIAL->value,
            'inspection_date' => '2024-03-05',
        ]);

        $payload = [
            'model' => Inspection::class,
            'columns' => ['barrier.name', 'status', 'inspection_date'],
            'filters' => [
                ['column' => 'barrier.name', 'operator' => 'like', 'value' => 'Rampa'],
            ],
        ];

        // Act
        $result = $this->service->run($payload);

        // Assert
        $this->assertSame(1, $result['total']);
        $this->assertSame('Rampa bloqueada', $result['rows'][0]['barrier__name']);
        $this->assertSame('Identificada', $result['rows'][0]['status']);
        $this->assertSame('05/03/2024', $result['rows'][0]['inspection_date']);
    }

    public function test_export_data_applies_requested_limit()
    {
        // Arrange
        $this->createStudent(['registration' => 'MAT-LIMIT-001']);
        $this->createStudent(['registration' => 'MAT-LIMIT-002']);

        // Act
        $result = $this->service->exportData([
            'model' => Student::class,
            'columns' => ['registration'],
        ], 1);

        // Assert
        $this->assertSame(1, $result['total']);
        $this->assertCount(1, $result['rows']);
    }

    public function test_it_normalizes_html_strings_in_report_output()
    {
        // Arrange
        \App\Models\Deficiency::factory()->create([
            'name' => 'Deficiência <strong>Visual</strong>',
            'description' => "Texto&nbsp;com\n espaços",
        ]);

        // Act
        $result = $this->service->run([
            'model' => \App\Models\Deficiency::class,
            'columns' => ['name', 'description'],
        ]);

        // Assert
        $this->assertSame('Deficiência Visual', $result['rows'][0]['name']);
        $this->assertSame('Texto com espaços', $result['rows'][0]['description']);
    }

    private function createStudent(array $overrides = []): Student
    {
        $person = Person::factory()->create([
            'name' => $overrides['name'] ?? 'Aluno Relatorio',
            'email' => $overrides['email'] ?? fake()->unique()->safeEmail(),
            'document' => $overrides['document'] ?? fake()->unique()->numerify('###########'),
            'gender' => $overrides['gender'] ?? Gender::NOT_SPECIFIED->value,
        ]);

        return Student::factory()->create([
            'person_id' => $person->id,
            'registration' => $overrides['registration'] ?? fake()->unique()->numerify('MAT-#####'),
            'entry_date' => $overrides['entry_date'] ?? '2024-01-01',
            'is_active' => $overrides['is_active'] ?? true,
        ]);
    }
}
