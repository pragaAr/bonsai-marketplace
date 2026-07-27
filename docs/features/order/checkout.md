# Checkout

## Tujuan

Memproses pembelian produk dari keranjang atau langsung dari detail produk.

## Status Implementasi

Sudah diimplementasikan sebagai **mock checkout** — pesanan dibuat dan dicatat, namun belum terintegrasi dengan sistem pembayaran nyata dan tidak disimpan ke tabel orders di database.

## Alur

1. Pengguna masuk ke halaman checkout:
   - Dari keranjang: semua item di session cart diproses sekaligus.
   - Dari detail produk: langsung ke checkout produk tunggal via `/checkout/{slug}`.
2. Sistem menampilkan form nama penerima, nomor WhatsApp, alamat pengiriman, catatan, dan pilihan metode pembayaran.
3. Data profil pengguna (nama, WhatsApp, alamat) diisi otomatis jika sudah tersimpan.
4. Setelah submit, order number dibuat secara lokal (format `ORD-{tanggal}-{random}`).
5. Pesanan dicatat melalui Spatie Activitylog dengan event `mock_order_created`.
6. Session cart dikosongkan setelah checkout dari keranjang.

## Metode Pembayaran yang Tersedia

| Opsi | Biaya Layanan |
|---|---|
| `rekening_bersama` | Rp 10.000 |
| `qris` | Gratis |
| `cod` | Gratis |

## Catatan Implementasi

- Komponen Livewire: `App\Livewire\Checkout`.
- Route: `GET /checkout` (dari keranjang) dan `GET /checkout/{slug}` (produk langsung), keduanya dengan middleware `auth`.
- Pesanan **belum disimpan ke database** — ini adalah mock order. Integrasi database order adalah bagian pengembangan selanjutnya.
- Subtotal = harga × kuantitas; total = subtotal + biaya layanan.
