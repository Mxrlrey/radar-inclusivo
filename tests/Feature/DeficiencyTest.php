<?php

namespace Tests\Feature;

use App\Models\Deficiency;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeficiencyTest extends TestCase
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

    public function test_guest_cannot_access_deficiencies_index()
    {
        // Act
        $response = $this->get(route('deficiencias.index'));

        // Assert
        $response->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_access_deficiencies_index()
    {
        // Act
        $response = $this->actingAs($this->regularUser)
            ->get(route('deficiencias.index'));

        // Assert
        $response->assertForbidden();
    }

    public function test_admin_can_list_deficiencies()
    {
        // Arrange
        Deficiency::factory()->count(2)->create();

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('deficiencias.index'));

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.deficiencies.index');
        $response->assertViewHas('deficiencies');
    }

    public function test_deficiencies_index_returns_partial_when_ajax()
    {
        // Arrange
        Deficiency::factory()->count(2)->create();

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('deficiencias.index'), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.deficiencies.partials.table');
    }

    public function test_admin_can_access_deficiency_create_page()
    {
        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('deficiencias.criar'));

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.deficiencies.create');
    }

    public function test_admin_can_store_deficiency()
    {
        // Arrange
        $data = [
            'name' => 'Deficiência Visual',
            'cid_code' => 'H54',
            'description' => 'Baixa visão ou cegueira.',
            'is_active' => true,
        ];

        // Act
        $response = $this->actingAs($this->admin)
            ->post(route('deficiencias.salvar'), $data);

        // Assert
        $response->assertRedirect(route('deficiencias.index'));
        $this->assertDatabaseHas('deficiencies', [
            'name' => 'Deficiência Visual',
            'cid_code' => 'H54',
        ]);
    }

    public function test_store_requires_unique_deficiency_name()
    {
        // Arrange
        Deficiency::factory()->create(['name' => 'Deficiência Auditiva']);

        // Act
        $response = $this->actingAs($this->admin)
            ->post(route('deficiencias.salvar'), ['name' => 'Deficiência Auditiva']);

        // Assert
        $response->assertSessionHasErrors('name');
    }

    public function test_admin_can_view_deficiency()
    {
        // Arrange
        $deficiency = Deficiency::factory()->create();

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('deficiencias.visualizar', $deficiency));

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.deficiencies.show');
        $response->assertViewHas('deficiency', $deficiency);
    }

    public function test_admin_can_access_deficiency_edit_page()
    {
        // Arrange
        $deficiency = Deficiency::factory()->create();

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('deficiencias.editar', $deficiency));

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.deficiencies.edit');
        $response->assertViewHas('deficiency', $deficiency);
    }

    public function test_admin_can_update_deficiency()
    {
        // Arrange
        $deficiency = Deficiency::factory()->create(['name' => 'Nome Antigo']);

        $data = [
            'name' => 'Nome Atualizado',
            'cid_code' => 'F84',
            'description' => 'Descrição atualizada.',
            'is_active' => true,
        ];

        // Act
        $response = $this->actingAs($this->admin)
            ->put(route('deficiencias.atualizar', $deficiency), $data);

        // Assert
        $response->assertRedirect(route('deficiencias.index'));
        $this->assertDatabaseHas('deficiencies', [
            'id' => $deficiency->id,
            'name' => 'Nome Atualizado',
        ]);
    }

    public function test_admin_can_toggle_deficiency_active_status()
    {
        // Arrange
        $deficiency = Deficiency::factory()->create(['is_active' => true]);

        // Act
        $response = $this->actingAs($this->admin)
            ->patch(route('deficiencias.desativar', $deficiency));

        // Assert
        $response->assertRedirect(route('deficiencias.index'));
        $this->assertDatabaseHas('deficiencies', [
            'id' => $deficiency->id,
            'is_active' => false,
        ]);
    }

    public function test_admin_can_delete_deficiency()
    {
        // Arrange
        $deficiency = Deficiency::factory()->create();

        // Act
        $response = $this->actingAs($this->admin)
            ->delete(route('deficiencias.excluir', $deficiency));

        // Assert
        $response->assertRedirect(route('deficiencias.index'));
        $this->assertSoftDeleted('deficiencies', ['id' => $deficiency->id]);
    }
}
