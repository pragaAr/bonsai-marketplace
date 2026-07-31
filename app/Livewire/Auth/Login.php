<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|min:6',
    ];

    protected $messages = [
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
        'password.required' => 'Password wajib diisi.',
        'password.min' => 'Password minimal 6 karakter.',
    ];

    public function mount(): void
    {
        $redirect = url()->previous();

        // Simpan URL halaman asal ke session. Jangan menyimpan halaman auth
        // agar URL /login tidak menjadi tujuan redirect berikutnya.
        if ($this->isLocalUrl($redirect) && ! $this->isAuthUrl($redirect)) {
            session()->put('url.intended', $redirect);
        }
    }

    protected function isLocalUrl(string $url): bool
    {
        $parts = parse_url($url);

        if ($parts === false) {
            return false;
        }

        // URL relatif lokal, misalnya /shop/product/...
        if (! isset($parts['host'])) {
            return isset($parts['path'])
                && str_starts_with($parts['path'], '/')
                && ! str_starts_with($parts['path'], '//');
        }

        // URL absolut harus tetap berada pada host aplikasi.
        return $parts['host'] === request()->getHost()
            && (! isset($parts['port']) || $parts['port'] === request()->getPort());
    }

    protected function isAuthUrl(string $url): bool
    {
        $path = parse_url($url, PHP_URL_PATH);

        return in_array($path, ['/login', '/register', '/auth/google', '/auth/google/callback'], true);
    }

    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }

    public function login()
    {
        $this->validate();

        if (RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            $seconds = RateLimiter::availableIn($this->throttleKey());
            session()->flash('error', "Terlalu banyak percobaan login. Silakan coba lagi dalam {$seconds} detik.");

            return;
        }

        $user = User::where('email', $this->email)->first();

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());
            session()->flash('error', 'Email atau password salah.');

            return;
        }

        RateLimiter::clear($this->throttleKey());
        /** @var User $user */
        $user = Auth::user();

        // Cek Role via Spatie Permission
        if ($user->hasAnyRole(['system_admin', 'admin'])) {
            session()->regenerate();

            return redirect()->route('admin.dashboard');
        }

        if ($user->hasRole('seller')) {
            session()->regenerate();

            return redirect()->route('seller.dashboard');
        }

        // Fallback jika tidak punya role yang jelas
        session()->regenerate();

        return redirect()->intended('/');
    }

    #[Layout('layouts.guest')]
    #[Title('Login')]
    public function render()
    {
        return view('livewire.auth.login');
    }
}
