# Login dengan Google

## Tujuan

Memudahkan pengguna masuk menggunakan akun Google (OAuth 2.0).

## Status Implementasi

Sudah diimplementasikan sepenuhnya.

## Alur

1. Pengguna memilih tombol login Google.
2. Sistem mengarahkan ke halaman OAuth Google melalui `/auth/google`.
3. Setelah pengguna menyetujui akses, Google mengarahkan kembali ke `/auth/google/callback`.
4. Sistem mencari akun berdasarkan `google_id` terlebih dahulu, lalu fallback ke `email`.
5. Jika akun belum ada, akun baru dibuat dan diberi role `user`, serta aktivitas registrasi dicatat.
6. Avatar Google di-cache ke storage lokal melalui `GoogleAvatarCache` service menggunakan Spatie Media Library.
7. Pengguna diarahkan berdasarkan role (admin, seller, atau user biasa).

## Catatan Implementasi

- Controller: `App\Http\Controllers\Auth\GoogleController`.
- Route: `GET /auth/google` (redirect) dan `GET /auth/google/callback` (callback), keduanya dengan middleware `guest`.
- Menggunakan Laravel Socialite untuk integrasi OAuth Google.
- Avatar disimpan ke media collection `avatar` di storage lokal melalui `App\Services\GoogleAvatarCache`.
- Variabel environment yang diperlukan: `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, `GOOGLE_REDIRECT_URI`.
