<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Laptop', 'Proyektor', 'Kamera', 'Ruangan'] as $name) {
            Category::query()->firstOrCreate(['name' => $name]);
        }
    }
}
