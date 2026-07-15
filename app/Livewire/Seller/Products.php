<?php

namespace App\Livewire\Seller;

use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Products extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'status')]
    public string $filterStatus = '';

    public bool $showDeleteModal = false;

    public ?int $deleteId = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        // Pastikan produk milik seller ini
        $product = Product::where('seller_id', auth()->id())->findOrFail($id);
        $this->deleteId = $product->id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if (!$this->deleteId) {
            return;
        }

        $product = Product::where('seller_id', auth()->id())->findOrFail($this->deleteId);

        // Hapus model relasi polymorphic detail
        if ($product->productable) {
            $product->productable->delete();
        }

        // Log activity sebelum hapus
        activity('product')
            ->performedOn($product)
            ->causedBy(auth()->user())
            ->event('product_deleted')
            ->log("Produk {$product->name} dihapus oleh seller");

        // Hapus produk (Spatie Media Library akan otomatis menghapus gambar terkait)
        $product->delete();

        $this->showDeleteModal = false;
        $this->deleteId = null;
        $this->dispatch('toast', message: 'Produk berhasil dihapus.', type: 'success');
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'filterStatus']);
        $this->resetPage();
    }

    private function hasActiveFilter(): bool
    {
        return $this->search !== '' || $this->filterStatus !== '';
    }

    #[Layout('layouts.dashboard')]
    #[Title('Daftar Produk Saya')]
    public function render()
    {
        $query = Product::query()
            ->where('seller_id', auth()->id())
            ->with(['category', 'productable']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('short_description', 'like', "%{$this->search}%")
                    ->orWhere('description', 'like', "%{$this->search}%");
            });
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        $products = $query->latest()->paginate(10);

        return view('livewire.seller.products', [
            'products' => $products,
            'hasActiveFilter' => $this->hasActiveFilter(),
        ]);
    }
}
