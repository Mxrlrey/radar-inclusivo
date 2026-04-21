<?php

namespace App\Providers;

use App\Models\AccessibleEducationalMaterial;
use App\Models\AssistiveTechnology;
use App\Models\Barrier;
use App\Models\Deficiency;
use App\Models\Inspection;
use App\Models\Institution;
use App\Models\Permission;
use App\Models\Person;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();
        App::setLocale('pt_BR');
        Carbon::setLocale('pt_BR');
        Relation::enforceMorphMap([
            'student' => Student::class,
            'person' => Person::class,
            'assistive_technology' => AssistiveTechnology::class,
            'accessible_educational_material' => AccessibleEducationalMaterial::class,
            'barrier' => Barrier::class,
            'inspection' => Inspection::class,
            'user' => User::class,
        ]);

        // --- SISTEMA DE PERMISSÕES ---
        // O provider não pode depender do banco no bootstrap inicial
        // porque comandos como package:discover podem rodar antes do MySQL existir.
        try {
            if (Schema::hasTable('permissions')) {

                // ADMIN TEM TODAS PERMISSÕES
                Gate::before(function ($user, $ability) {
                    if ($user->is_admin) {
                        return true;
                    }
                });

                $permissions = Permission::all();

                foreach ($permissions as $permission) {
                    Gate::define($permission->slug, function ($user) use ($permission) {
                        return $user->hasPermission($permission->slug);
                    });
                }
            }
        } catch (\Throwable $e) {
            // Silencia durante bootstrap sem banco / migrate / package discovery
        }

        // View Composer para a Navbar (INSTITUIÇÃO)
        View::composer('layouts.master', function ($view) {
            try {
                $institution = Schema::hasTable('institutions') ? Institution::first() : null;
            } catch (\Throwable $e) {
                $institution = null;
            }

            $view->with('institution', $institution);
        });
    }
}
