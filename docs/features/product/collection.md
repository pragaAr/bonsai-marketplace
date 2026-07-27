# Koleksi Produk (Shop)

## Tujuan

Menampilkan kumpulan produk dalam bentuk katalog yang mudah diakses pengguna.

## Status Implementasi

Sudah diimplementasikan sepenuhnya.

## Fitur yang Tersedia

- Menampilkan daftar produk yang sudah disetujui (`status = approved`) dalam grid.
- Filter berdasarkan kategori produk.
- Pencarian produk berdasarkan nama.
- Menampilkan gambar produk, harga, nama, dan kategori.
- Akses ke halaman detail produk dari tiap card.
- Tombol tambah ke keranjang langsung dari listing.

## Catatan Implementasi

- Komponen Livewire: `App\Livewire\Shop`.
- Route: `GET /shop` dengan nama `shop`.
- Hanya produk dengan status `approved` yang ditampilkan ke publik.
- Gambar produk diambil dari media collection `images`; jika tidak ada, menggunakan fallback image.
- Produk dengan status lain (`draft`, `pending`, `rejected`) tidak muncul di halaman shop.
