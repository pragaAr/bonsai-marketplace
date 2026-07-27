# Manajemen Produk Seller

## Tujuan

Memungkinkan penjual yang sudah disetujui untuk mengelola produk yang dijual di marketplace.

## Status Implementasi

Sudah diimplementasikan sepenuhnya.

## Fitur yang Tersedia

- Menambahkan produk baru melalui form multi-step (3 langkah).
- Mengedit data produk yang sudah ada.
- Mengunggah gambar produk (maks. 4 gambar, format jpeg/png/webp, maks. 2MB per file).
- Menghapus gambar lama secara individual saat mengedit.
- Melihat status persetujuan produk (draft, pending, approved, rejected).
- Menghapus produk (beserta relasi polimorfik dan gambar terkait).
- Melihat detail produk melalui modal tanpa berpindah halaman.
- Filter daftar produk berdasarkan status.
- Pencarian produk berdasarkan nama, deskripsi singkat, atau deskripsi.

## Alur Form Multi-Step

1. **Langkah 1 — Informasi Dasar**: nama, harga, stok, deskripsi singkat, deskripsi lengkap, kategori.
2. **Langkah 2 — Detail Spesifik**: atribut sesuai kategori yang dipilih (lihat tabel di bawah).
3. **Langkah 3 — Gambar Produk**: upload satu atau lebih gambar (minimal 1, maksimal 4).

Setiap langkah divalidasi sebelum berpindah ke langkah berikutnya.

## Detail Spesifik per Kategori

| Kategori | Field Tambahan |
|---|---|
| `tanaman` | species (pilih/buat baru), care level, kebutuhan cahaya, kebutuhan air, ukuran pot |
| `pot` | bahan, bentuk, dimensi, warna |
| `media-tanam` | tipe media, berat, volume |
| `pupuk` | tipe pupuk, formulasi, berat |
| `alat` | bahan, merek, berat |

## Catatan Implementasi

- Komponen Livewire: `App\Livewire\Seller\Products` (daftar) dan `App\Livewire\Seller\ProductForm` (form).
- Route: `GET /seller/products` (daftar), `GET /seller/products/create` (buat), `GET /seller/products/{id}/edit` (edit).
- Produk dikelola melalui model `Product` dengan relasi polimorfik ke detail model.
- Gambar produk disimpan di media collection `images` melalui Spatie Media Library (disk `public`, maks. 4 file).
- Status produk saat simpan dapat dipilih antara `pending` (kirim untuk review) atau `draft` (simpan dulu).
- Jika produk dikirim ulang setelah ditolak dengan status `pending`, kolom `rejection_reason` direset ke `null`.
- Penghapusan produk otomatis menghapus relasi polimorfik detail dan semua gambar melalui Spatie Media Library.
- Setiap aksi (buat, edit, hapus) dicatat melalui Spatie Activitylog dengan event `product_created`, `product_updated`, `product_deleted`.
- Akses halaman ini hanya untuk user dengan role `seller`.
