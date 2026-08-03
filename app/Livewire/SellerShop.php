<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Models\SellerRequest;
use App\Models\User;
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
        $this->resetPage($this->paginationPageName());
    }

    public function updatingCategory(): void
    {
        $this->resetPage($this->paginationPageName());
    }

    public function selectCategory(string $category): void
    {
        $this->category = $category;
        $this->resetPage();
    }

    public function setSort(string $sort): void
    {
        $allowedSorts = ['default', 'price_asc', 'price_desc', 'name_asc', 'name_desc'];

        $this->sort = in_array($sort, $allowedSorts, true) ? $sort : 'default';
        $this->resetPage();
    }

    protected function paginationPageName(): string
    {
        return 'page';
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
    public function render()
    {
        $sellerProducts = Product::query()
            ->where('seller_id', $this->seller->id)
            ->where('status', 'approved');

        $productCount = (clone $sellerProducts)->count();

        $categories = Category::query()
            ->whereHas('products', function ($query) {
                $query->where('seller_id', $this->seller->id)
                    ->where('status', 'approved');
            })
            ->orderBy('name')
            ->get();

        $query = (clone $sellerProducts)
            ->with('category')
            ->when(trim($this->search) !== '', function ($query) {
                $term = '%'.trim($this->search).'%';

                $query->where(function ($query) use ($term) {
                    $query->where('name', 'like', $term)
                        ->orWhere('short_description', 'like', $term)
                        ->orWhere('description', 'like', $term);
                });
            })
            ->when($this->category !== 'all', function ($query) {
                $query->whereHas('category', function ($query) {
                    $query->where('slug', $this->category);
                });
            })
            ->when($this->sort === 'price_asc', fn ($query) => $query->orderBy('price', 'asc'))
            ->when($this->sort === 'price_desc', fn ($query) => $query->orderBy('price', 'desc'))
            ->when($this->sort === 'name_asc', fn ($query) => $query->orderBy('name', 'asc'))
            ->when($this->sort === 'name_desc', fn ($query) => $query->orderBy('name', 'desc'))
            ->when($this->sort === 'default', fn ($query) => $query->latest());

        $products = $query->paginate(12, ['*'], $this->paginationPageName());

        return view('livewire.shop.seller-shop', [
            'products' => $products,
            'productCount' => $productCount,
            'categories' => $categories,
        ]);
    }
}
