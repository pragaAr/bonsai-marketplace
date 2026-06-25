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

      return redirect()->intended(route('admin.dashboard'));
    }

    if ($user->hasRole('seller')) {
      session()->regenerate();

      return redirect()->intended(route('seller.dashboard'));
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
