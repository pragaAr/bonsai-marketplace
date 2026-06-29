# bonsaiku - Bonsai Marketplace

`bonsaiku` adalah proyek marketplace komunitas bonsai berbasis Laravel. Aplikasi ini sedang dalam tahap pengembangan: storefront publik sudah berjalan cukup jauh, sementara dashboard admin/seller dan workflow commerce penuh masih bertahap.

Tujuan proyek ini adalah membangun pengalaman belanja bonsai yang minimal, tenang, dan product-led, lalu berkembang menjadi marketplace dengan seller, approval produk, order history, payment, dan shipping.

## Status Proyek

Belum 100% selesai. Saat ini proyek sudah memiliki fondasi berikut:

- Storefront publik: landing page, shop, detail produk, about, care guide, artikel/journal.
- Auth: login/register manual dan Google OAuth.
- Role dasar: `system_admin`, `admin`, `seller`, `user`.
- Cart berbasis session.
- Mock checkout dan download invoice PDF.
- Media produk/artikel dan avatar profil user via Spatie Media Library.
- Activity logging untuk beberapa aktivitas penting.
- Profile user: form terpadu edit data, foto profil, dan ganti password.
- Layout dashboard admin/seller sudah ada, tetapi fitur operasionalnya belum lengkap.

Fitur besar yang masih direncanakan:

- Tombol dan flow "Jadi Penjual".
- History pembelian.
- Order persistence.
- Admin CRUD user, role, permission, dan access matrix.
- Approval produk seller.
- Seller dashboard dan CRUD produk.
- Payment gateway / transfer manual.
- Shipping, kemungkinan integrasi KiriminAja.

## Stack Teknologi

- PHP 8.2+
- Laravel 12
- Livewire 4
- Blade
- Alpine.js
- Tailwind CSS 4
- Vite 7
- MySQL untuk database lokal saat ini
- Spatie Laravel Permission
- Spatie Laravel Media Library
- Spatie Laravel Activitylog
- Laravel Socialite untuk Google OAuth
- Barryvdh DomPDF untuk invoice PDF
- Maatwebsite Excel
- PHPUnit
- Laravel Pint

## Fitur yang Sudah Ada

### Storefront

- Landing page.
- Katalog produk di `/shop`.
- Search, filter kategori, dan sort produk via query URL.
- Detail produk di `/shop/product/{slug}`.
- Artikel/journal.
- Care guide.
- About page.
- Header responsive dengan mobile menu.
- Search overlay.
- Cart badge dan cart drawer.
- Toast notification.

### Auth dan Role

- Login dan register berbasis Livewire.
- Google OAuth.
- Logout.
- Redirect dashboard berdasarkan role.
- Middleware role untuk admin dan seller.

### Profile User

- Halaman profil di `/profile` (auth-only).
- Form terpadu: edit nama, email, WhatsApp, alamat, foto profil, dan ganti password dalam satu form.
- Akun Google (login via OAuth tanpa password lokal) hanya diizinkan mengubah WhatsApp dan alamat.
- Banner peringatan otomatis muncul jika WhatsApp atau alamat belum diisi.
- Foto profil mendukung dua sumber:
  - **Avatar Google**: diambil otomatis saat login via Google OAuth.
  - **Avatar kustom**: upload file JPG/JPEG/PNG, maksimal 2MB, disimpan via Spatie Media Library.
- Tombol "Gunakan Foto Google" tersedia bagi pengguna Google yang sudah upload foto kustom.
- Avatar pada navbar (desktop & mobile) langsung reaktif saat foto profil diperbarui, tanpa perlu refresh halaman (menggunakan Alpine.js event bus `avatar-updated`).
- Path penyimpanan avatar: `users/{user_id}/profile/{media_id}` (custom path generator via Spatie Media Library).

### Produk dan Artikel

- Produk punya seller ownership.
- Produk punya field approval: `status`, `rejection_reason`, `approved_at`, `approved_by`.
- Produk dan artikel mendukung media image.
- Fallback image tersedia dari `public/images`.
- Seeder menyediakan sample produk dan artikel.

### Cart dan Invoice

- Cart tersimpan di session.
- User harus login untuk add-to-cart.
- Quantity cart dapat diubah.
- Item cart dapat dihapus.
- Checkout masih mock.
- Invoice PDF bisa diunduh dari cart.

## Struktur Folder Penting

```text
app/
  Http/Controllers/Auth/GoogleController.php
  Livewire/
    Admin/
    Auth/
    Profile.php          ← Komponen halaman profil user
    Seller/
  Models/
  Services/
    UserProfilePathGenerator.php  ← Custom path generator avatar

database/
  migrations/
  seeders/

resources/
  css/
  js/
  views/
    layouts/
    livewire/
      profile.blade.php  ← View halaman profil

routes/
  web.php

public/
  css/
  images/
  js/
```

## Route Utama

- `/` - landing page
- `/shop` - katalog produk
- `/shop/product/{slug}` - detail produk
- `/about` - halaman tentang
- `/care-guide` - panduan perawatan
- `/article` - daftar artikel
- `/article/{slug}` - detail artikel
- `/login` - login
- `/register` - register
- `/auth/google` - redirect Google OAuth
- `/auth/google/callback` - callback Google OAuth
- `/profile` - halaman profil user (auth-only)
- `/seller/dashboard` - dashboard seller
- `/admin/dashboard` - dashboard admin

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

Pastikan database MySQL sudah dibuat dan `.env` mengarah ke koneksi MySQL, misalnya `DB_CONNECTION=mysql`. Lalu jalankan migration dan seeder:

```bash
php artisan migrate --seed
```

Jalankan development server:

```bash
composer run dev
```

Alternatif jika ingin menjalankan server terpisah:

```bash
php artisan serve
npm run dev
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

## Akun Seeder

Seeder membuat beberapa akun default untuk development:

- `sysadmin@bonsaiku.com`
- `admin@bonsaiku.com`
- `seller@bonsaiku.com`
- `user@bonsaiku.com`

Password default dari seeder: `password`.

Gunakan akun ini hanya untuk development lokal.

## Catatan Pengembangan

- Jangan commit `.env`, database lokal, cache, log, `vendor`, `node_modules`, `public/build`, atau file runtime dari `storage`.
- Cart saat ini belum berbasis order permanen.
- History pembelian belum bisa dibuat dengan benar sampai tabel order/order item tersedia.
- Storefront sebaiknya hanya menampilkan produk dengan status `approved` ketika workflow approval mulai dipakai penuh.
- Seller panel perlu dibuat setelah flow seller dan approval produk lebih jelas.
- Admin CRUD role/permission harus hati-hati karena menyentuh akses aplikasi.

## Roadmap Singkat

1. ✅ ~~Buat profile user.~~
2. Tambah persistence order dan order item.
3. Ubah checkout mock menjadi membuat order.
4. Buat history pembelian user.
5. Buat flow request menjadi seller.
6. Buat admin product approval.
7. Buat seller product CRUD.
8. Lengkapi admin CRUD user, role, permission, dan access matrix.
9. Integrasi payment dan shipping.

## Lisensi

Proyek ini dibangun di atas Laravel. Status lisensi proyek aplikasi belum ditentukan secara eksplisit.
