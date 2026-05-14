<?php

namespace Tests\Feature;

use App\Enums\Gender;
use App\Models\Professional;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_without_professional_profile_is_redirected_from_profile_edit()
    {
        // Arrange
        $admin = User::factory()->create(['is_admin' => true, 'professional_id' => null]);

        // Act
        $response = $this->actingAs($admin)->get(route('profile.edit'));

        // Assert
        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error', 'Administradores globais não possuem perfil de dados pessoais vinculado.');
    }

    public function test_professional_user_can_access_profile_edit()
    {
        // Arrange
        [$user, $professional] = $this->makeProfessionalUser();

        // Act
        $response = $this->actingAs($user)->get(route('profile.edit'));

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.profile.edit');
        $response->assertViewHas('professional', $professional);
        $response->assertViewHas('person', $professional->person);
    }

    public function test_professional_user_can_update_profile()
    {
        // Arrange
        [$user, $professional] = $this->makeProfessionalUser();

        $data = [
            'name' => 'Perfil Atualizado',
            'document' => $professional->person->document,
            'birth_date' => '1991-01-10',
            'gender' => Gender::NOT_SPECIFIED->value,
            'email' => 'perfil.atualizado@example.com',
            'phone' => '77999999999',
            'address' => 'Rua Perfil, 100',
            'password' => 'novaSenha1',
            'password_confirmation' => 'novaSenha1',
        ];

        // Act
        $response = $this->actingAs($user)
            ->put(route('profile.update'), $data);

        // Assert
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Seu perfil foi atualizado com sucesso!');
        $this->assertDatabaseHas('people', [
            'id' => $professional->person_id,
            'name' => 'Perfil Atualizado',
            'email' => 'perfil.atualizado@example.com',
        ]);
        $this->assertTrue(Hash::check('novaSenha1', $user->fresh()->password));
    }

    public function test_profile_update_validates_required_name()
    {
        // Arrange
        [$user, $professional] = $this->makeProfessionalUser();

        $data = [
            'name' => '',
            'document' => $professional->person->document,
            'birth_date' => '1991-01-10',
            'gender' => Gender::NOT_SPECIFIED->value,
            'email' => 'perfil.validacao@example.com',
        ];

        // Act
        $response = $this->actingAs($user)
            ->put(route('profile.update'), $data);

        // Assert
        $response->assertSessionHasErrors('name');
    }

    private function makeProfessionalUser(): array
    {
        $professional = Professional::factory()->create();
        $professional->person->update(['document' => '52998224725']);

        $user = User::factory()->create([
            'professional_id' => $professional->id,
            'is_admin' => false,
            'name' => $professional->person->name,
            'email' => $professional->person->email,
        ]);

        return [$user, $professional];
    }
}
