<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Register extends Component
{
    // Data seller
    public string $name = '';

    public string $email = '';

    public string $whatsapp = '';

    public string $address = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function register()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:20',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'address' => 'required|string',
        ];

        $messages = [
            'name.required' => 'Nama wajib diisi.',
            'whatsapp.required' => 'Nomor WhatsApp wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 8 karakter.',
            'address.required' => 'Alamat wajib diisi.',
        ];

        $this->validate($rules, $messages);

        $user = User::create([
            'name' => $this->name,
            'whatsapp' => $this->whatsapp,
            'email' => $this->email,
            'password' => $this->password,
            'address' => $this->address,
        ]);

        $user->assignRole('user');

        activity('user_registration')
            ->performedOn($user)
            ->causedBy($user)
            ->event('register')
            ->log("User {$user->name} mendaftar");

        session()->flash('success', 'Pendaftaran berhasil! Silahkan login untuk melanjutkan.');

        return redirect()->route('login');
    }

    #[Layout('layouts.guest')]
    #[Title('Daftar - Bonsaiku')]
    public function render()
    {
        return view('livewire.auth.register');
    }
}
