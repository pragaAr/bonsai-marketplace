# Register

## Tujuan

Memungkinkan pengguna baru membuat akun sebagai pembeli atau calon penjual.

## Alur

1. Pengguna membuka halaman register.
2. Sistem menerima data nama, email, password, dan informasi lain yang diperlukan.
3. Akun dibuat dan disimpan ke database.
4. Pengguna otomatis diberi role default `user`.

## Catatan Implementasi

- Fitur register tersedia sebagai halaman Livewire.
- Email yang sama tidak boleh diduplikasi.
- Proses pendaftaran dapat dikembangkan lebih lanjut untuk verifikasi email.
