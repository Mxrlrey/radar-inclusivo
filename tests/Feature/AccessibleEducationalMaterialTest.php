<?php

namespace Tests\Feature;

use App\Enums\ConservationState;
use App\Enums\InspectionType;
use App\Enums\ResourceStatus;
use App\Models\AuditLog;
use App\Models\AccessibilityFeature;
use App\Models\AccessibleEducationalMaterial;
use App\Models\Inspection;
use App\Models\Deficiency;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccessibleEducationalMaterialTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['is_admin' => true]);
        $this->regularUser = User::factory()->create(['is_admin' => false]);
    }

    public function test_guest_cannot_access_materials_index()
    {
        // Act
        $response = $this->get(route('materiais-pedagogicos-acessiveis.index'));

        // Assert
        $response->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_access_materials_index()
    {
        // Act
        $response = $this->actingAs($this->regularUser)
            ->get(route('materiais-pedagogicos-acessiveis.index'));

        // Assert
        $response->assertForbidden();
    }

    public function test_admin_can_list_materials()
    {
        // Arrange
        AccessibleEducationalMaterial::factory()->count(2)->create();

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('materiais-pedagogicos-acessiveis.index'));

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.accessible-educational-materials.index');
        $response->assertViewHas('materials');
    }

    public function test_materials_index_returns_partial_when_ajax()
    {
        // Arrange
        AccessibleEducationalMaterial::factory()->count(2)->create();

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('materiais-pedagogicos-acessiveis.index'), [
                'X-Requested-With' => 'XMLHttpRequest',
            ]);

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.accessible-educational-materials.partials.table');
        $response->assertViewHas('materials');
    }

    public function test_admin_can_access_material_create_page()
    {
        // Arrange
        Deficiency::factory()->count(2)->create();
        AccessibilityFeature::factory()->count(2)->create(['is_active' => true]);

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('materiais-pedagogicos-acessiveis.criar'));

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.accessible-educational-materials.create');
        $response->assertViewHas('deficiencies');
        $response->assertViewHas('accessibilityFeatures');
        $response->assertViewHas('defaultInspection', InspectionType::INITIAL->value);
    }

    public function test_admin_can_store_a_material_with_valid_data()
    {
        // Arrange
        $deficiency = Deficiency::factory()->create();
        $feature = AccessibilityFeature::factory()->create();

        $data = [
            'name' => 'MPA de Matemática',
            'is_digital' => false,
            'is_loanable' => true,
            'asset_code' => 'PAT-5001',
            'quantity' => 4,
            'status' => ResourceStatus::AVAILABLE->value,
            'deficiencies' => [$deficiency->id],
            'accessibility_features' => [$feature->id],
            'conservation_state' => ConservationState::GOOD->value,
            'inspection_type' => InspectionType::INITIAL->value,
            'inspection_date' => now()->toDateString(),
        ];

        // Act
        $response = $this->actingAs($this->admin)
            ->post(route('materiais-pedagogicos-acessiveis.salvar'), $data);

        // Assert
        $response->assertRedirect(route('materiais-pedagogicos-acessiveis.index'));
        $this->assertDatabaseHas('accessible_educational_materials', [
            'name' => 'MPA de Matemática',
            'asset_code' => 'PAT-5001',
        ]);
    }

    public function test_it_fails_to_store_material_without_deficiencies()
    {
        // Arrange
        $data = [
            'name' => 'MPA sem Público',
            'is_digital' => false,
            'quantity' => 2,
            'status' => ResourceStatus::AVAILABLE->value,
            'deficiencies' => [],
            'conservation_state' => ConservationState::GOOD->value,
            'inspection_type' => InspectionType::INITIAL->value,
            'inspection_date' => now()->toDateString(),
        ];

        // Act
        $response = $this->actingAs($this->admin)
            ->from(route('materiais-pedagogicos-acessiveis.criar'))
            ->post(route('materiais-pedagogicos-acessiveis.salvar'), $data);

        // Assert
        $response->assertRedirect(route('materiais-pedagogicos-acessiveis.criar'));
        $response->assertSessionHasErrors('deficiencies');
    }

    public function test_admin_can_update_a_material()
    {
        // Arrange
        $deficiency = Deficiency::factory()->create();
        $feature = AccessibilityFeature::factory()->create();
        $material = AccessibleEducationalMaterial::factory()->physical()->available()->create([
            'quantity' => 3,
            'quantity_available' => 3,
        ]);

        $data = [
            'name' => 'MPA Atualizado',
            'is_digital' => false,
            'is_loanable' => true,
            'asset_code' => $material->asset_code,
            'quantity' => 7,
            'quantity_available' => 7,
            'status' => ResourceStatus::AVAILABLE->value,
            'deficiencies' => [$deficiency->id],
            'accessibility_features' => [$feature->id],
            'conservation_state' => ConservationState::REGULAR->value,
            'inspection_date' => now()->toDateString(),
        ];

        // Act
        $response = $this->actingAs($this->admin)
            ->put(route('materiais-pedagogicos-acessiveis.atualizar', $material), $data);

        // Assert
        $response->assertRedirect(route('materiais-pedagogicos-acessiveis.index'));
        $this->assertDatabaseHas('accessible_educational_materials', [
            'id' => $material->id,
            'name' => 'MPA Atualizado',
            'quantity' => 7,
        ]);
    }

    public function test_admin_can_view_a_material()
    {
        // Arrange
        $material = AccessibleEducationalMaterial::factory()->create();

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('materiais-pedagogicos-acessiveis.visualizar', $material));

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.accessible-educational-materials.show');
        $response->assertViewHas('material');
        $response->assertViewHas('deficiencies');
        $response->assertViewHas('features');
        $response->assertViewHas('inspections');
    }

    public function test_material_show_returns_inspections_partial_when_ajax()
    {
        // Arrange
        $material = AccessibleEducationalMaterial::factory()->create();
        Inspection::factory()->forAccessibleEducationalMaterial($material)->create();

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('materiais-pedagogicos-acessiveis.visualizar', $material), [
                'X-Requested-With' => 'XMLHttpRequest',
            ]);

        // Assert
        $response->assertOk();
        $this->assertNotSame('', $response->getContent());
    }

    public function test_admin_can_store_digital_material_without_quantity()
    {
        // Arrange
        $deficiency = Deficiency::factory()->create();

        $data = [
            'name' => 'MPA Digital',
            'is_digital' => true,
            'is_loanable' => false,
            'status' => ResourceStatus::AVAILABLE->value,
            'deficiencies' => [$deficiency->id],
            'conservation_state' => ConservationState::NOT_APPLICABLE->value,
            'inspection_type' => InspectionType::INITIAL->value,
            'inspection_date' => now()->toDateString(),
        ];

        // Act
        $response = $this->actingAs($this->admin)
            ->post(route('materiais-pedagogicos-acessiveis.salvar'), $data);

        // Assert
        $response->assertRedirect(route('materiais-pedagogicos-acessiveis.index'));
        $this->assertDatabaseHas('accessible_educational_materials', [
            'name' => 'MPA Digital',
            'quantity' => null,
        ]);
    }

    public function test_admin_can_access_material_edit_page()
    {
        // Arrange
        Deficiency::factory()->count(2)->create();
        AccessibilityFeature::factory()->count(2)->create(['is_active' => true]);
        $material = AccessibleEducationalMaterial::factory()->create();

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('materiais-pedagogicos-acessiveis.editar', $material));

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.accessible-educational-materials.edit');
        $response->assertViewHas('material');
        $response->assertViewHas('activeLoans');
        $response->assertViewHas('defaultInspection', InspectionType::PERIODIC->value);
    }

    public function test_admin_can_delete_a_material()
    {
        // Arrange
        $material = AccessibleEducationalMaterial::factory()->create();

        // Act
        $response = $this->actingAs($this->admin)
            ->delete(route('materiais-pedagogicos-acessiveis.excluir', $material));

        // Assert
        $response->assertRedirect(route('materiais-pedagogicos-acessiveis.index'));
        $this->assertSoftDeleted('accessible_educational_materials', [
            'id' => $material->id,
        ]);
    }

    public function test_admin_can_generate_material_pdf()
    {
        // Arrange
        $material = AccessibleEducationalMaterial::factory()->create(['name' => 'Material PDF']);

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('materiais-pedagogicos-acessiveis.pdf', $material));

        // Assert
        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_admin_can_view_a_material_inspection()
    {
        // Arrange
        $material = AccessibleEducationalMaterial::factory()->create();
        $inspection = Inspection::factory()
            ->forAccessibleEducationalMaterial($material)
            ->create();

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('materiais-pedagogicos-acessiveis.inspecao.visualizar', [$material, $inspection]));

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.accessible-educational-materials.inspections.show');
        $response->assertViewHas('inspection', $inspection);
    }

    public function test_it_blocks_access_to_an_inspection_of_another_material()
    {
        // Arrange
        $material = AccessibleEducationalMaterial::factory()->create();
        $otherMaterial = AccessibleEducationalMaterial::factory()->create();
        $inspection = Inspection::factory()
            ->forAccessibleEducationalMaterial($otherMaterial)
            ->create();

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('materiais-pedagogicos-acessiveis.inspecao.visualizar', [$material, $inspection]));

        // Assert
        $response->assertForbidden();
    }

    public function test_guest_cannot_access_material_logs()
    {
        $material = AccessibleEducationalMaterial::factory()->create();

        $response = $this->get(route('materiais-pedagogicos-acessiveis.registros', $material));

        $response->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_access_material_logs()
    {
        $material = AccessibleEducationalMaterial::factory()->create();

        $response = $this->actingAs($this->regularUser)
            ->get(route('materiais-pedagogicos-acessiveis.registros', $material));

        $response->assertForbidden();
    }

    public function test_admin_can_view_material_logs()
    {
        $material = AccessibleEducationalMaterial::factory()->create();

        AuditLog::create([
            'user_id' => $this->admin->id,
            'action' => 'updated',
            'auditable_type' => $material->getMorphClass(),
            'auditable_id' => $material->id,
            'old_values' => ['name' => 'Anterior'],
            'new_values' => ['name' => 'Atual'],
            'ip_address' => '127.0.0.1',
            'user_agent' => 'PHPUnit',
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('materiais-pedagogicos-acessiveis.registros', $material));

        $response->assertOk();
        $response->assertViewIs('pages.accessible-educational-materials.logs.logs');
        $response->assertViewHas('material', $material);
        $response->assertViewHas('logs');
    }
}
