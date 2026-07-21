<?php

namespace App\Livewire\Admin\Product;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Approval extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'status')]
    public string $filterStatus = 'pending';

    #[Url(as: 'seller')]
    public string $filterSeller = '';

    #[Url(as: 'category')]
    public string $filterCategory = '';

    public bool $showFilterModal = false;

    public bool $showDetailModal = false;

    public ?int $selectedProductId = null;

    public bool $isRejecting = false;

    public string $rejectionReason = '';

    public function openFilter(): void
    {
        $this->showFilterModal = true;
    }

    public function filterList(): void
    {
        if (! in_array($this->filterStatus, ['pending', 'rejected'], true)) {
            $this->filterStatus = 'pending';
        }

        $this->showFilterModal = false;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterStatus(): void
    {
        if (! in_array($this->filterStatus, ['pending', 'rejected'], true)) {
            $this->filterStatus = 'pending';
        }

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

    public function openDetail(int $id): void
    {
        $this->selectedProductId = $id;
        $this->rejectionReason = '';
        $this->isRejecting = false;
        $this->showDetailModal = true;
    }

    public function approve(): void
    {
        if (! $this->selectedProductId) {
            return;
        }

        $product = Product::findOrFail($this->selectedProductId);

        if ($product->status === 'approved') {
            $this->dispatch('toast', message: 'Produk sudah disetujui sebelumnya.', type: 'warning');

            return;
        }

        $product->update([
            'status' => 'approved',
            'rejection_reason' => null,
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        activity('product')
            ->performedOn($product)
            ->causedBy(auth()->user())
            ->event('product_approved')
            ->log("Produk {$product->name} disetujui oleh admin");

        $this->showDetailModal = false;
        $this->selectedProductId = null;
        $this->dispatch('toast', message: 'Produk berhasil disetujui dan ditayangkan.', type: 'success');
    }

    public function startReject(): void
    {
        $this->isRejecting = true;
    }

    public function cancelReject(): void
    {
        $this->isRejecting = false;
        $this->rejectionReason = '';
    }

    public function reject(): void
    {
        $this->validate([
            'rejectionReason' => 'required|string|max:255',
        ], [
            'rejectionReason.required' => 'Alasan penolakan wajib diisi.',
            'rejectionReason.max' => 'Alasan penolakan maksimal 255 karakter.',
        ]);

        if (! $this->selectedProductId) {
            return;
        }

        $product = Product::findOrFail($this->selectedProductId);

        $product->update([
            'status' => 'rejected',
            'rejection_reason' => $this->rejectionReason,
            'approved_at' => null,
            'approved_by' => null,
        ]);

        activity('product')
            ->performedOn($product)
            ->causedBy(auth()->user())
            ->event('product_rejected')
            ->log("Produk {$product->name} ditolak. Alasan: {$this->rejectionReason}");

        $this->showDetailModal = false;
        $this->isRejecting = false;
        $this->rejectionReason = '';
        $this->selectedProductId = null;
        $this->dispatch('toast', message: 'Produk telah ditolak.', type: 'success');
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'filterStatus', 'filterSeller', 'filterCategory']);
        $this->filterStatus = 'pending';
        $this->resetPage();
    }

    private function hasActiveFilter(): bool
    {
        return $this->search !== ''
            || $this->filterStatus !== 'pending'
            || $this->filterSeller !== ''
            || $this->filterCategory !== '';
    }

    #[Layout('layouts.dashboard')]
    #[Title('Persetujuan Produk')]
    public function render()
    {
        $status = in_array($this->filterStatus, ['pending', 'rejected'], true)
            ? $this->filterStatus
            : 'pending';

        $query = Product::query()
            ->with(['category', 'productable', 'seller', 'approvedBy']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('short_description', 'like', "%{$this->search}%")
                    ->orWhereHas('seller', function ($sq) {
                        $sq->where('name', 'like', "%{$this->search}%");
                    });
            });
        }

        $query->where('status', $status);

        if ($this->filterSeller) {
            $query->where('seller_id', $this->filterSeller);
        }

        if ($this->filterCategory) {
            $query->where('category_id', $this->filterCategory);
        }

        $products = $query->latest()->paginate(15);
        $sellers = User::query()
            ->whereIn('id', Product::query()
                ->whereIn('status', ['pending', 'rejected'])
                ->whereNotNull('seller_id')
                ->select('seller_id')
                ->distinct())
            ->orderBy('name')
            ->get(['id', 'name']);
        $categories = Category::query()
            ->whereHas('products', fn ($q) => $q->whereIn('status', ['pending', 'rejected']))
            ->orderBy('name')
            ->get(['id', 'name']);
        $selectedProduct = $this->selectedProductId ? Product::with(['category', 'productable', 'seller', 'tags'])->find($this->selectedProductId) : null;

        return view('livewire.admin.product.approval', [
            'products' => $products,
            'sellers' => $sellers,
            'categories' => $categories,
            'selectedProduct' => $selectedProduct,
            'hasActiveFilter' => $this->hasActiveFilter(),
            'title' => 'Persetujuan Produk',
            'subTitle' => 'Tinjau produk yang diajukan oleh penjual',
        ]);
    }
}
