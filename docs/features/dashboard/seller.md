# Dashboard Seller

## Tujuan

Memberikan panel operasional sederhana untuk penjual yang sudah disetujui.

## Status Implementasi

Sudah diimplementasikan (dashboard dasar).

## Fitur yang Tersedia

- Halaman awal bagi seller setelah login.
- Akses cepat ke manajemen produk (daftar produk, tambah produk, edit produk).
- Menampilkan status approval produk secara langsung dari daftar produk.

## Catatan Implementasi

- Komponen Livewire: `App\Livewire\Seller\Dashboard`.
- Route: `GET /seller/dashboard` dengan nama `seller.dashboard`.
- Akses halaman ini hanya untuk user dengan role `seller`.
- Layout menggunakan `layouts.dashboard`.
- Untuk fitur manajemen produk lengkap, lihat [product-management.md](product-management.md).
