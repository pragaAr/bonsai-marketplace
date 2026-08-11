# Todo Proyek Bonsai Marketplace

Dokumen ini berfungsi sebagai acuan prioritas pengembangan dan pelengkapan fitur proyek.

## Prioritas 1 — Fondasi dan Dokumentasi

- [x] Menyusun dokumentasi arsitektur aplikasi
- [x] Menyusun dokumentasi konvensi pengembangan
- [x] Menyusun dokumentasi deployment
- [x] Menyusun ADR untuk keputusan penting
- [x] Menyusun dokumentasi fitur utama
- [x] Menjaga dokumen tetap sinkron dengan perubahan kode
- [ ] Menambahkan diagram alur inti jika dibutuhkan

## Prioritas 2 — Autentikasi dan Pengguna

- [x] Login manual
- [x] Login Google / OAuth
- [x] Register pengguna
- [x] Verifikasi email pengguna
- [ ] Reset password
- [x] Upload dan ganti avatar profil
- [x] Validasi dan pengelolaan data profil yang lebih lengkap ?

## Prioritas 3 — Seller dan Produk

- [x] Pengajuan menjadi seller
- [x] Review seller oleh admin (approve, reject, ban, delete)
- [x] Role dan permission dasar (Spatie Permission)
- [x] CRUD produk lengkap untuk seller (form multi-step)
- [x] Approval produk oleh admin (approve / reject dengan alasan)
- [x] Dashboard seller (dasar)
- [x] Manajemen gambar produk (upload, hapus individual, maks. 4 gambar)
- [x] Validasi input produk per kategori dan error handling
- [x] Modal detail produk di daftar produk seller
- [ ] Dashboard seller yang lebih informatif (statistik, ringkasan penjualan)

## Prioritas 4 — Katalog dan Konten Publik

- [x] Halaman landing page dengan produk featured
- [x] Halaman detail produk (termasuk detail spesifik per kategori)
- [x] Halaman shop / koleksi produk
- [x] Halaman about
- [x] Halaman artikel (daftar + detail)
- [x] Halaman care guide
- [x] Filter pencarian berdasarkan kategori di halaman shop
- [ ] Filter dan sorting produk yang lebih lengkap (harga, stok, dsb.)
- [ ] Wishlist pengguna
- [ ] Fitur pencarian full-text / pencarian lanjutan

## Prioritas 5 — Order dan Transaksi

- [x] Keranjang belanja berbasis session
- [x] Checkout sederhana (mock order)
- [x] Download invoice PDF dari keranjang
- [ ] Integrasi order database yang nyata (simpan ke tabel orders)
- [ ] Riwayat pesanan yang menampilkan data nyata
- [ ] Integrasi pembayaran resmi (Midtrans, Xendit, atau sejenisnya)
- [ ] Status pesanan dan workflow fulfillment
- [ ] Notifikasi order ke seller dan pembeli

## Prioritas 6 — Admin dan Operasional

- [x] Dashboard admin dasar
- [x] Pengelolaan role dan permission
- [x] Pengelolaan permintaan seller (approve, reject, ban, delete)
- [x] Pengelolaan produk seller (approval, daftar produk approved)
- [x] Master data: kategori produk, tag, species, kategori artikel
- [x] Daftar semua seller aktif
- [ ] Dashboard admin yang lebih informatif (statistik sistem)
- [ ] Laporan penjualan / laporan admin
- [ ] Export data produk dan pengguna (Maatwebsite Excel sudah tersedia)
- [ ] Audit log yang lebih mudah dipantau
- [ ] Pengaturan konten dan modul admin lanjutan

## Prioritas 7 — Kualitas, Testing, dan Keamanan

- [ ] Menambah unit test dan feature test
- [ ] Menutup celah keamanan pada fitur sensitif
- [ ] Menambah validasi input di semua form
- [x] Handling error yang konsisten (toast notification)
- [x] Activity log untuk aksi penting (produk, seller, cart, checkout)

## Prioritas 8 — Deployment dan Production Readiness

- [x] Dokumentasi deployment dasar
- [x] Langkah deploy ke Hostinger / subdomain
- [ ] Setup CI/CD sederhana
- [ ] Automasi backup database
- [ ] Monitoring error dan log produksi
- [ ] Optimasi cache dan asset production
- [ ] Menyusun checklist deploy berulang

## Rekomendasi Urutan Pengerjaan

1. Implementasikan order database nyata dan riwayat pesanan.
2. Lengkapi alur pembayaran (integrasi gateway).
3. Perkuat admin dashboard dengan statistik dan laporan.
4. Tambah test dan hardening keamanan.
5. Siapkan deployment dan monitoring produksi.
