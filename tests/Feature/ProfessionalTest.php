<?php

namespace Tests\Feature;

use App\Enums\Gender;
use App\Models\Position;
use App\Models\Professional;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfessionalTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $regularUser;
    protected Position $position;

    protected function setUp(): void
    {
        parent::setUp();

        // Arrange
        $this->admin = User::factory()->create(['is_admin' => true]);
        $this->regularUser = User::factory()->create(['is_admin' => false]);
        $this->position = Position::factory()->active()->create();
    }

    public function test_guest_cannot_access_professionals_index()
    {
        // Act
        $response = $this->get(route('profissionais.index'));

        // Assert
        $response->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_access_professionals_index()
    {
        // Act
        $response = $this->actingAs($this->regularUser)->get(route('profissionais.index'));

        // Assert
        $response->assertForbidden();
    }

    public function test_admin_can_list_professionals()
    {
        // Arrange
        Professional::factory()->count(2)->create(['position_id' => $this->position->id]);

        // Act
        $response = $this->actingAs($this->admin)->get(route('profissionais.index'));

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.professionals.index');
        $response->assertViewHas('professionals');
        $response->assertViewHas('positions');
    }

    public function test_professionals_index_returns_partial_when_ajax()
    {
        // Arrange
        Professional::factory()->count(2)->create(['position_id' => $this->position->id]);

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('profissionais.index'), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.professionals.partials.table');
    }

    public function test_admin_can_access_professional_create_page()
    {
        // Act
        $response = $this->actingAs($this->admin)->get(route('profissionais.criar'));

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.professionals.create');
        $response->assertViewHas('positions');
        $response->assertViewHas('genders');
    }

    public function test_admin_can_store_professional()
    {
        // Arrange
        $data = $this->validProfessionalData([
            'name' => 'Profissional Teste',
            'email' => 'profissional.teste@example.com',
            'document' => '52998224725',
            'registration' => 'PROF-001',
        ]);

        // Act
        $response = $this->actingAs($this->admin)
            ->post(route('profissionais.salvar'), $data);

        // Assert
        $response->assertRedirect(route('profissionais.index'));
        $this->assertDatabaseHas('people', ['email' => 'profissional.teste@example.com']);
        $this->assertDatabaseHas('professionals', ['registration' => 'PROF-001']);
        $this->assertDatabaseHas('users', ['email' => 'profissional.teste@example.com']);
    }

    public function test_store_requires_professional_name()
    {
        // Arrange
        $data = $this->validProfessionalData(['name' => '']);

        // Act
        $response = $this->actingAs($this->admin)
            ->post(route('profissionais.salvar'), $data);

        // Assert
        $response->assertSessionHasErrors('name');
    }

    public function test_admin_can_view_professional()
    {
        // Arrange
        $professional = Professional::factory()->create(['position_id' => $this->position->id]);

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('profissionais.visualizar', $professional));

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.professionals.show');
        $response->assertViewHas('professional', $professional);
    }

    public function test_admin_can_access_professional_edit_page()
    {
        // Arrange
        $professional = Professional::factory()->create(['position_id' => $this->position->id]);

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('profissionais.editar', $professional));

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.professionals.edit');
        $response->assertViewHas('professional', $professional);
        $response->assertViewHas('positions');
    }

    public function test_admin_can_update_professional()
    {
        // Arrange
        $professional = Professional::factory()->create(['position_id' => $this->position->id]);
        $professional->person->update(['document' => '52998224725']);

        $data = $this->validProfessionalData([
            'name' => 'Profissional Atualizado',
            'email' => 'profissional.atualizado@example.com',
            'document' => $professional->person->document,
            'registration' => $professional->registration,
        ]);

        // Act
        $response = $this->actingAs($this->admin)
            ->put(route('profissionais.atualizar', $professional), $data);

        // Assert
        $response->assertRedirect(route('profissionais.index'));
        $this->assertDatabaseHas('people', [
            'id' => $professional->person_id,
            'name' => 'Profissional Atualizado',
        ]);
        $this->assertDatabaseHas('users', ['email' => 'profissional.atualizado@example.com']);
    }

    public function test_admin_can_delete_professional()
    {
        // Arrange
        $professional = Professional::factory()->create(['position_id' => $this->position->id]);
        $personId = $professional->person_id;

        // Act
        $response = $this->actingAs($this->admin)
            ->delete(route('profissionais.excluir', $professional));

        // Assert
        $response->assertRedirect(route('profissionais.index'));
        $this->assertSoftDeleted('professionals', ['id' => $professional->id]);
        $this->assertSoftDeleted('people', ['id' => $personId]);
    }

    private function validProfessionalData(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Profissional Radar',
            'document' => '52998224725',
            'birth_date' => '1990-01-10',
            'gender' => Gender::NOT_SPECIFIED->value,
            'email' => 'profissional.radar@example.com',
            'phone' => '77999999999',
            'address' => 'Rua de Teste, 200',
            'registration' => 'PROF-BASE',
            'position_id' => $this->position->id,
            'entry_date' => '2024-02-01',
            'is_active' => true,
            'is_admin' => false,
        ], $overrides);
    }
}
