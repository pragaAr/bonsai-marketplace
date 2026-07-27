# Arsitektur Aplikasi

## Tujuan

Dokumen ini menjelaskan arsitektur inti dari proyek bonsaiku sebagai marketplace bonsai yang dibangun dengan Laravel 12, Livewire 4, Blade, dan Vite.

## Gambaran Umum

Aplikasi ini terbagi menjadi tiga lapisan utama:

1. Frontend UI
   - Menggunakan Blade + Livewire untuk halaman interaktif.
   - Tailwind CSS dan Alpine.js dipakai untuk styling dan interaksi ringan.
   - Vite digunakan untuk membangun asset frontend.

2. Backend Application
   - Laravel sebagai framework utama untuk routing, controller, model, service, dan autentikasi.
   - Logika bisnis utama berada di model, service, dan komponen Livewire.
   - Middleware dan role (Spatie Permission) digunakan untuk membatasi akses.

3. Data dan Storage
   - MySQL digunakan sebagai database utama.
   - File media disimpan melalui Spatie Media Library ke disk publik.
   - Activity log disimpan untuk audit dan pemantauan perubahan.

## Struktur Folder Utama

- `app/Http/Controllers`: controller klasik, saat ini hanya digunakan untuk `GoogleController`.
- `app/Livewire`: komponen UI berbasis Livewire, dikelompokkan per area:
  - `app/Livewire/Auth`: Login, Register.
  - `app/Livewire/Seller`: Dashboard, Products, ProductForm.
  - `app/Livewire/Admin/Access`: User, Role, Permission.
  - `app/Livewire/Admin/Master`: ProductCategories, Tags, Species, ArticleCategories.
  - `app/Livewire/Admin/Product`: Index, Approval.
  - `app/Livewire/Admin/Seller`: Index, Request.
  - Komponen publik: LandingPage, Shop, ProductDetail, Article, ArticleDetail, CareGuide, About, Checkout, Profile, ProfileOrders, SellerApply, CartDrawer, CartBadge.
- `app/Models`: model domain utama — User, Product, SellerRequest, Category, Tag, Species, PlantDetail, PotDetail, MediaDetail, FertilizerDetail, ToolDetail, JournalEntry.
- `app/Services`: service untuk path generator media dan cache avatar Google.
- `app/Exports`: export Excel untuk data produk atau pengguna.
- `routes/web.php`: definisi seluruh route publik, seller, dan admin.
- `resources/views`: template Blade untuk tampilan halaman.
- `database/migrations`: skema database.
- `tests`: pengujian fitur dan unit.

## Alur Fitur Utama

### 1. Autentikasi dan Login

- Login manual menggunakan sistem auth Laravel (Livewire).
- Login Google menggunakan Laravel Socialite via `GoogleController`.
- Setelah login, pengguna diarahkan ke dashboard admin, seller, atau halaman utama berdasarkan role.
- Avatar Google di-cache ke storage lokal melalui Spatie Media Library.

### 2. Marketplace Publik

- Pengunjung dapat melihat landing page, katalog produk, detail produk, artikel, care guide, dan about.
- Produk dapat ditandai sebagai unggulan melalui field `featured`.
- Keranjang belanja dan checkout tersedia untuk pengguna yang sudah login.

### 3. Seller Flow

- Pengguna dapat mengajukan diri menjadi penjual melalui halaman `/seller/apply`.
- Pengajuan disimpan di model `SellerRequest` dengan status `pending`.
- Admin meninjau permintaan dan dapat menyetujui atau menolak.
- Setelah disetujui, akun mendapat role `seller` melalui `syncRoles`.
- Seller dapat mengelola produk (CRUD) dengan form multi-step di `SellerProductForm`.

### 4. Produk dengan Polimorfik

- Setiap produk memiliki kategori (tanaman, pot, media tanam, pupuk, alat).
- Detail spesifik per kategori disimpan dalam model polimorfik: `PlantDetail`, `PotDetail`, `MediaDetail`, `FertilizerDetail`, `ToolDetail`.
- Gambar produk disimpan di media collection `images` melalui Spatie Media Library (maks. 4 gambar).
- Status produk: `draft`, `pending`, `approved`, `rejected`.

### 5. Admin Area

- Admin dapat mengelola user, role, permission, seller request, dan produk.
- Tersedia halaman persetujuan produk (`Approval`) dan daftar semua seller aktif.
- Master data: kategori produk, tag, species.
- Akses dibatasi untuk role `admin` dan `system_admin`.

## Roles yang Tersedia

| Role | Akses |
|---|---|
| `user` | Publik, profil, keranjang, checkout, pengajuan seller |
| `seller` | Semua akses user + dashboard seller dan manajemen produk |
| `admin` | Semua akses seller + area admin (kecuali pengelolaan role/permission) |
| `system_admin` | Akses penuh ke seluruh fitur termasuk pengelolaan role dan permission |

## Prinsip Arsitektur

- Gunakan Livewire untuk fitur interaktif yang cukup kompleks, tetapi tidak terlalu berat.
- Pisahkan logika bisnis ke service bila mulai kompleks.
- Simpan file media melalui Media Library agar konsisten.
- Pastikan semua akses penting dikontrol berdasarkan role dan permission (Spatie).
- Catat aktivitas penting menggunakan Spatie Activitylog.

## Catatan Pengembangan

Saat menambah fitur, prioritaskan:

- kemudahan maintenance,
- konsistensi route dan naming,
- reuse komponen Livewire,
- test coverage untuk fitur penting.
