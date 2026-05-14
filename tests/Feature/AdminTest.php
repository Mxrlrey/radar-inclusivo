<?php

namespace Tests\Feature;

use App\Http\Controllers\AdminController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_impersonate_non_admin_user()
    {
        // Arrange
        $admin = User::factory()->create(['is_admin' => true]);
        $target = User::factory()->create(['is_admin' => false]);

        // Act
        $response = $this->actingAs($admin)
            ->post(route('admin.impersonate', $target));

        // Assert
        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('impersonator_id', $admin->id);
        $this->assertAuthenticatedAs($target);
    }

    public function test_admin_cannot_impersonate_another_admin()
    {
        // Arrange
        $admin = User::factory()->create(['is_admin' => true]);
        $target = User::factory()->create(['is_admin' => true]);

        // Act
        $response = $this->actingAs($admin)
            ->post(route('admin.impersonate', $target));

        // Assert
        $response->assertForbidden();
        $this->assertAuthenticatedAs($admin);
    }

    public function test_admin_cannot_impersonate_self()
    {
        // Arrange
        $admin = User::factory()->create(['is_admin' => true]);

        // Act
        $response = $this->actingAs($admin)
            ->post(route('admin.impersonate', $admin));

        // Assert
        $response->assertForbidden();
        $this->assertAuthenticatedAs($admin);
    }

    public function test_controller_blocks_self_impersonation_branch()
    {
        // Arrange
        $user = User::factory()->create(['is_admin' => false]);

        // Assert
        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('Não é possível impersonar você mesmo.');

        // Act
        $this->actingAs($user);
        app(AdminController::class)->impersonate($user);
    }

    public function test_impersonated_user_can_return_to_admin()
    {
        // Arrange
        $admin = User::factory()->create(['is_admin' => true]);
        $target = User::factory()->create(['is_admin' => false]);

        // Act
        $response = $this->actingAs($target)
            ->withSession(['impersonator_id' => $admin->id])
            ->post(route('admin.impersonate.leave'));

        // Assert
        $response->assertRedirect(route('dashboard'));
        $response->assertSessionMissing('impersonator_id');
        $this->assertAuthenticatedAs($admin);
    }

    public function test_leave_impersonate_without_session_returns_error()
    {
        // Arrange
        $admin = User::factory()->create(['is_admin' => true]);

        // Act
        $response = $this->actingAs($admin)
            ->post(route('admin.impersonate.leave'));

        // Assert
        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error', 'Você não esta em uma impersonação ');
    }
}
