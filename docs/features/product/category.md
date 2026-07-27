# Kategori Produk

## Tujuan

Mengelompokkan produk berdasarkan jenis untuk memudahkan browsing dan pengelolaan spesifikasi.

## Status Implementasi

Sudah diimplementasikan. Kategori dikelola oleh admin dan digunakan pada form produk seller.

## Kategori yang Tersedia

| Slug | Nama | Detail Model |
|---|---|---|
| `tanaman` | Tanaman Bonsai | `PlantDetail` |
| `pot` | Pot | `PotDetail` |
| `media-tanam` | Media Tanam | `MediaDetail` |
| `pupuk` | Pupuk | `FertilizerDetail` |
| `alat` | Alat | `ToolDetail` |

## Fitur yang Tersedia

- Menampilkan daftar kategori produk di halaman shop sebagai filter.
- Setiap kategori menentukan atribut detail spesifik yang harus diisi seller saat membuat produk.
- Admin dapat mengelola kategori dari `/admin/master/categories`.

## Catatan Implementasi

- Model: `App\Models\Category`.
- Komponen admin: `App\Livewire\Admin\Master\ProductCategories`.
- Kategori terhubung ke model `Product` melalui relasi `belongsTo`.
- Slug kategori digunakan sebagai kunci untuk menentukan validasi dan model polimorfik yang digunakan (lihat ADR-006).
