<?php

namespace Tests\Unit;

use App\Models\AccessibilityFeature;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccessibilityFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Arrange
        $this->admin = User::factory()->create(['is_admin' => true]);
        $this->regularUser = User::factory()->create(['is_admin' => false]);
    }

    public function test_guest_cannot_access_index()
    {
        // Act
        $response = $this->get(route('recursos-de-acessibilidade.index'));

        // Assert
        $response->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_access_index()
    {
        // Act
        $response = $this->actingAs($this->regularUser)
            ->get(route('recursos-de-acessibilidade.index'));

        // Assert
        $response->assertForbidden();
    }

    public function test_admin_can_access_index()
    {
        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('recursos-de-acessibilidade.index'));

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.accessibility-features.index');
    }

    public function test_index_returns_partial_when_ajax()
    {
        // Arrange
        AccessibilityFeature::factory()->count(2)->create();

        // Act
        $response = $this->actingAs($this->admin)
            ->get(
                route('recursos-de-acessibilidade.index'),
                ['HTTP_X-Requested-With' => 'XMLHttpRequest']
            );

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.accessibility-features.partials.table');
    }

    public function test_guest_cannot_access_create()
    {
        // Act
        $response = $this->get(route('recursos-de-acessibilidade.criar'));

        // Assert
        $response->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_access_create()
    {
        // Act
        $response = $this->actingAs($this->regularUser)
            ->get(route('recursos-de-acessibilidade.criar'));

        // Assert
        $response->assertForbidden();
    }

    public function test_admin_can_access_create_page()
    {
        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('recursos-de-acessibilidade.criar'));

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.accessibility-features.create');
    }

    public function test_guest_cannot_access_edit()
    {
        // Arrange
        $feature = AccessibilityFeature::factory()->create();

        // Act
        $response = $this->get(route('recursos-de-acessibilidade.editar', $feature));

        // Assert
        $response->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_access_edit()
    {
        // Arrange
        $feature = AccessibilityFeature::factory()->create();

        // Act
        $response = $this->actingAs($this->regularUser)
            ->get(route('recursos-de-acessibilidade.editar', $feature));

        // Assert
        $response->assertForbidden();
    }

    public function test_admin_can_access_edit_page()
    {
        // Arrange
        $feature = AccessibilityFeature::factory()->create();

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('recursos-de-acessibilidade.editar', $feature));

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.accessibility-features.edit');
        $response->assertViewHas('accessibilityFeature', $feature);
    }

    public function test_guest_cannot_store_feature()
    {
        // Act
        $response = $this->post(
            route('recursos-de-acessibilidade.salvar'),
            ['name' => 'Teste']
        );

        // Assert
        $response->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_store_feature()
    {
        // Act
        $response = $this->actingAs($this->regularUser)
            ->post(route('recursos-de-acessibilidade.salvar'), [
                'name' => 'Teste'
            ]);

        // Assert
        $response->assertForbidden();
    }

    public function test_admin_can_store_feature()
    {
        // Arrange
        $data = [
            'name' => 'Audiodescrição',
            'description' => 'Recurso importante',
            'is_active' => true,
        ];

        // Act
        $response = $this->actingAs($this->admin)
            ->post(route('recursos-de-acessibilidade.salvar'), $data);

        // Assert
        $response->assertRedirect(route('recursos-de-acessibilidade.index'));
        $this->assertDatabaseHas('accessibility_features', ['name' => 'Audiodescrição']);
    }

    public function test_store_requires_unique_name()
    {
        // Arrange
        AccessibilityFeature::factory()->create(['name' => 'Libras']);

        // Act
        $response = $this->actingAs($this->admin)
            ->post(route('recursos-de-acessibilidade.salvar'), [
                'name' => 'Libras'
            ]);

        // Assert
        $response->assertSessionHasErrors('name');
    }

    public function test_guest_cannot_view_feature()
    {
        // Arrange
        $feature = AccessibilityFeature::factory()->create();

        // Act
        $response = $this->get(route('recursos-de-acessibilidade.visualizar', $feature));

        // Assert
        $response->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_view_feature()
    {
        // Arrange
        $feature = AccessibilityFeature::factory()->create();

        // Act
        $response = $this->actingAs($this->regularUser)
            ->get(route('recursos-de-acessibilidade.visualizar', $feature));

        // Assert
        $response->assertForbidden();
    }

    public function test_admin_can_view_feature()
    {
        // Arrange
        $feature = AccessibilityFeature::factory()->create();

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('recursos-de-acessibilidade.visualizar', $feature));

        // Assert
        $response->assertOk();
        $response->assertViewHas('feature', $feature);
    }

    public function test_guest_cannot_update_feature()
    {
        // Arrange
        $feature = AccessibilityFeature::factory()->create();

        // Act
        $response = $this->put(
            route('recursos-de-acessibilidade.atualizar', $feature),
            ['name' => 'Novo']
        );

        // Assert
        $response->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_update_feature()
    {
        // Arrange
        $feature = AccessibilityFeature::factory()->create();

        // Act
        $response = $this->actingAs($this->regularUser)
            ->put(route('recursos-de-acessibilidade.atualizar', $feature), [
                'name' => 'Novo'
            ]);

        // Assert
        $response->assertForbidden();
    }

    public function test_admin_can_update_feature()
    {
        // Arrange
        $feature = AccessibilityFeature::factory()->create();

        // Act
        $response = $this->actingAs($this->admin)
            ->put(route('recursos-de-acessibilidade.atualizar', $feature), [
                'name' => 'Atualizado',
                'is_active' => true,
            ]);

        // Assert
        $response->assertRedirect(route('recursos-de-acessibilidade.index'));
        $this->assertDatabaseHas('accessibility_features', [
            'id' => $feature->id,
            'name' => 'Atualizado'
        ]);
    }

    public function test_guest_cannot_delete_feature()
    {
        // Arrange
        $feature = AccessibilityFeature::factory()->create();

        // Act
        $response = $this->delete(route('recursos-de-acessibilidade.excluir', $feature));

        // Assert
        $response->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_delete_feature()
    {
        // Arrange
        $feature = AccessibilityFeature::factory()->create();

        // Act
        $response = $this->actingAs($this->regularUser)
            ->delete(route('recursos-de-acessibilidade.excluir', $feature));

        // Assert
        $response->assertForbidden();
    }

    public function test_admin_can_delete_feature()
    {
        // Arrange
        $feature = AccessibilityFeature::factory()->create();

        // Act
        $response = $this->actingAs($this->admin)
            ->delete(route('recursos-de-acessibilidade.excluir', $feature));

        // Assert
        $response->assertRedirect(route('recursos-de-acessibilidade.index'));
        $this->assertDatabaseMissing('accessibility_features', ['id' => $feature->id]);
    }

    public function test_index_can_filter_by_name_via_ajax()
    {
        // Arrange
        AccessibilityFeature::factory()->create(['name' => 'Braille']);
        AccessibilityFeature::factory()->create(['name' => 'Libras']);

        // Act
        $response = $this->actingAs($this->admin)
            ->get(
                route('recursos-de-acessibilidade.index', ['name' => 'Braille']),
                ['HTTP_X-Requested-With' => 'XMLHttpRequest']
            );

        // Assert
        $response->assertOk();
        $response->assertSee('Braille');
        $response->assertDontSee('Libras');
    }

    public function test_index_can_filter_by_status_via_ajax()
    {
        // Arrange
        AccessibilityFeature::factory()->active()->create(['name' => 'VISIBLE_ACTIVE']);
        AccessibilityFeature::factory()->inactive()->create(['name' => 'HIDDEN_INACTIVE']);

        // Act
        $response = $this->actingAs($this->admin)
            ->get(
                route('recursos-de-acessibilidade.index', ['is_active' => '1']),
                ['HTTP_X-Requested-With' => 'XMLHttpRequest']
            );

        // Assert
        $response->assertOk();
        $response->assertSee('VISIBLE_ACTIVE');
        $response->assertDontSee('HIDDEN_INACTIVE');
    }

    public function test_it_has_materials_relationship()
    {
        // Arrange
        $feature = new AccessibilityFeature();

        // Act
        $relation = $feature->materials();

        // Assert
        $this->assertInstanceOf(BelongsToMany::class, $relation);
        $this->assertEquals('accessible_educational_material_accessibility', $relation->getTable());
    }
}
