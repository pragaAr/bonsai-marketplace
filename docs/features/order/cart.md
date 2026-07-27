# Keranjang Belanja

## Tujuan

Memungkinkan pengguna menampung produk yang ingin dibeli sebelum melakukan checkout.

## Status Implementasi

Sudah diimplementasikan. Keranjang menggunakan session Laravel.

## Fitur yang Tersedia

- Menambahkan produk ke keranjang (membutuhkan login; non-login mendapat notifikasi toast).
- Melihat isi keranjang melalui drawer yang muncul dari sisi layar.
- Mengubah kuantitas item atau menghapus item individual.
- Mengosongkan seluruh keranjang sekaligus.
- Menampilkan subtotal harga secara real-time.
- Download invoice keranjang dalam format PDF menggunakan Barryvdh DomPDF.
- Akses cepat ke halaman checkout dari dalam drawer.

## Catatan Implementasi

- Komponen Livewire: `App\Livewire\CartDrawer` (drawer) dan `App\Livewire\CartBadge` (badge jumlah item di navbar).
- Data keranjang disimpan di session Laravel (key `cart`), bukan di database.
- Nomor invoice dibuat otomatis dengan format `INV-{tanggal}-{random 4 karakter}`.
- Penambahan item ke keranjang dicatat melalui Spatie Activitylog dengan event `cart_added`.
- Download invoice dicatat dengan event `invoice_downloaded`.
- Keranjang bersifat sementara — data hilang jika sesi berakhir atau cart dikosongkan saat checkout.
