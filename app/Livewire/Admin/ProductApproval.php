<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ProductApproval extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'status')]
    public string $filterStatus = 'pending'; // Default to pending queue

    public bool $showDetailModal = false;

    public ?int $selectedProductId = null;

    public bool $isRejecting = false;

    public string $rejectionReason = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterStatus(): void
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
        if (!$this->selectedProductId) {
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

        if (!$this->selectedProductId) {
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
        $this->reset(['search', 'filterStatus']);
        $this->filterStatus = 'pending';
        $this->resetPage();
    }

    private function hasActiveFilter(): bool
    {
        return $this->search !== '' || $this->filterStatus !== 'pending';
    }

    #[Layout('layouts.dashboard')]
    #[Title('Persetujuan Produk')]
    public function render()
    {
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

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        $products = $query->latest()->paginate(15);
        $selectedProduct = $this->selectedProductId ? Product::with(['category', 'productable', 'seller', 'tags'])->find($this->selectedProductId) : null;

        return view('livewire.admin.product-approval', [
            'products' => $products,
            'selectedProduct' => $selectedProduct,
            'hasActiveFilter' => $this->hasActiveFilter(),
        ]);
    }
}
