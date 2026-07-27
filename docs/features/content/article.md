# Artikel dan Edukasi

## Tujuan

Menyediakan konten informatif untuk pengguna, khususnya seputar bonsai, perawatan, dan komunitas.

## Status Implementasi

Sudah diimplementasikan (daftar artikel dan detail artikel tersedia).

## Fitur yang Tersedia

- Menampilkan daftar artikel yang tersedia.
- Menyediakan halaman detail artikel berdasarkan slug.
- Mendukung konten edukatif yang memperkuat nilai marketplace.

## Catatan Implementasi

- Komponen daftar: `App\Livewire\Article`.
- Komponen detail: `App\Livewire\ArticleDetail`.
- Route: `GET /article` (daftar) dan `GET /article/{slug}` (detail), keduanya dengan nama `article` dan `article.detail`.
- Admin dapat mengelola kategori artikel dari `/admin/master/categories` melalui `App\Livewire\Admin\Master\ArticleCategories`.
- Keduanya dapat diakses tanpa login (halaman publik).
