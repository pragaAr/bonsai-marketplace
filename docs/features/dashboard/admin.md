# Dashboard Admin

## Tujuan

Memberikan akses admin untuk memantau dan mengelola operasi marketplace.

## Status Implementasi

Sudah diimplementasikan (dashboard dasar dan semua fitur pengelolaan).

## Fitur yang Tersedia

### Pengelolaan Seller
- Meninjau permintaan menjadi seller (`pending` / `rejected`).
- Menyetujui, menolak, membekukan (banned), atau menghapus pengajuan seller.
- Melihat daftar semua seller aktif.

### Pengelolaan Produk
- Melihat produk yang menunggu persetujuan (`pending`).
- Menyetujui atau menolak produk (dengan alasan penolakan).
- Melihat semua produk yang sudah disetujui (`approved`).

### Master Data
- Pengelolaan kategori produk (`/admin/master/categories`).
- Pengelolaan tag produk (`/admin/master/tags`).
- Pengelolaan species tanaman (`/admin/master/species`).

### Akses dan Pengguna
- Pengelolaan user (`/admin/users`).
- Pengelolaan role (`/admin/roles`).
- Pengelolaan permission (`/admin/permissions`).

## Catatan Implementasi

- Komponen dashboard admin: `App\Livewire\Admin\Dashboard`.
- Route: `GET /admin/dashboard` dengan nama `admin.dashboard`.
- Komponen terkait:
  - `App\Livewire\Admin\Seller\Request` — review pengajuan seller.
  - `App\Livewire\Admin\Seller\Index` — daftar seller aktif.
  - `App\Livewire\Admin\Product\Approval` — persetujuan produk.
  - `App\Livewire\Admin\Product\Index` — semua produk approved.
  - `App\Livewire\Admin\Master\ProductCategories`, `Tags`, `Species` — master data.
  - `App\Livewire\Admin\Access\User`, `Role`, `Permission` — manajemen akses.
- Akses dibatasi hanya untuk role `admin` dan `system_admin`.
- Layout menggunakan `layouts.dashboard`.
