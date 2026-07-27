# Alamat Pengguna

## Tujuan

Mengelola data alamat pengguna yang digunakan saat checkout.

## Status Implementasi

Sudah diimplementasikan sebagai bagian dari profil pengguna (field tunggal).

## Fitur yang Tersedia

- Menyimpan dan mengedit alamat pengiriman utama dari halaman profil.
- Alamat otomatis diisi ke form checkout saat pengguna melakukan pemesanan.

## Catatan Implementasi

- Alamat disimpan sebagai kolom `address` (string) di tabel `users`.
- Tidak ada tabel alamat terpisah — sistem saat ini menggunakan satu alamat per pengguna.
- Multi-address (lebih dari satu alamat per pengguna) belum diimplementasikan.
- Untuk mengedit alamat, pengguna menggunakan halaman profil (`GET /profile`).
