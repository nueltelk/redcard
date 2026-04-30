<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        Location::query()->firstOrCreate(
            ['name' => 'TULT'],
            ['latitude' => -6.973, 'longitude' => 107.63],
        );

        Location::query()->firstOrCreate(
            ['name' => 'Rektorat'],
            ['latitude' => -6.974, 'longitude' => 107.631],
        );
    }
}