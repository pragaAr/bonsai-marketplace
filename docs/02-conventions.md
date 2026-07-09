# Konvensi Pengembangan

## Bahasa dan Format

- Dokumentasi utama ditulis dalam bahasa Indonesia.
- Nama class, method, dan variabel menggunakan bahasa Inggris dengan gaya camelCase atau PascalCase sesuai standar PHP/Laravel.
- Komentar dibuat singkat, jelas, dan hanya saat diperlukan.

## Struktur Kode

- Gunakan namespace sesuai domain, misalnya `App\Livewire`, `App\Models`, `App\Services`.
- File terkait fitur sebaiknya disimpan dekat dengan domain-nya.
- Hindari logika bisnis yang terlalu panjang di view; pindahkan ke component atau service.

## Naming Convention

- Controller: `NamaController`.
- Livewire component: `NamaKomponen` dengan file `NamaKomponen.php`.
- Model: `NamaModel`.
- Migration: `xxxx_xx_xx_xxxxxx_create_nama_table.php`.
- Route name: gunakan nama yang deskriptif, misalnya `seller.dashboard`, `admin.users`.

## Gaya Penulisan PHP

- Gunakan PSR-12.
- Selalu gunakan `declare(strict_types=1);` bila memungkinkan.
- Prioritaskan sintaks modern PHP 8.2.
- Gunakan tipe return dan parameter bila relevan.

## Livewire dan Blade

- Komponen Livewire dipakai untuk fitur interaktif.
- Blade digunakan untuk markup statis dan layout.
- Hindari menempatkan logika kompleks langsung di Blade.

## Database

- Migration harus idempotent dan dapat dijalankan berulang.
- Nama kolom disusun singkat, konsisten, dan deskriptif.
- Gunakan foreign key dan constraint bila sesuai.

## Git dan Commit

- Commit message disarankan singkat dan deskriptif.
- Perubahan besar sebaiknya dibagi per fitur atau domain.
- Jangan menggabungkan perubahan yang tidak terkait dalam satu commit.

## Testing

- Tambahkan test untuk fitur penting seperti autentikasi, akses role, pengajuan seller, dan logika produk.
- Jalankan test sebelum menutup perubahan yang berdampak besar.
