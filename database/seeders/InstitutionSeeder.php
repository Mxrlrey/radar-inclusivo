<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InstitutionSeeder extends Seeder
{
    public function run()
    {
        DB::table('institutions')->insert([
            'name' => 'Instituto Federal Baiano de Guanambi',
            'short_name' => 'IF Baiano Guanambi',
            'city' => 'Guanambi',
            'state' => 'BA',
            'district' => null,
            'address' => null,
            'latitude' => -14.30277594,
            'longitude' => -42.69508323,
            'default_zoom' => 16,
            'is_active' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
