<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Collection;

class CategoryService
{
    public function getGlobalCategories(): Collection
    {
        $categories = Category::query()->orderBy('name')->get(['name', 'slug']);

        return $this->prependAllOption($categories);
    }

    public function getCategoriesBySeller(int $sellerId): Collection
    {
        $categories = Category::query()
            ->whereHas('products', function ($q) use ($sellerId) {
                $q->where('seller_id', $sellerId)
                    ->where('status', 'approved');
            })
            ->orderBy('name')
            ->get(['name', 'slug']);

        return $this->prependAllOption($categories);
    }

    private function prependAllOption(Collection $categories): Collection
    {
        return collect([
            (object) ['name' => 'Semua', 'slug' => 'all'],
        ])->concat($categories);
    }
}
