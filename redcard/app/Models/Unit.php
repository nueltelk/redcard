<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Unit extends Model
{
    protected $fillable = [
        'name',
        'code',
        'stock',
        'description',
       
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    // Helper method untuk attach multiple kategori berdasarkan nama
    public function attachCategoriesByName(array $categoryNames): void
    {
        $categoryIds = Category::query()
            ->whereIn('name', $categoryNames)
            ->pluck('id')
            ->toArray();

        if (!empty($categoryIds)) {
            $this->categories()->syncWithoutDetaching($categoryIds);
        }
    }

    // Helper method untuk sync kategori (ganti semua)
    public function syncCategoriesByName(array $categoryNames): void
    {
        $categoryIds = Category::query()
            ->whereIn('name', $categoryNames)
            ->pluck('id')
            ->toArray();

        $this->categories()->sync($categoryIds);
    }
}
