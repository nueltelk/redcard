<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['name' => 'Laptop ASUS', 'code' => 'LP001', 'stock' => 10, 'category' => 'Laptop'],
            ['name' => 'Proyektor Epson', 'code' => 'PJ001', 'stock' => 4, 'category' => 'Proyektor'],
            ['name' => 'Kamera Canon', 'code' => 'KM001', 'stock' => 2, 'category' => 'Kamera'],
        ];

        foreach ($rows as $row) {
            $categoryName = $row['category'];
            unset($row['category']);

            $unit = Unit::query()->firstOrCreate(
                ['code' => $row['code']],
                ['name' => $row['name'], 'stock' => $row['stock'] ?? 0],
            );

            $categoryId = Category::query()->where('name', $categoryName)->value('id');
            if ($categoryId) {
                $unit->categories()->syncWithoutDetaching([$categoryId]);
            }
        }
    }
}
