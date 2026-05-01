<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        Location::query()->updateOrCreate(
            ['name' => 'TULT'],
            ['latitude' => -6.969233739450027, 'longitude' => 107.62814578066697],
        );

        Location::query()->updateOrCreate(
            ['name' => 'Rektorat'],
            ['latitude' => -6.9740443, 'longitude' => 107.6304548],
        );
    }
}