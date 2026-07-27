# Detail Produk

## Tujuan

Menampilkan informasi lengkap suatu produk bonsai dan mendukung keputusan pembelian pengguna.

## Status Implementasi

Sudah diimplementasikan sepenuhnya.

## Fitur yang Tersedia

- Menampilkan nama, harga, deskripsi singkat, deskripsi lengkap, dan kategori produk.
- Menampilkan gambar produk dari media library (slider jika lebih dari satu gambar).
- Menampilkan detail spesifik sesuai kategori:
  - Tanaman: spesies, care level, kebutuhan cahaya, kebutuhan air, ukuran pot.
  - Pot: bahan, bentuk, dimensi, warna.
  - Media tanam: tipe, berat, volume.
  - Pupuk: tipe, formulasi, berat.
  - Alat: bahan, merek, berat.
- Menampilkan status stok (tersedia / habis).
- Tombol tambah ke keranjang dan akses langsung ke checkout produk tunggal.
- Menampilkan informasi seller (nama toko).

## Catatan Implementasi

- Komponen Livewire: `App\Livewire\ProductDetail`.
- Route: `GET /shop/product/{slug}` dengan nama `product.detail`.
- Produk diambil berdasarkan slug dengan eager loading relasi `category`, `productable`, `seller`, `tags`, `media`.
- Jika media tidak tersedia, sistem menampilkan fallback image berdasarkan peta slug → nama file.
- Hanya produk dengan status `approved` yang dapat diakses publik.
