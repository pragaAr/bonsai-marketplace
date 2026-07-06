# bonsaiku - Bonsai Marketplace

bonsaiku adalah marketplace bonsai berbasis Laravel 12 dan Livewire 4 yang sedang berkembang dari storefront publik menjadi platform commerce dengan role-based access.

## Status Proyek

Proyek ini sudah berada di tahap marketplace inti. Fitur publik, autentikasi, profil user, serta alur penjual awal sudah tersedia, sementara dashboard operasional dan commerce lanjutan masih dalam pengembangan.

### Yang sudah tersedia

- Storefront publik: landing page, shop, detail produk, about, care guide, artikel/journal.
- Auth: login/register manual dan Google OAuth.
- Role dasar: system_admin, admin, seller, user.
- Profil user: edit data, foto profil, ganti password, dan banner isi data kontak.
- Alur “Jadi Penjual”: form pengajuan via SellerRequest dan route /seller/apply.
- Area user: halaman profil dan halaman history pembelian awal di /profile/orders.
- Admin access & role management: halaman CRUD lengkap untuk /admin/roles, /admin/permissions, dan /admin/users.
- Cart session, checkout mock, dan download invoice PDF.
- Media produk/artikel/avatar user via Spatie Media Library.
- Activity logging untuk beberapa event penting.

- Order persistence dan purchase history yang benar-benar tersimpan.
- Seller dashboard operasional.
- Admin seller request approval (sedang dikerjakan / file diinisialisasi).
- Approval produk seller.
- Payment gateway dan shipping.

## Stack Teknologi

- PHP 8.2+
- Laravel 12
- Livewire 4
- Blade
- Alpine.js
- Tailwind CSS 4
- Vite 7
- MySQL
- Spatie Laravel Permission
- Spatie Laravel Media Library
- Spatie Laravel Activitylog
- Laravel Socialite
- Barryvdh DomPDF
- Maatwebsite Excel
- PHPUnit
- Laravel Pint

## Route Utama

- / - landing page
- /shop - katalog produk
- /shop/product/{slug} - detail produk
- /about - halaman tentang
- /care-guide - panduan perawatan
- /article - daftar artikel
- /article/{slug} - detail artikel
- /login - login
- /register - register
- /auth/google - redirect Google OAuth
- /auth/google/callback - callback Google OAuth
- /profile - halaman profil user
- /profile/orders - halaman history pembelian awal
- /seller/apply - form pengajuan menjadi seller
- /seller/dashboard - dashboard seller
- /admin/dashboard - dashboard admin
- /admin/roles - manajemen role
- /admin/permissions - manajemen permission
- /admin/users - manajemen user
- /admin/seller/request - approval pengajuan seller (inisialisasi / sedang dikerjakan)

## Setup Lokal

Install dependency PHP:

```bash
composer install
```

Install dependency frontend:

```bash
npm install
```

Siapkan environment:

```bash
cp .env.example .env
php artisan key:generate
```

Pastikan database MySQL sudah dibuat dan .env mengarah ke koneksi MySQL. Lalu jalankan migrasi dan seeder:

```bash
php artisan migrate --seed
```

Jalankan development server:

```bash
composer run dev
```

## Perintah Umum

Build frontend:

```bash
npm run build
```

Jalankan test:

```bash
composer test
```

Format kode PHP:

```bash
vendor/bin/pint
```

Clear cache Laravel:

```bash
php artisan optimize:clear
```

## Catatan Pengembangan

- Jangan commit .env, database lokal, cache, log, vendor, node_modules, public/build, atau file runtime dari storage.
- Checkout saat ini masih mock dan belum menyimpan order permanen.
- Alur jadi penjual sudah ada di level form/request, tetapi approval admin belum jadi panel operasional.
- Storefront sebaiknya hanya menampilkan produk approved ketika workflow approval dipakai penuh.
- Admin CRUD role/permission harus hati-hati karena menyentuh akses aplikasi.

5. Buat flow request menjadi seller (Selesai).
6. Lengkapi admin CRUD user, role, dan permission (Selesai).
7. Buat admin seller request approval (Inisialisasi / Sedang dikerjakan).
8. Buat admin product approval.
9. Buat seller product CRUD.
10. Integrasi payment dan shipping.

## Lisensi

Proyek ini dibangun di atas Laravel. Status lisensi proyek aplikasi belum ditentukan secara eksplisit.
