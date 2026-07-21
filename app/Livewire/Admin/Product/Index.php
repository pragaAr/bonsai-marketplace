<?php

namespace App\Livewire\Admin\Product;

use App\Models\Category;
use App\Models\Product as ProductModel;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'seller')]
    public string $filterSeller = '';

    #[Url(as: 'category')]
    public string $filterCategory = '';

    public bool $showFilterModal = false;

    public function openFilter(): void
    {
        $this->showFilterModal = true;
    }

    public function filterList(): void
    {
        $this->showFilterModal = false;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterSeller(): void
    {
        $this->resetPage();
    }

    public function updatingFilterCategory(): void
    {
        $this->resetPage();
    }

    public function toggleFeatured(int $id): void
    {
        $product = ProductModel::where('status', 'approved')->findOrFail($id);
        $product->update(['featured' => ! $product->featured]);

        $this->dispatch(
            'toast',
            message: $product->featured
                ? 'Produk ditambahkan ke featured.'
                : 'Produk dihapus dari featured.',
            type: 'success',
        );
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'filterSeller', 'filterCategory']);
        $this->resetPage();
    }

    private function hasActiveFilter(): bool
    {
        return $this->search !== ''
            || $this->filterSeller !== ''
            || $this->filterCategory !== '';
    }

    #[Layout('layouts.dashboard')]
    #[Title('Daftar Produk')]
    public function render()
    {
        $query = ProductModel::query()
            ->where('status', 'approved')
            ->with(['category', 'seller']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('short_description', 'like', "%{$this->search}%")
                    ->orWhereHas('seller', function ($sq) {
                        $sq->where('name', 'like', "%{$this->search}%");
                    });
            });
        }

        if ($this->filterSeller) {
            $query->where('seller_id', $this->filterSeller);
        }

        if ($this->filterCategory) {
            $query->where('category_id', $this->filterCategory);
        }

        $products = $query->latest()->paginate(15);

        $sellers = User::query()
            ->whereIn('id', ProductModel::query()
                ->where('status', 'approved')
                ->whereNotNull('seller_id')
                ->select('seller_id')
                ->distinct())
            ->orderBy('name')
            ->get(['id', 'name']);

        $categories = Category::query()
            ->whereHas('products', fn ($q) => $q->where('status', 'approved'))
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('livewire.admin.product.index', [
            'products' => $products,
            'sellers' => $sellers,
            'categories' => $categories,
            'hasActiveFilter' => $this->hasActiveFilter(),
            'title' => 'Daftar Produk',
            'subTitle' => 'Kelola produk yang telah disetujui',
        ]);
    }
}
