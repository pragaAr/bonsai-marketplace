<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Category;
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
    public $category = 'All';

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

    public function updatingSort()
    {
        $this->resetPage();
    }

    public function selectCategory($catSlug)
    {
        $this->category = $catSlug;
    }

    #[Title('Koleksi')]
    public function render()
    {
        $query = Product::where('status', 'approved')->with(['category', 'productable']);

        // Search name, description & species details for plant products
        if (! empty(trim($this->search))) {
            $term = '%'.trim($this->search).'%';
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                    ->orWhere('description', 'like', $term)
                    ->orWhereHasMorph('productable', [\App\Models\PlantDetail::class], function ($query) use ($term) {
                        $query->whereHas('species', function ($speciesQuery) use ($term) {
                            $speciesQuery->where('scientific_name', 'like', $term)
                                ->orWhere('common_name', 'like', $term);
                        });
                    });
            });
        }

        // Category filter
        if ($this->category !== 'All') {
            $query->whereHas('category', function ($q) {
                $q->where('slug', $this->category);
            });
        }

        // Sorting
        switch ($this->sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default:
                // Default featured or id order
                $query->orderBy('featured', 'desc')->orderBy('id', 'asc');
                break;
        }

        $products = $query->paginate(12);

        $dbCategories = Category::all();
        $categories = collect([
            (object) ['name' => 'Semua', 'slug' => 'All']
        ])->concat($dbCategories->map(fn($c) => (object)[
            'name' => $c->name,
            'slug' => $c->slug
        ]));

        return view('livewire.shop', [
            'products' => $products,
            'categories' => $categories,
        ])->layout('layouts.app');
    }
}
