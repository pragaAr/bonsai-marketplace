<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\GoogleAvatarCache;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
  /**
   * Redirect ke halaman consent Google OAuth.
   */
  public function redirect()
  {
    return Socialite::driver('google')->redirect();
  }

  /**
   * Handle callback dari Google OAuth.
   */
  public function callback()
  {
    try {
      $googleUser = Socialite::driver('google')->user();
    } catch (\Exception $e) {
      \Log::error('Google Login Error: '.$e->getMessage(), [
        'exception' => $e,
      ]);
      session()->flash('error', 'Login dengan Google gagal. Detail: '.$e->getMessage());

      return redirect()->route('login');
    }

    // Cari user berdasarkan google_id terlebih dahulu
    $user = User::where('google_id', $googleUser->getId())->first();

    if ($user) {
      // User sudah pernah login via Google sebelumnya — selalu update URL avatar
      // dengan URL terbaru dari Google (URL bisa berubah antar sesi OAuth).
      // GoogleAvatarCache akan pakai URL ini saat re-download jika file lokal hilang.
      $user->update([
        'avatar' => $googleUser->getAvatar(),
      ]);
    } else {
      // Cari berdasarkan email (mungkin sudah daftar manual sebelumnya)
      $user = User::where('email', $googleUser->getEmail())->first();

      if ($user) {
        // Akun email sudah ada — hubungkan dengan Google
        $user->update([
          'google_id' => $googleUser->getId(),
          'avatar'    => $googleUser->getAvatar(),
        ]);
      } else {
        // Buat akun baru dari Google
        $user = User::create([
          'name'              => $googleUser->getName(),
          'email'             => $googleUser->getEmail(),
          'google_id'         => $googleUser->getId(),
          'avatar'            => $googleUser->getAvatar(),
          'email_verified_at' => now(),
          'password'          => null,
        ]);

        // Assign role user biasa
        $user->assignRole('user');

        activity('user_registration')
          ->performedOn($user)
          ->causedBy($user)
          ->event('register')
          ->log("User {$user->name} mendaftar via Google");
      }
    }

    app(GoogleAvatarCache::class)->cache($user, $googleUser->getAvatar());

    Auth::login($user, true);
    session()->regenerate();

    // Redirect berdasarkan role
    if ($user->hasAnyRole(['system_admin', 'admin'])) {
      return redirect()->intended(route('admin.dashboard'));
    }

    if ($user->hasRole('seller')) {
      return redirect()->intended(route('seller.dashboard'));
    }

    return redirect()->intended('/');
  }
}
