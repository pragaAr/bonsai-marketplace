<?php

namespace App\Livewire;

use App\Models\Product;
use App\Services\CategoryService;
use App\Services\ProductQueryService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Shop extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public $search = '';

    #[Url(as: 'category')]
    public $category = 'all';

    #[Url(as: 'sort')]
    public $sort = 'default';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function updatingSort(&$value): void
    {
        $allowedSorts = ['default', 'price_asc', 'price_desc', 'name_asc', 'name_desc'];

        if (! in_array($value, $allowedSorts, true)) {
            $value = 'default';
        }

        $this->resetPage();
    }

    public function updatedPage(): void
    {
        $this->dispatch('shop-page-updated');
    }

    public function selectCategory($catSlug)
    {
        $this->category = $catSlug;
        $this->resetPage();
    }

    public function setSort(string $sort): void
    {
        $this->sort = $sort;
    }

    public function queryStringHandlesPagination(): array
    {
        return [
            'paginators.page' => [
                'history' => true,
                'as' => 'page',
                'keep' => false,
                'except' => 1,
            ],
        ];
    }

    #[Layout('layouts.app')]
    #[Title('Koleksi')]
    public function render(ProductQueryService $productService, CategoryService $categoryService)
    {
        $baseQuery = Product::query()->approved();

        $products = $productService
            ->buildFilteredQuery($baseQuery, $this->search, $this->category, $this->sort)
            ->paginate(12);

        $categories = $categoryService->getGlobalCategories();

        return view('livewire.shop.index', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }
}
