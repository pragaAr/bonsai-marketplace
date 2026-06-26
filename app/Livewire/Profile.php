<?php

namespace App\Livewire;

use App\Models\User;
use App\Services\GoogleAvatarCache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class Profile extends Component
{
    use WithFileUploads;

    public string $name = '';

    public string $email = '';

    public string $address = '';

    public string $whatsapp = '';

    public string $avatar = '';

    public TemporaryUploadedFile|array|string|null $avatarFile = null;

    public string $password = '';

    public string $password_confirmation = '';

    public string $sellerLabel = '';

    public bool $isGoogleOnly = false;

    public bool $isSeller = false;

    public bool $showEditor = false;

    public bool $hasGoogleAvatar = false;

    public bool $hasCustomAvatar = false;

    public function mount(): void
    {
        $user = Auth::user();

        $this->name = $user->name;
        $this->email = $user->email;
        $this->address = $user->address ?? '';
        $this->whatsapp = $user->whatsapp ?? '';
        $this->avatar = $user->avatar ?? '';

        $this->isGoogleOnly = filled($user->google_id) && blank($user->password);
        $this->isSeller = $user->hasRole('seller');
        $this->sellerLabel = $this->isSeller ? 'Sudah menjadi penjual' : 'Belum menjadi penjual';

        $rawAvatar = $user->getRawOriginal('avatar');
        $this->hasGoogleAvatar = filled($user->google_id) && filled($rawAvatar) && (str_starts_with($rawAvatar, 'http://') || str_starts_with($rawAvatar, 'https://'));
        $this->hasCustomAvatar = $this->hasUserUploadedAvatar($user);
    }

    public function getUserProperty()
    {
        return Auth::user();
    }

    public function toggleEditor(): void
    {
        $this->showEditor = ! $this->showEditor;
        if (! $this->showEditor) {
            $this->mount();
            $this->password = '';
            $this->password_confirmation = '';
            $this->avatarFile = null;
        }
    }

    public function saveProfile(): void
    {
        $user = Auth::user();

        if ($this->isGoogleOnly) {
            $validated = $this->validate([
                'whatsapp' => ['nullable', 'string', 'max:20'],
                'address' => ['nullable', 'string', 'max:1000'],
            ], [
                'whatsapp.max' => 'Nomor WhatsApp maksimal 20 karakter.',
                'address.max' => 'Alamat maksimal 1000 karakter.',
            ]);

            $user->update([
                'whatsapp' => $validated['whatsapp'],
                'address' => $validated['address'],
            ]);

            $this->whatsapp = $validated['whatsapp'] ?? '';
            $this->address = $validated['address'] ?? '';
        } else {
            $rules = [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore(Auth::id())],
                'whatsapp' => ['nullable', 'string', 'max:20'],
                'address' => ['nullable', 'string', 'max:1000'],
                'avatarFile' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            ];

            if (filled($this->password)) {
                $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
            }

            $validated = $this->validate($rules, [
                'name.required' => 'Nama wajib diisi.',
                'name.max' => 'Nama maksimal 255 karakter.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah digunakan akun lain.',
                'whatsapp.max' => 'Nomor WhatsApp maksimal 20 karakter.',
                'address.max' => 'Alamat maksimal 1000 karakter.',
                'avatarFile.image' => 'File avatar harus berupa gambar.',
                'avatarFile.mimes' => 'Format avatar hanya boleh JPG, JPEG, atau PNG.',
                'avatarFile.max' => 'Ukuran avatar maksimal 2MB.',
                'password.required' => 'Password baru wajib diisi.',
                'password.min' => 'Password minimal 8 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
            ]);

            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'whatsapp' => $validated['whatsapp'],
                'address' => $validated['address'],
            ];

            if (filled($this->password)) {
                $updateData['password'] = Hash::make($this->password);
            }

            $user->update($updateData);

            if ($this->avatarFile) {
                $user->clearMediaCollection('avatar');
                $user->addMedia($this->avatarFile->getRealPath())
                    ->usingFileName($this->avatarFile->hashName())
                    ->withCustomProperties(['source' => 'user'])
                    ->toMediaCollection('avatar', 'public');
                $this->hasCustomAvatar = true;
            }

            $this->name = $validated['name'];
            $this->email = $validated['email'];
            $this->whatsapp = $validated['whatsapp'] ?? '';
            $this->address = $validated['address'] ?? '';
            $this->avatar = $user->fresh()->avatar ?? '';
            $this->password = '';
            $this->password_confirmation = '';
            $this->avatarFile = null;
        }

        $this->dispatch('toast', message: 'Profil berhasil diperbarui.', duration: 3000);
        $this->dispatch('avatar-updated', avatarUrl: $this->avatar);
        $this->showEditor = false;
    }

    public function useGoogleAvatar(): void
    {
        $user = Auth::user();
        $rawAvatar = $user->getRawOriginal('avatar');

        if (! app(GoogleAvatarCache::class)->cache($user, $rawAvatar, true)) {
            $this->dispatch('toast', message: 'Foto Google belum bisa diambil. Coba lagi beberapa saat.', duration: 3000);

            return;
        }

        $freshUser = $user->fresh();
        $this->avatar = $freshUser->avatar ?? '';
        $this->hasCustomAvatar = false;
        $this->dispatch('toast', message: 'Foto profil berhasil disinkronkan dengan foto Google.', duration: 3000);
        $this->dispatch('avatar-updated', avatarUrl: $this->avatar);
    }

    private function hasUserUploadedAvatar(User $user): bool
    {
        $media = $user->getFirstMedia('avatar');

        if (! $media) {
            return false;
        }

        return $media->getCustomProperty('source') !== 'google'
          && ! str_starts_with($media->file_name, 'google-avatar-');
    }

    #[Layout('layouts.app')]
    #[Title('Profil Saya')]
    public function render()
    {
        return view('livewire.profile');
    }
}
