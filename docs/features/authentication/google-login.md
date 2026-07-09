# Login dengan Google

## Tujuan

Memudahkan pengguna masuk menggunakan akun Google.

## Alur

1. Pengguna memilih tombol login Google.
2. Sistem mengarahkan ke OAuth Google.
3. Setelah pengguna menyetujui akses, callback diproses.
4. Sistem mencari akun berdasarkan `google_id` atau `email`.
5. Jika akun belum ada, akun baru dibuat dan diberi role `user`.
6. Avatar Google di-cache ke storage lokal.

## Catatan Implementasi

- Menggunakan Laravel Socialite.
- Avatar disimpan ke Spatie Media Library.
- Setelah berhasil login, pengguna diarahkan berdasarkan role.
