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
use App\Models\Traits\Reportable;
use App\Services\ReportService;
use BackedEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use InvalidArgumentException;
use Mockery;
use ReflectionMethod;
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

    public function test_it_runs_report_with_non_like_filters(): void
    {
        $matching = $this->createStudent([
            'name' => 'Filtro Exato',
            'email' => 'filtro.exato@example.com',
            'registration' => 'MAT-FILTER-001',
        ]);
        $this->createStudent([
            'email' => 'outro.filtro@example.com',
            'registration' => 'MAT-FILTER-002',
        ]);

        $result = $this->service->run([
            'model' => Student::class,
            'columns' => ['id', 'person.email'],
            'filters' => [
                ['column' => 'id', 'operator' => '=', 'value' => $matching->id],
                ['column' => 'registration', 'operator' => 'like', 'value' => 'MAT-FILTER'],
                ['column' => 'person.email', 'operator' => '=', 'value' => 'filtro.exato@example.com'],
            ],
        ]);

        $this->assertSame(1, $result['total']);
        $this->assertSame('filtro.exato@example.com', $result['rows'][0]['person__email']);
    }

    public function test_it_skips_empty_filters_and_handles_polymorphic_non_like_filters_and_mismatches(): void
    {
        $category = BarrierCategory::factory()->create();
        $barrier = Barrier::factory()->create([
            'name' => 'Porta estreita',
            'barrier_category_id' => $category->id,
        ]);
        Inspection::factory()->forBarrier($barrier)->create([
            'status' => BarrierStatus::IDENTIFIED->value,
            'type' => InspectionType::INITIAL->value,
            'inspection_date' => '2024-04-10',
        ]);

        $result = $this->service->run([
            'model' => Inspection::class,
            'columns' => ['barrier.name', 'accessibleEducationalMaterial.name'],
            'filters' => [
                ['column' => null, 'operator' => '=', 'value' => 'ignored'],
                ['column' => 'description', 'operator' => '=', 'value' => ''],
                ['column' => 'barrier.id', 'operator' => '=', 'value' => $barrier->id],
            ],
        ]);

        $this->assertSame(1, $result['total']);
        $this->assertSame('Porta estreita', $result['rows'][0]['barrier__name']);
        $this->assertNull($result['rows'][0]['accessibleEducationalMaterial__name']);
    }

    public function test_meta_embeds_allowed_relations_and_ignores_throwing_relation_methods(): void
    {
        $this->createReportFixtureTables();

        $meta = $this->service->meta(ReportParentModel::class);

        $this->assertArrayHasKey('child.name', $meta['columns']);
        $this->assertContains('child', collect($meta['relations'])->pluck('name')->all());
    }

    public function test_meta_includes_extra_pivot_columns_for_many_to_many_relations(): void
    {
        $this->createReportFixtureTables();

        $meta = $this->service->meta(ReportPivotParentModel::class);
        $children = collect($meta['relations'])->firstWhere('name', 'children');

        $this->assertSame(['role' => 'Role'], $children['pivot']['columns']);
    }

    public function test_report_service_private_helpers_cover_remaining_branches(): void
    {
        $this->createReportFixtureTables();

        app('translator')->addLines([
            'database.columns.report_custom_blacklists.name' => 'Nome Traduzido',
            'database.columns.report_pivot_child_parent.role' => 'Papel',
        ], app()->getLocale());

        $this->assertSame([], $this->invokeReportPrivate('reportRelationsFor', ReportNoRelationsFixture::class));
        $this->assertNull($this->invokeReportPrivate('reportRelationMeta', Student::class, 'missing'));

        $this->assertArrayHasKey('email', $this->invokeReportPrivate(
            'translatedColumnsForRelatedModel',
            User::class,
            'users'
        ));
        $this->assertSame(['name' => 'Nome Traduzido'], $this->invokeReportPrivate(
            'translatedColumnsForRelatedModel',
            ReportCustomBlacklistModel::class,
            'report_custom_blacklists'
        ));

        $relationsToLoad = $this->invokeReportPrivate('relationsToLoadFor', Inspection::class, [
            'id',
            'barrier.name',
            'status',
        ], [
            ['column' => 'barrier.name'],
            ['column' => null],
        ]);
        $this->assertContains('inspectable', $relationsToLoad);

        $selectedPoly = $this->invokeReportPrivate('selectedPolymorphicRelationsFor', Inspection::class, [
            'barrier.name',
            'id',
        ], []);
        $this->assertSame(Barrier::class, $selectedPoly[0]['class']);

        $emptyQuery = Mockery::mock();
        $this->invokeReportPrivate('applyPolymorphicSelectionConstraints', $emptyQuery, []);

        $singleQuery = Mockery::mock();
        $singleQuery->shouldReceive('where')->once();
        $this->invokeReportPrivate('applyPolymorphicSelectionConstraints', $singleQuery, $selectedPoly);

        $multiQuery = Mockery::mock();
        $multiQuery->shouldReceive('whereIn')->once();
        $this->invokeReportPrivate('applyPolymorphicSelectionConstraints', $multiQuery, [
            $selectedPoly[0],
            [
                'type_column' => $selectedPoly[0]['type_column'],
                'class' => \App\Models\AccessibleEducationalMaterial::class,
            ],
        ]);

        $this->assertSame('um, dois', $this->invokeReportPrivate('normalizeValue', collect(['um', 'dois', 'um', null])));
        $this->assertSame('plain', $this->invokeReportPrivate('normalizeValue', ReportPlainEnum::PLAIN));
        $this->assertSame('14/05/2026 12:45', $this->invokeReportPrivate('normalizeValue', Carbon::parse('2026-05-14 12:45:00')));
        $this->assertSame(json_encode(['name' => 'obj']), $this->invokeReportPrivate('normalizeValue', (object) ['name' => 'obj']));

        $pivotRelation = Mockery::mock(\Illuminate\Database\Eloquent\Relations\BelongsToMany::class);
        $pivotRelation->shouldReceive('getTable')->andReturn('report_pivot_child_parent');
        $pivotRelation->shouldReceive('getPivotColumns')->andThrow(new \RuntimeException('pivot unavailable'));
        $pivotRelation->shouldReceive('getForeignPivotKeyName')->andReturn('report_pivot_parent_id');
        $pivotRelation->shouldReceive('getRelatedPivotKeyName')->andReturn('report_pivot_child_id');

        $this->assertSame(['role' => 'Papel'], $this->invokeReportPrivate('resolvePivotColumns', $pivotRelation));
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

    private function invokeReportPrivate(string $method, mixed ...$arguments): mixed
    {
        $reflection = new ReflectionMethod($this->service, $method);
        $reflection->setAccessible(true);

        return $reflection->invoke($this->service, ...$arguments);
    }

    private function createReportFixtureTables(): void
    {
        foreach (['report_custom_blacklists', 'report_pivot_child_parent', 'report_pivot_children', 'report_pivot_parents', 'report_children', 'report_parents'] as $table) {
            Schema::dropIfExists($table);
        }

        Schema::create('report_parents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->nullable();
            $table->string('name')->nullable();
        });

        Schema::create('report_children', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
        });

        Schema::create('report_pivot_parents', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
        });

        Schema::create('report_pivot_children', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
        });

        Schema::create('report_pivot_child_parent', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_pivot_parent_id');
            $table->foreignId('report_pivot_child_id');
            $table->string('role')->nullable();
            $table->timestamps();
        });

        Schema::create('report_custom_blacklists', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('secret')->nullable();
        });
    }
}

