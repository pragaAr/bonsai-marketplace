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
    public function render()
    {
        $searchTerm = trim($this->search);

        // Base query seller
        $sellerProducts = Product::query()
            ->where('seller_id', $this->seller->id)
            ->where('status', 'approved');

        // Total count produk seller
        $productCount = (clone $sellerProducts)->count();

        // Ambil kategori DB milik seller ini & tambahkan opsi "Semua" di awal
        $dbCategories = Category::query()
            ->whereHas('products', function ($query) {
                $query->where('seller_id', $this->seller->id)
                    ->where('status', 'approved');
            })
            ->orderBy('name')
            ->get(['name', 'slug']);

        $categories = collect([
            (object) ['name' => 'Semua', 'slug' => 'all'],
        ])->concat($dbCategories);

        // Query produk seller dengan filter lengkap
        $products = (clone $sellerProducts)
            ->with(['category', 'productable'])
            // Filter Search
            ->when($searchTerm !== '', function ($query) use ($searchTerm) {
                $term = "%{$searchTerm}%";

                $query->where(function ($q) use ($term) {
                    $q->where('name', 'like', $term)
                        ->orWhere('short_description', 'like', $term)
                        ->orWhere('description', 'like', $term)
                        ->orWhereHasMorph('productable', [PlantDetail::class], function ($query) use ($term) {
                            $query->whereHas('species', function ($speciesQuery) use ($term) {
                                $speciesQuery->where('scientific_name', 'like', $term)
                                    ->orWhere('common_name', 'like', $term);
                            });
                        });
                });
            })
            // Filter Category
            ->when($this->category !== 'all', function ($query) {
                $query->whereHas('category', fn ($q) => $q->where('slug', $this->category));
            })
            // Sorting
            ->when($this->sort === 'price_asc', fn ($q) => $q->orderBy('price', 'asc'))
            ->when($this->sort === 'price_desc', fn ($q) => $q->orderBy('price', 'desc'))
            ->when($this->sort === 'name_asc', fn ($q) => $q->orderBy('name', 'asc'))
            ->when($this->sort === 'name_desc', fn ($q) => $q->orderBy('name', 'desc'))
            ->when(! in_array($this->sort, ['price_asc', 'price_desc', 'name_asc', 'name_desc']), function ($q) {
                $q->latest();
            })
            ->paginate(12);

        return view('livewire.shop.seller-shop', [
            'products' => $products,
            'productCount' => $productCount,
            'categories' => $categories,
        ]);
    }
}
