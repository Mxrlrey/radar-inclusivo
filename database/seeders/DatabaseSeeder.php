<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            DeficiencySeeder::class,
            PositionSeeder::class,
            StudentSeeder::class,
            ProfessionalSeeder::class,
            BarrierCategorySeeder::class,
            AccessibilityFeatureSeeder::class,
            AccessibleEducationalMaterialSeeder::class,
//            PSPUSeeder::class,
            AdminSeeder::class,
            PermissionSeeder::class,
            InstitutionSeeder::class,
            LocationSeeder::class,
            AssistiveTechnologySeeder::class,
//            BarrierSeeder::class,
            InstitutionalEventSeeder::class,
//            DemoLoanWaitlistSeeder::class,
        ]);
    }
}
