# Todo Proyek Bonsai Marketplace

Dokumen ini berfungsi sebagai acuan prioritas pengembangan dan pelengkapan fitur proyek.

## Prioritas 1 — Fondasi dan Dokumentasi

- [x] Menyusun dokumentasi arsitektur aplikasi
- [x] Menyusun dokumentasi konvensi pengembangan
- [x] Menyusun dokumentasi deployment
- [x] Menyusun ADR untuk keputusan penting
- [x] Menyusun dokumentasi fitur utama
- [ ] Menjaga dokumen tetap sinkron dengan perubahan kode
- [ ] Menambahkan diagram alur inti jika dibutuhkan

## Prioritas 2 — Autentikasi dan Pengguna

- [x] Login manual
- [x] Login Google / OAuth
- [x] Register pengguna
- [ ] Verifikasi email pengguna
- [ ] Reset password
- [ ] Perbaikan alur profil pengguna dan upload avatar
- [ ] Validasi dan pengelolaan data profil yang lebih lengkap

## Prioritas 3 — Seller dan Produk

- [x] Pengajuan menjadi seller
- [x] Review seller oleh admin
- [x] Role dan permission dasar
- [ ] CRUD produk lengkap untuk seller
- [ ] Approval produk oleh admin
- [ ] Dashboard seller yang lebih lengkap
- [ ] Manajemen gambar produk dan media koleksi yang lebih matang
- [ ] Validasi input produk dan error handling

## Prioritas 4 — Katalog dan Konten Publik

- [x] Halaman landing page
- [x] Halaman detail produk
- [x] Halaman shop / koleksi produk
- [x] Halaman about
- [x] Halaman artikel
- [ ] Filter pencarian dan sorting produk yang lebih baik
- [ ] Kategori produk yang lebih terstruktur
- [ ] Wishlist pengguna
- [ ] Fitur pencarian full-text / pencarian lanjutan

## Prioritas 5 — Order dan Transaksi

- [x] Keranjang belanja dasar
- [x] Checkout sederhana
- [ ] Integrasi order database yang lebih nyata
- [ ] Riwayat pesanan yang lebih lengkap
- [ ] Integrasi pembayaran resmi
- [ ] Status pesanan dan workflow fulfillment
- [ ] Notifikasi order ke seller dan pembeli

## Prioritas 6 — Admin dan Operasional

- [x] Dashboard admin dasar
- [x] Pengelolaan role dan permission
- [x] Pengelolaan permintaan seller
- [ ] Dashboard admin yang lebih informatif
- [ ] Laporan penjualan / laporan admin
- [ ] Export data produk dan pengguna
- [ ] Audit log yang lebih mudah dipantau
- [ ] Pengaturan konten dan modul admin lanjutan

## Prioritas 7 — Kualitas, Testing, dan Keamanan

- [ ] Menambah unit test dan feature test
- [ ] Menutup celah keamanan pada fitur sensitif
- [ ] Menambah validasi input di semua form
- [ ] Menambahkan handling error yang konsisten
- [ ] Menjaga log aktivitas tetap relevan dan tidak berlebihan

## Prioritas 8 — Deployment dan Production Readiness

- [x] Dokumentasi deployment dasar
- [x] Langkah deploy ke Hostinger / subdomain
- [ ] Setup CI/CD sederhana jika tersedia
- [ ] Automasi backup database
- [ ] Monitoring error dan log produksi
- [ ] Optimasi cache dan asset production
- [ ] Menyusun checklist deploy berulang

## Rekomendasi Urutan Pengerjaan

1. Selesaikan CRUD produk seller dan approval admin.
2. Lengkapi alur order dan pembayaran.
3. Perkuat admin dashboard dan laporan.
4. Tambah test dan hardening.
5. Siapkan deployment dan monitoring produksi.
