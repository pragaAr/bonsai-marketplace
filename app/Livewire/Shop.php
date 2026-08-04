<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\PlantDetail;
use App\Models\Product;
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
    public function render()
    {
        $searchTerm = trim($this->search);

        $products = Product::query()
            ->where('status', 'approved')
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
                $q->orderBy('featured', 'desc')->orderBy('id', 'desc');
            })
            ->paginate(12);

        // Ambil kategori DB & tambahkan opsi "Semua" di awal
        $dbCategories = Category::query()->orderBy('name')->get(['name', 'slug']);

        $categories = collect([
            (object) ['name' => 'Semua', 'slug' => 'all'],
        ])->concat($dbCategories);

        return view('livewire.shop.index', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }
}
