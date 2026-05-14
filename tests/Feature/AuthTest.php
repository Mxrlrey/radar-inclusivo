<?php

namespace Tests\Feature;

use App\Enums\BarrierStatus;
use App\Enums\InspectionType;
use App\Models\Barrier;
use App\Models\BarrierCategory;
use App\Models\Inspection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_access_login_form()
    {
        // Act
        $response = $this->get(route('login'));

        // Assert
        $response->assertOk();
        $response->assertViewIs('auth.login');
    }

    public function test_admin_can_login_and_access_dashboard()
    {
        // Arrange
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('secret123'),
            'is_admin' => true,
        ]);

        // Act
        $response = $this->post(route('login'), [
            'email' => 'admin@example.com',
            'password' => 'secret123',
        ]);

        // Assert
        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_rejects_invalid_credentials()
    {
        // Arrange
        User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('secret123'),
            'is_admin' => true,
        ]);

        // Act
        $response = $this->from(route('login'))->post(route('login'), [
            'email' => 'admin@example.com',
            'password' => 'wrong-password',
        ]);

        // Assert
        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_user_without_access_permission_is_logged_out()
    {
        // Arrange
        $user = User::factory()->create([
            'email' => 'sem-acesso@example.com',
            'password' => Hash::make('secret123'),
            'is_admin' => false,
            'professional_id' => null,
        ]);

        // Act
        $response = $this->from(route('login'))->post(route('login'), [
            'email' => 'sem-acesso@example.com',
            'password' => 'secret123',
        ]);

        // Assert
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error', 'Usuário sem permissão de acesso.');
        $this->assertGuest();
    }

    public function test_authenticated_user_can_view_dashboard()
    {
        // Arrange
        $user = User::factory()->create(['is_admin' => true]);

        // Act
        $response = $this->actingAs($user)->get(route('dashboard'));

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.dashboard');
        $response->assertViewHas('totalStudents');
        $response->assertViewHas('totalProfessionals');
        $response->assertViewHas('mapBarriers');
    }

    public function test_dashboard_includes_barriers_with_current_status_on_map()
    {
        // Arrange
        $user = User::factory()->create(['is_admin' => true]);
        $category = BarrierCategory::factory()->create([
            'name' => 'Acesso',
            'blocks_map' => true,
        ]);
        $barrier = Barrier::factory()->create([
            'name' => 'Escada sem corrimão',
            'barrier_category_id' => $category->id,
            'latitude' => -14.22,
            'longitude' => -42.78,
        ]);

        Inspection::factory()->forBarrier($barrier)->create([
            'status' => BarrierStatus::IDENTIFIED->value,
            'type' => InspectionType::INITIAL->value,
        ]);

        // Act
        $response = $this->actingAs($user)->get(route('dashboard'));

        // Assert
        $response->assertOk();
        $mapBarriers = $response->viewData('mapBarriers');
        $this->assertCount(1, $mapBarriers);
        $this->assertSame('Escada sem corrimão', $mapBarriers->first()['name']);
        $this->assertSame(BarrierStatus::IDENTIFIED->value, $mapBarriers->first()['status']);
    }

    public function test_authenticated_user_can_logout()
    {
        // Arrange
        $user = User::factory()->create(['is_admin' => true]);

        // Act
        $response = $this->actingAs($user)->post(route('logout'));

        // Assert
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_guest_can_access_forgot_password_form()
    {
        // Act
        $response = $this->get(route('password.request'));

        // Assert
        $response->assertOk();
        $response->assertViewIs('auth.forgot-password');
    }

    public function test_forgot_password_validates_email()
    {
        // Act
        $response = $this->post(route('password.email'), ['email' => 'invalid']);

        // Assert
        $response->assertSessionHasErrors('email');
    }

    public function test_forgot_password_returns_status_for_valid_email_format()
    {
        // Act
        $response = $this->from(route('password.request'))
            ->post(route('password.email'), ['email' => 'usuario@example.com']);

        // Assert
        $response->assertRedirect(route('password.request'));
        $response->assertSessionHas('status', 'Se o e-mail estiver cadastrado, enviaremos instruções.');
    }

    public function test_guest_can_access_reset_password_form()
    {
        // Act
        $response = $this->get(route('password.reset', [
            'token' => 'token-teste',
            'email' => 'usuario@example.com',
        ]));

        // Assert
        $response->assertOk();
        $response->assertViewIs('auth.reset-password');
        $response->assertViewHas('token', 'token-teste');
        $response->assertViewHas('email', 'usuario@example.com');
    }

    public function test_user_can_reset_password_with_valid_token()
    {
        // Arrange
        $user = User::factory()->create([
            'email' => 'reset@example.com',
            'password' => Hash::make('old-password'),
        ]);

        $token = Password::createToken($user);

        // Act
        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => 'reset@example.com',
            'password' => 'newpass1',
            'password_confirmation' => 'newpass1',
        ]);

        // Assert
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success', 'Senha alterada!');
        $this->assertTrue(Hash::check('newpass1', $user->fresh()->password));
    }
}