enum ReportPlainEnum: string
{
    case PLAIN = 'plain';
}

class ReportNoRelationsFixture
{
}

class ReportCustomBlacklistModel
{
    public static function getBlacklist(): array
    {
        return ['id', 'secret'];
    }
}

class ReportParentModel extends Model
{
    use Reportable;

    protected $table = 'report_parents';

    public static function getReportLabel(): string
    {
        return 'Report Parent';
    }

    public static function getReportColumns(): array
    {
        return [];
    }

    public static function getReportColumnLabels(): array
    {
        return ['id' => 'ID'];
    }

    public static function getEmbeddedRelations(): array
    {
        return ['child'];
    }

    public function child()
    {
        return $this->hasOne(ReportChildModel::class, 'id', 'child_id');
    }

    public function brokenRelation()
    {
        throw new \RuntimeException('broken relation');
    }
}

class ReportChildModel extends Model
{
    use Reportable;

    protected $table = 'report_children';

    public static function getReportLabel(): string
    {
        return 'Report Child';
    }

    public static function getReportColumns(): array
    {
        return ['id', 'name'];
    }

    public static function getReportColumnLabels(): array
    {
        return ['id' => 'ID', 'name' => 'Nome'];
    }
}

class ReportPivotParentModel extends Model
{
    use Reportable;

    protected $table = 'report_pivot_parents';

    public static function getReportLabel(): string
    {
        return 'Report Pivot Parent';
    }

    public static function getReportColumns(): array
    {
        return ['id', 'name'];
    }

    public static function getReportColumnLabels(): array
    {
        return ['id' => 'ID', 'name' => 'Nome'];
    }

    public function children()
    {
        return $this->belongsToMany(
            ReportPivotChildModel::class,
            'report_pivot_child_parent',
            'report_pivot_parent_id',
            'report_pivot_child_id'
        )->withPivot('role');
    }
}

class ReportPivotChildModel extends Model
{
    use Reportable;

    protected $table = 'report_pivot_children';

    public static function getReportLabel(): string
    {
        return 'Report Pivot Child';
    }

    public static function getReportColumns(): array
    {
        return ['id', 'name'];
    }

    public static function getReportColumnLabels(): array
    {
        return ['id' => 'ID', 'name' => 'Nome'];
    }
}
