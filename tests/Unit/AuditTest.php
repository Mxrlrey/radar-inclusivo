<?php

namespace Tests\Unit;

use App\Audit\AuditLogger;
use App\Audit\Formatters\AccessibleEducationalMaterialFormatter;
use App\Audit\Formatters\AssistiveTechnologyFormatter;
use App\Enums\ConservationState;
use App\Enums\ResourceStatus;
use App\Models\AccessibilityFeature;
use App\Models\AuditLog;
use App\Models\Deficiency;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Model;
use Tests\TestCase;

class AuditTest extends TestCase
{
    use RefreshDatabase;

    public function test_accessible_educational_material_formatter_formats_known_fields(): void
    {
        $formatter = new AccessibleEducationalMaterialFormatter();

        $this->assertSame('Digital', $formatter->format('is_digital', true));
        $this->assertSame('Físico', $formatter->format('is_digital', false));
        $this->assertSame('Ativo', $formatter->format('is_active', 1));
        $this->assertSame('Inativo', $formatter->format('is_active', 0));
        $this->assertSame('Sim', $formatter->format('is_loanable', true));
        $this->assertSame('Não', $formatter->format('is_loanable', false));
        $this->assertSame(ResourceStatus::AVAILABLE->label(), $formatter->format('status', ResourceStatus::AVAILABLE->value));
        $this->assertSame(ConservationState::GOOD->label(), $formatter->format('conservation_state', ConservationState::GOOD->value));
    }

    public function test_assistive_technology_formatter_formats_known_fields(): void
    {
        $formatter = new AssistiveTechnologyFormatter();

        $this->assertSame('Digital', $formatter->format('is_digital', true));
        $this->assertSame('Físico', $formatter->format('is_digital', false));
        $this->assertSame('Ativo', $formatter->format('is_active', true));
        $this->assertSame('Inativo', $formatter->format('is_active', false));
        $this->assertSame('Sim', $formatter->format('is_loanable', 1));
        $this->assertSame('Não', $formatter->format('is_loanable', 0));
        $this->assertSame(ResourceStatus::DAMAGED->label(), $formatter->format('status', ResourceStatus::DAMAGED->value));
        $this->assertSame(ConservationState::BAD->label(), $formatter->format('conservation_state', ConservationState::BAD->value));
    }

    public function test_formatter_returns_null_for_unknown_fields_and_invalid_relation_values(): void
    {
        $materialFormatter = new AccessibleEducationalMaterialFormatter();
        $technologyFormatter = new AssistiveTechnologyFormatter();

        $this->assertNull($materialFormatter->format('unknown_field', 'value'));
        $this->assertNull($materialFormatter->format('deficiencies', 'not-json'));
        $this->assertNull($materialFormatter->format('accessibility_features', 'not-json'));
        $this->assertNull($technologyFormatter->format('deficiencies', 'not-json'));
    }

    public function test_formatters_resolve_relation_names_and_empty_fallbacks(): void
    {
        $deficiency = Deficiency::factory()->create(['name' => 'Baixa visão']);
        $feature = AccessibilityFeature::factory()->create(['name' => 'Audiodescrição']);

        $materialFormatter = new AccessibleEducationalMaterialFormatter();
        $technologyFormatter = new AssistiveTechnologyFormatter();

        $this->assertSame('Baixa visão', $materialFormatter->format('deficiencies', [$deficiency->id]));
        $this->assertSame('Audiodescrição', $materialFormatter->format('accessibility_features', [$feature->id]));
        $this->assertSame('Baixa visão', $technologyFormatter->format('deficiencies', [$deficiency->id]));
        $this->assertSame('Nenhuma', $materialFormatter->format('deficiencies', []));
        $this->assertSame('Nenhum', $materialFormatter->format('accessibility_features', []));
        $this->assertSame('Nenhuma', $technologyFormatter->format('deficiencies', []));
    }

    public function test_formatter_decodes_json_before_calling_callable_formatters(): void
    {
        $formatter = new class extends \App\Audit\Formatters\AuditFormatter {
            protected function formatters(): array
            {
                return [
                    'payload' => fn($value) => is_array($value)
                        ? implode('|', $value)
                        : 'not-array',
                ];
            }
        };

        $this->assertSame('alpha|beta', $formatter->format('payload', '["alpha","beta"]'));
        $this->assertSame('not-array', $formatter->format('payload', 'plain text'));
    }

    public function test_formatter_returns_null_when_configured_formatter_is_not_supported(): void
    {
        $formatter = new class extends \App\Audit\Formatters\AuditFormatter {
            protected function formatters(): array
            {
                return ['payload' => 123];
            }
        };

        $this->assertNull($formatter->format('payload', 'value'));
    }

    public function test_relation_logger_ignores_equal_ids_after_sorting(): void
    {
        $logger = new AuditLogger();
        $model = new class extends Model {
            protected $table = 'fake_audit_models';
        };
        $model->forceFill(['id' => 10]);
        $model->exists = true;

        $logger->logRelationIfChanged($model, 'deficiencies', [3, 1, 2], [2, 3, 1]);

        $this->assertTrue(true);
    }

    public function test_relation_logger_swallows_audit_write_failures(): void
    {
        $model = new class extends Model {
            protected $table = 'fake_audit_models';
        };
        $model->forceFill(['id' => 30]);
        $model->exists = true;

        (new AuditLogger())->logRelationIfChanged($model, 'deficiencies', [1], [2]);

        $this->assertTrue(true);
    }

    public function test_relation_logger_writes_audit_when_ids_change(): void
    {
        $model = Student::factory()->create();

        (new AuditLogger())->logRelationIfChanged($model, 'materials', [1], [2]);

        $this->assertDatabaseHas('audit_logs', [
            'auditable_type' => $model->getMorphClass(),
            'auditable_id' => $model->id,
            'action' => 'updated',
        ]);

        $log = AuditLog::query()->latest('id')->first();

        $this->assertSame(['materials' => [1]], $log->old_values);
        $this->assertSame(['materials' => [2]], $log->new_values);
    }
}
