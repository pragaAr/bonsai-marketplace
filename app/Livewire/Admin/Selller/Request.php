<?php

namespace App\Livewire\Admin\Selller;

use App\Models\SellerRequest;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Request extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'status')]
    public string $filterStatus = '';

    public bool $showFilterModal = false;

    public bool $showDetailModal = false;

    public ?int $selectedRequestId = null;

    public bool $isRejecting = false;

    public string $rejectionReason = '';

    public bool $showBanModal = false;

    public ?int $banId = null;

    public bool $showDeleteModal = false;

    public ?int $deleteId = null;

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

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function openDetail(int $id): void
    {
        $this->selectedRequestId = $id;
        $this->rejectionReason = '';
        $this->isRejecting = false;
        $this->showDetailModal = true;
    }

    public function approve(): void
    {
        $request = SellerRequest::with('user')->findOrFail($this->selectedRequestId);

        if ($request->status === 'approved') {
            $this->dispatch('toast', message: 'Permintaan sudah disetujui sebelumnya.', type: 'warning');

            return;
        }

        $request->update([
            'status' => 'approved',
            'rejection_reason' => null,
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
        ]);

        $request->user->syncRoles(['seller']);

        activity('seller_request')
            ->performedOn($request)
            ->causedBy(auth()->user())
            ->event('seller_approved')
            ->log("Permintaan seller {$request->store_name} disetujui oleh admin");

        $this->showDetailModal = false;
        $this->dispatch('toast', message: 'Permintaan menjadi penjual berhasil disetujui.', type: 'success');
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

        $request = SellerRequest::with('user')->findOrFail($this->selectedRequestId);

        $request->update([
            'status' => 'rejected',
            'rejection_reason' => $this->rejectionReason,
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
        ]);

        activity('seller_request')
            ->performedOn($request)
            ->causedBy(auth()->user())
            ->event('seller_rejected')
            ->log("Permintaan seller {$request->store_name} ditolak. Alasan: {$this->rejectionReason}");

        $this->showDetailModal = false;
        $this->isRejecting = false;
        $this->rejectionReason = '';
        $this->dispatch('toast', message: 'Permintaan menjadi penjual ditolak.', type: 'success');
    }

    public function confirmBan(int $id): void
    {
        $this->banId = $id;
        $this->showBanModal = true;
    }

    public function ban(): void
    {
        if (! $this->banId) {
            return;
        }

        $request = SellerRequest::with('user')->findOrFail($this->banId);

        $request->update([
            'status' => 'banned',
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
        ]);

        $request->user->syncRoles(['user']);

        activity('seller_request')
            ->performedOn($request)
            ->causedBy(auth()->user())
            ->event('seller_banned')
            ->log("Seller {$request->store_name} dibekukan (Banned) oleh admin");

        $this->showBanModal = false;
        $this->banId = null;
        $this->dispatch('toast', message: 'Seller berhasil dibekukan.', type: 'success');
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if (! $this->deleteId) {
            return;
        }

        $request = SellerRequest::with('user')->findOrFail($this->deleteId);

        // If they had seller role, remove it
        if ($request->status === 'approved') {
            $request->user->syncRoles(['user']);
        }

        activity('seller_request')
            ->performedOn($request)
            ->causedBy(auth()->user())
            ->event('seller_request_deleted')
            ->log("Data pengajuan seller {$request->store_name} dihapus oleh admin");

        $request->delete();

        $this->showDeleteModal = false;
        $this->deleteId = null;
        $this->dispatch('toast', message: 'Data pengajuan berhasil dihapus.', type: 'success');
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
    #[Title('Permintaan Penjual')]
    public function render()
    {
        $query = SellerRequest::query()->with(['user', 'reviewer']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('store_name', 'like', "%{$this->search}%")
                    ->orWhere('owner_name', 'like', "%{$this->search}%")
                    ->orWhere('whatsapp', 'like', "%{$this->search}%")
                    ->orWhereHas('user', function ($uq) {
                        $uq->where('name', 'like', "%{$this->search}%")
                            ->orWhere('email', 'like', "%{$this->search}%");
                    });
            });
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        return view('livewire.admin.seller.request', [
            'requests' => $query->latest()->paginate(15),
            'selectedRequest' => $this->selectedRequestId ? SellerRequest::with(['user', 'reviewer'])->find($this->selectedRequestId) : null,
            'title' => 'Permintaan Penjual',
            'subTitle' => 'Kelola pendaftaran dan status kemitraan penjual',
            'hasActiveFilter' => $this->hasActiveFilter(),
        ]);
    }
}
