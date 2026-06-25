<?php

namespace App\Livewire;

use App\Models\SellerRequest;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class SellerApply extends Component
{
    public string $store_name = '';

    public string $whatsapp = '';

    public string $notes = '';

    public function mount(): void
    {
        if (Auth::user()->hasRole('seller')) {
            redirect()->route('seller.dashboard')->send();
        }

        $request = SellerRequest::query()->where('user_id', Auth::id())->first();

        if ($request) {
            $this->store_name = $request->store_name;
            $this->whatsapp = $request->whatsapp;
            $this->notes = $request->notes ?? '';
        }
    }

    public function submit(): void
    {
        $validated = $this->validate([
            'store_name' => ['required', 'string', 'max:255'],
            'whatsapp' => ['required', 'string', 'max:50'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'store_name.required' => 'Nama toko wajib diisi.',
            'whatsapp.required' => 'Nomor WhatsApp wajib diisi.',
        ]);

        SellerRequest::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'store_name' => $validated['store_name'],
                'whatsapp' => $validated['whatsapp'],
                'notes' => $validated['notes'] ?? null,
                'status' => 'pending',
            ]
        );

        $this->dispatch('toast', message: 'Pengajuan seller berhasil dikirim.', duration: 3000);

        $this->redirectRoute('profile');
    }

    #[Layout('layouts.app')]
    #[Title('Jadi Penjual')]
    public function render()
    {
        return view('livewire.seller-apply');
    }
}
