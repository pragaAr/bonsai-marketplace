<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Livewire\About;
use App\Livewire\Admin\Access\Permission;
use App\Livewire\Admin\Access\Role;
use App\Livewire\Admin\Access\User;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\Master\ProductCategories;
use App\Livewire\Admin\Master\Species;
use App\Livewire\Admin\Master\Tags;
use App\Livewire\Admin\Product\Approval as AdminProductApproval;
use App\Livewire\Admin\Product\Index as AdminProducts;
use App\Livewire\Admin\Seller\Index as AdminSellerIndex;
use App\Livewire\Admin\Seller\Request as AdminSellerRequest;
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
use App\Livewire\Seller\ProductForm as SellerProductForm;
use App\Livewire\Seller\Products as SellerProducts;
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

    Route::middleware('can-apply-seller')->group(function () {
        Route::get('/seller/apply', SellerApply::class)->name('seller.apply');
    });

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
        Route::get('/products', SellerProducts::class)->name('products');
        Route::get('/products/create', SellerProductForm::class)->name('products.create');
        Route::get('/products/{id}/edit', SellerProductForm::class)->name('products.edit');
    });

// ============================================================
// Route Admin — harus login, role system_admin atau admin
// ============================================================
Route::prefix('admin')
    ->middleware(['auth', 'role:system_admin|admin'])
    ->name('admin.')
    ->group(function () {
        // Dashboard Admin
        Route::get('/dashboard', AdminDashboard::class)->name('dashboard');

        // Kelola user, role, permission
        Route::get('/roles', Role::class)->name('roles');
        Route::get('/permissions', Permission::class)->name('permissions');
        Route::get('/users', User::class)->name('users');

        // Master Data
        Route::get('/master/categories', ProductCategories::class)->name('master.categories');
        Route::get('/master/tags', Tags::class)->name('master.tags');
        Route::get('/master/species', Species::class)->name('master.species');

        // Route untuk kelola permintaan menjadi penjual
        Route::get('/seller/request', AdminSellerRequest::class)->name('seller.request');

        Route::get('/seller', AdminSellerIndex::class)->name('seller.index');

        // Route untuk kelola persetujuan produk
        Route::get('/products/approval', AdminProductApproval::class)->name('products.approval');

        // Produk yang telah disetujui
        Route::get('/products', AdminProducts::class)->name('products');
    });
