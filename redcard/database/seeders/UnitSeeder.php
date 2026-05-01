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
            ['name' => 'Laptop ASUS', 'code' => 'LP001', 'stock' => 10, 'categories' => ['Laptop', 'Elektronik']],
            ['name' => 'Proyektor Epson', 'code' => 'PJ001', 'stock' => 4, 'categories' => ['Proyektor', 'Presentasi']],
            ['name' => 'Kamera Canon', 'code' => 'KM001', 'stock' => 2, 'categories' => ['Kamera', 'Fotografi']],
        ];

        foreach ($rows as $row) {
            $categoryNames = $row['categories'];
            unset($row['categories']);

            $unit = Unit::query()->firstOrCreate(
                ['code' => $row['code']],
                ['name' => $row['name'], 'stock' => $row['stock'] ?? 0],
            );

            // Ambil semua category IDs berdasarkan nama
            $categoryIds = Category::query()
                ->whereIn('name', $categoryNames)
                ->pluck('id')
                ->toArray();

            if (!empty($categoryIds)) {
                $unit->categories()->syncWithoutDetaching($categoryIds);
            }
        }
    }
}
