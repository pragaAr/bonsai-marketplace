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

    public string $whatsapp = '';

    public string $notes = '';

    public bool $agreement = false;

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
            $this->whatsapp = $request->whatsapp;
            $this->notes = $request->notes;
            $this->agreement = $request->agreement;
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
                'whatsapp' => ['required', 'string', 'max:50'],
                'notes' => ['required', 'string', 'max:255'],
                'agreement' => ['required', 'boolean'],
            ],
            [
                'store_name.required' => 'Nama toko wajib diisi.',
                'owner_name.required' => 'Nama pemilik wajib diisi.',
                'province_name.required' => 'Provinsi wajib diisi.',
                'city_name.required' => 'Kota wajib diisi.',
                'whatsapp.required' => 'WhatsApp aktif wajib diisi.',
                'notes.required' => 'Beri sedikit catatan untuk toko anda',
                'agreement.required' => 'Anda harus menyetujui syarat dan ketentuan.',
            ]
        );

        $user = Auth::user();

        $sellerRequest = SellerRequest::updateOrCreate(
            [
                'user_id' => $user->id,
            ],
            [
                'store_name' => $validated['store_name'],
                'owner_name' => $validated['owner_name'],
                'province_name' => $validated['province_name'],
                'city_name' => $validated['city_name'],
                'agreement' => $validated['agreement'],
                'whatsapp' => $validated['whatsapp'],
                'notes' => $validated['notes'],
                'status' => 'pending',
            ]
        );

        activity('seller_request')
            ->performedOn($sellerRequest)
            ->causedBy($user)
            ->event('seller_application')
            ->log("User {$user->name} mengajukan menjadi seller");

        session()->flash(
            'success',
            'Pengajuan menjadi penjual berhasil dikirim. Silakan tunggu konfirmasi dari admin'
        );

        $this->redirectRoute('profile');
    }

    #[Layout('layouts.app')]
    #[Title('Jadi Penjual')]
    public function render()
    {
        return view('livewire.seller-apply');
    }
}
