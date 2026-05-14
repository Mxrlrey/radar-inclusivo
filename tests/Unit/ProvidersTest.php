<?php

namespace Tests\Unit;

use App\Models\Institution;
use App\Models\Permission;
use App\Models\Position;
use App\Models\Professional;
use App\Models\User;
use App\Providers\AppServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use ReflectionMethod;
use RuntimeException;
use Tests\TestCase;

class ProvidersTest extends TestCase
{
    use RefreshDatabase;

    public function test_app_service_provider_registers_dynamic_permission_gates(): void
    {
        $permission = Permission::create([
            'name' => 'Permissao do provider',
            'slug' => 'provider.permission',
        ]);
        $position = Position::factory()->create();
        $position->permissions()->attach($permission);

        $professional = Professional::factory()->create(['position_id' => $position->id]);
        $allowedUser = User::factory()->create([
            'professional_id' => $professional->id,
            'is_admin' => false,
        ]);
        $deniedUser = User::factory()->create(['is_admin' => false]);
        $admin = User::factory()->create(['is_admin' => true]);

        $this->invokeProviderMethod('registerDynamicPermissions');

        $this->assertTrue(Gate::forUser($allowedUser)->allows('provider.permission'));
        $this->assertFalse(Gate::forUser($deniedUser)->allows('provider.permission'));
        $this->assertTrue(Gate::forUser($admin)->allows('provider.permission'));
    }

    public function test_app_service_provider_ignores_schema_failures_when_registering_permissions(): void
    {
        Schema::shouldReceive('hasTable')
            ->once()
            ->with('permissions')
            ->andThrow(new RuntimeException('database unavailable'));

        $this->invokeProviderMethod('registerDynamicPermissions');

        $this->assertTrue(true);
    }

    public function test_app_service_provider_resolves_institution_for_layout(): void
    {
        $institution = Institution::factory()->create(['name' => 'Campus Provider']);

        $resolved = $this->invokeProviderMethod('resolveInstitutionForLayout');

        $this->assertTrue($institution->is($resolved));
    }

    public function test_app_service_provider_returns_null_when_institution_schema_fails(): void
    {
        Schema::shouldReceive('hasTable')
            ->once()
            ->with('institutions')
            ->andThrow(new RuntimeException('database unavailable'));

        $this->assertNull($this->invokeProviderMethod('resolveInstitutionForLayout'));
    }

    private function invokeProviderMethod(string $methodName): mixed
    {
        $provider = new AppServiceProvider($this->app);
        $method = new ReflectionMethod($provider, $methodName);
        $method->setAccessible(true);

        return $method->invoke($provider);
    }
}
