<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Position;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PositionTest extends TestCase
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

    public function test_guest_cannot_access_positions_index()
    {
        // Act
        $response = $this->get(route('cargos.index'));

        // Assert
        $response->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_access_positions_index()
    {
        // Act
        $response = $this->actingAs($this->regularUser)->get(route('cargos.index'));

        // Assert
        $response->assertForbidden();
    }

    public function test_admin_can_list_positions()
    {
        // Arrange
        Position::factory()->count(2)->create();

        // Act
        $response = $this->actingAs($this->admin)->get(route('cargos.index'));

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.positions.index');
        $response->assertViewHas('positions');
    }

    public function test_positions_index_returns_partial_when_ajax()
    {
        // Arrange
        Position::factory()->count(2)->create();

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('cargos.index'), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.positions.partials.table');
    }

    public function test_admin_can_access_position_create_page()
    {
        // Act
        $response = $this->actingAs($this->admin)->get(route('cargos.criar'));

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.positions.create');
        $response->assertViewHas('permissions');
    }

    public function test_admin_can_store_position_with_permissions()
    {
        // Arrange
        $permission = Permission::create([
            'name' => 'Ver estudantes',
            'slug' => 'student.view',
        ]);

        $data = [
            'name' => 'Coordenador NAPNE',
            'description' => 'Coordena atendimentos.',
            'is_active' => true,
            'permissions' => [$permission->id],
        ];

        // Act
        $response = $this->actingAs($this->admin)
            ->post(route('cargos.salvar'), $data);

        // Assert
        $response->assertRedirect(route('cargos.index'));
        $this->assertDatabaseHas('positions', ['name' => 'Coordenador NAPNE']);
        $this->assertDatabaseHas('permission_position', ['permission_id' => $permission->id]);
    }

    public function test_store_requires_unique_position_name()
    {
        // Arrange
        Position::factory()->create(['name' => 'Intérprete']);

        // Act
        $response = $this->actingAs($this->admin)
            ->post(route('cargos.salvar'), ['name' => 'Intérprete']);

        // Assert
        $response->assertSessionHasErrors('name');
    }

    public function test_admin_can_view_position()
    {
        // Arrange
        $position = Position::factory()->create();

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('cargos.visualizar', $position));

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.positions.show');
        $response->assertViewHas('position', $position);
    }

    public function test_admin_can_access_position_edit_page()
    {
        // Arrange
        $position = Position::factory()->create();

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('cargos.editar', $position));

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.positions.edit');
        $response->assertViewHas('position', $position);
        $response->assertViewHas('selectedPermissions');
    }

    public function test_admin_can_update_position()
    {
        // Arrange
        $position = Position::factory()->create(['name' => 'Cargo Antigo']);

        $data = [
            'name' => 'Cargo Atualizado',
            'description' => 'Nova descrição.',
            'is_active' => true,
        ];

        // Act
        $response = $this->actingAs($this->admin)
            ->put(route('cargos.atualizar', $position), $data);

        // Assert
        $response->assertRedirect(route('cargos.index'));
        $this->assertDatabaseHas('positions', [
            'id' => $position->id,
            'name' => 'Cargo Atualizado',
        ]);
    }

    public function test_admin_can_toggle_position_active_status()
    {
        // Arrange
        $position = Position::factory()->create(['is_active' => true]);

        // Act
        $response = $this->actingAs($this->admin)
            ->patch(route('cargos.desativar', $position));

        // Assert
        $response->assertRedirect(route('cargos.index'));
        $this->assertDatabaseHas('positions', [
            'id' => $position->id,
            'is_active' => false,
        ]);
    }

    public function test_admin_can_delete_position()
    {
        // Arrange
        $position = Position::factory()->create();

        // Act
        $response = $this->actingAs($this->admin)
            ->delete(route('cargos.excluir', $position));

        // Assert
        $response->assertRedirect(route('cargos.index'));
        $this->assertSoftDeleted('positions', ['id' => $position->id]);
    }
}
