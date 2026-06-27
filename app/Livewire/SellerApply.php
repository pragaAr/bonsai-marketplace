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

    public string $owner_name = '';

    public string $city_name = '';

    public string $province_name = '';

    public string $description = '';

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
            $this->owner_name = $request->owner_name;
            $this->province_name = $request->province_name;
            $this->city_name = $request->city_name;
            $this->description = $request->description;
            $this->whatsapp = $request->whatsapp;
            $this->notes = $request->notes ?? '';
        }
    }

    public function submit(): void
    {
        $validated = $this->validate(
            [
                'store_name' => ['required', 'string', 'max:255'],
                'owner_name' => ['required', 'string', 'max:255'],
                'province_name' => ['required', 'string', 'max:255'],
                'city_name' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string', 'max:1000'],
                'whatsapp' => ['required', 'string', 'max:50'],
                'notes' => ['nullable', 'string', 'max:1000'],
            ],
            [
                'store_name.required' => 'Nama toko wajib diisi.',
                'owner_name.required' => 'Nama pemilik wajib diisi.',
                'province_name.required' => 'Nama provinsi wajib diisi.',
                'city_name.required' => 'Nama kota wajib diisi.',
                'whatsapp.required' => 'Nomor WhatsApp wajib diisi.',
            ]
        );

        SellerRequest::updateOrCreate(
            [
                'user_id' => Auth::id(),
            ],
            [
                'store_name' => $validated['store_name'],
                'owner_name' => $validated['owner_name'],
                'province_name' => $validated['province_name'],
                'city_name' => $validated['city_name'],
                'description' => $validated['description'] ?? null,
                'whatsapp' => $validated['whatsapp'],
                'notes' => $validated['notes'] ?? null,
                'status' => 'pending',
            ]
        );

        $this->dispatch('toast', message: 'Pengajuan menjadi penjual berhasil dikirim.', duration: 3000);

        $this->redirectRoute('profile');
    }

    #[Layout('layouts.app')]
    #[Title('Jadi Penjual')]
    public function render()
    {
        return view('livewire.seller-apply');
    }
}
