<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;

class ProductQueryService
{
    public function buildFilteredQuery(
        Builder $baseQuery,
        string $search = '',
        string $category = 'all',
        string $sort = 'default',
        string $defaultSortType = 'featured'
    ): Builder {
        return $baseQuery
            ->with(['category', 'productable'])
            ->search($search)
            ->byCategory($category)
            ->sortBy($sort, $defaultSortType);
    }
}
