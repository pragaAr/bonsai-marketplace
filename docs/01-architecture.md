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
   - Middleware dan policy digunakan untuk membatasi akses sesuai role.

3. Data dan Storage
   - MySQL digunakan sebagai database utama.
   - File media disimpan melalui Spatie Media Library ke disk publik.
   - Activity log disimpan untuk audit dan pemantauan perubahan.

## Struktur Folder Utama

- app/Http/Controllers: controller klasik jika diperlukan.
- app/Livewire: komponen UI berbasis Livewire untuk halaman publik, profil, checkout, dan admin.
- app/Models: model domain utama seperti User, Product, SellerRequest, dan JournalEntry.
- app/Services: service untuk kebutuhan khusus seperti path generator media dan cache avatar Google.
- routes/web.php: definisi route utama untuk halaman publik, seller, dan admin.
- resources/views: template Blade untuk tampilan halaman.
- database/migrations: skema database.
- tests: pengujian fitur dan unit.

## Alur Fitur Utama

### 1. Autentikasi dan Login

- Login manual menggunakan sistem auth Laravel.
- Login Google menggunakan Laravel Socialite.
- Setelah login, pengguna diarahkan berdasarkan role.

### 2. Marketplace Publik

- Pengunjung dapat melihat landing page, katalog produk, detail produk, artikel, dan panduan perawatan.
- Produk dapat dipromosikan melalui fitur featured.

### 3. Seller Flow

- Pengguna dapat mengajukan diri menjadi penjual.
- Admin meninjau permintaan seller.
- Setelah disetujui, akun mendapat akses role seller.

### 4. Admin Area

- Admin dapat mengelola user, role, permission, seller request, dan dashboard.
- Pengelolaan akses ditangani dengan Spatie Permission.

## Prinsip Arsitektur

- Gunakan Livewire untuk fitur interaktif yang cukup kompleks, tetapi tidak terlalu berat.
- Pisahkan logika bisnis ke service bila mulai kompleks.
- Simpan file media melalui Media Library agar konsisten.
- Pastikan semua akses penting dikontrol berdasarkan role dan permission.

## Catatan Pengembangan

Saat menambah fitur, prioritaskan:

- kemudahan maintenance,
- konsistensi route dan naming,
- reuse komponen Livewire,
- test coverage untuk fitur penting.
