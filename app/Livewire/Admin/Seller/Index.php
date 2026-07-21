<?php

namespace App\Livewire\Admin\Seller;

use App\Models\SellerRequest;
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

    #[Url(as: 'status')]
    public string $filterStatus = 'approved';

    public bool $showFilterModal = false;

    public bool $showBanModal = false;

    public ?int $banId = null;

    public function openFilter(): void
    {
        $this->showFilterModal = true;
    }

    public function filterList(): void
    {
        $this->normalizeStatus();
        $this->showFilterModal = false;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterStatus(): void
    {
        $this->normalizeStatus();
        $this->resetPage();
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

        $request = SellerRequest::with('user')
            ->where('status', 'approved')
            ->findOrFail($this->banId);

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

    public function unban(int $id): void
    {
        $request = SellerRequest::with('user')
            ->where('status', 'banned')
            ->findOrFail($id);

        $request->update([
            'status' => 'approved',
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
        ]);

        $request->user->syncRoles(['seller']);

        activity('seller_request')
            ->performedOn($request)
            ->causedBy(auth()->user())
            ->event('seller_unbanned')
            ->log("Seller {$request->store_name} diaktifkan kembali oleh admin");

        $this->dispatch('toast', message: 'Seller berhasil diaktifkan kembali.', type: 'success');
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'filterStatus']);
        $this->filterStatus = 'approved';
        $this->resetPage();
        $this->dispatch('filter-reset');
    }

    private function normalizeStatus(): void
    {
        if (! in_array($this->filterStatus, ['approved', 'banned'], true)) {
            $this->filterStatus = 'approved';
        }
    }

    private function hasActiveFilter(): bool
    {
        return $this->search !== '' || $this->filterStatus !== 'approved';
    }

    #[Layout('layouts.dashboard')]
    #[Title('Daftar Seller')]
    public function render()
    {
        $this->normalizeStatus();

        $query = SellerRequest::query()
            ->where('status', $this->filterStatus)
            ->with('user');

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

        return view('livewire.admin.seller.index', [
            'sellers' => $query->latest()->paginate(15),
            'title' => 'Daftar Seller',
            'subTitle' => 'Kelola seller yang telah disetujui dan dibekukan',
            'hasActiveFilter' => $this->hasActiveFilter(),
        ]);
    }
}
