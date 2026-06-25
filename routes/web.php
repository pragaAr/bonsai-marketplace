<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Livewire\About;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Article;
use App\Livewire\ArticleDetail;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\CareGuide;
use App\Livewire\Checkout;
use App\Livewire\LandingPage;
use App\Livewire\ProductDetail;
use App\Livewire\Profile;
use App\Livewire\ProfileOrders;
use App\Livewire\Seller\Dashboard as SellerDashboard;
use App\Livewire\SellerApply;
use App\Livewire\Shop;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — Bonsai Marketplace
|--------------------------------------------------------------------------
*/

// ============================================================
// Halaman publik
// ============================================================
Route::get('/', LandingPage::class)->name('home');

Route::get('/shop', Shop::class)->name('shop');
Route::get('/shop/product/{slug}', ProductDetail::class)->name('product.detail');

Route::get('/about', About::class)->name('about');

Route::get('/care-guide', CareGuide::class)->name('care-guide');

Route::get('/article', Article::class)->name('article');
Route::get('/article/{slug}', ArticleDetail::class)->name('article.detail');

// ============================================================
// Autentikasi (guest only)
// ============================================================
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');

    // Google OAuth
    Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('auth.google.callback');
});

// ============================================================
// Logout (auth only)
// ============================================================
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('home');
})->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/profile', Profile::class)->name('profile');
    Route::get('/profile/orders', ProfileOrders::class)->name('profile.orders');
    Route::get('/seller/apply', SellerApply::class)->name('seller.apply');
    Route::get('/checkout', Checkout::class)->name('checkout');
    Route::get('/checkout/{slug}', Checkout::class)->name('checkout.product');
});

// ============================================================
// Route Seller — harus login, role seller
// ============================================================
Route::prefix('seller')
    ->middleware(['auth', 'role:seller'])
    ->name('seller.')
    ->group(function () {
        Route::get('/dashboard', SellerDashboard::class)->name('dashboard');
        // Add more seller routes here later
    });

// ============================================================
// Route Admin — harus login, role system_admin atau admin
// ============================================================
Route::prefix('admin')
    ->middleware(['auth', 'role:system_admin|admin'])
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
        // Add more admin routes here later
    });
