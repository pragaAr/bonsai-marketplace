# Profil Pengguna

## Tujuan

Memungkinkan pengguna melihat dan mengelola informasi akun mereka.

## Status Implementasi

Sudah diimplementasikan sepenuhnya.

## Fitur yang Tersedia

- Menampilkan dan mengedit data profil: nama, email, alamat, nomor WhatsApp.
- Upload dan ganti foto profil (avatar) melalui file upload.
- Mengubah password (khusus pengguna yang tidak login hanya melalui Google).
- Menampilkan status seller (pending, approved, rejected, banned) beserta alasan penolakan jika ada.
- Akses cepat ke halaman riwayat pesanan.

## Catatan Implementasi

- Komponen Livewire: `App\Livewire\Profile`.
- Route: `GET /profile` dengan nama `profile`, middleware `auth`.
- Avatar disimpan ke media collection `avatar` melalui Spatie Media Library.
- Pengguna yang hanya login lewat Google (`google_id` ada, password kosong) tidak ditampilkan form ubah password.
- Pengguna dapat menghapus avatar custom dan kembali ke avatar Google atau default.
- Status seller diambil dari relasi `sellerRequest` pada model `User`.
