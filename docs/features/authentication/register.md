# Register

## Tujuan

Memungkinkan pengguna baru membuat akun sebagai pembeli atau calon penjual.

## Status Implementasi

Sudah diimplementasikan (form registrasi manual tersedia).

## Alur

1. Pengguna membuka halaman `/register`.
2. Sistem menampilkan form nama, email, dan password.
3. Setelah submit, akun dibuat dan disimpan ke database.
4. Pengguna otomatis diberi role default `user`.
5. Pengguna diarahkan ke halaman utama setelah registrasi berhasil.

## Catatan Implementasi

- Komponen Livewire: `App\Livewire\Auth\Register`.
- Route: `GET /register` dengan middleware `guest`.
- Email yang sama tidak boleh diduplikasi (validasi unique).
- Verifikasi email belum diimplementasikan (lihat todo.md).
