<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\SellerRequest;
use App\Models\User;
use App\Services\CategoryService;
use App\Services\ProductQueryService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class SellerShop extends Component
{
    use WithPagination;

    public User $seller;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'category')]
    public string $category = 'all';

    #[Url(as: 'sort')]
    public string $sort = 'default';

    public function mount(string $seller_slug): void
    {
        $sellerRequest = SellerRequest::query()
            ->where('store_slug', $seller_slug)
            ->where('status', 'approved')
            ->with('user')
            ->firstOrFail();

        $this->seller = $sellerRequest->user->load('sellerRequest');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingCategory(): void
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

    public function selectCategory(string $category): void
    {
        $this->category = $category;
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
    #[Title('Toko Seller')]
    public function render(ProductQueryService $productService, CategoryService $categoryService)
    {
        $baseQuery = Product::query()
            ->where('seller_id', $this->seller->id)
            ->approved();

        $productCount = (clone $baseQuery)->count();

        $products = $productService
            ->buildFilteredQuery($baseQuery, $this->search, $this->category, $this->sort, defaultSortType: 'latest')
            ->paginate(12);

        $categories = $categoryService->getCategoriesBySeller($this->seller->id);

        return view('livewire.shop.seller-shop', [
            'products' => $products,
            'productCount' => $productCount,
            'categories' => $categories,
        ]);
    }
}
