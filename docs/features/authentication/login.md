# Login

## Tujuan

Memungkinkan pengguna terdaftar untuk masuk ke aplikasi dengan akun mereka.

## Status Implementasi

Sudah diimplementasikan sepenuhnya.

## Alur

1. Pengguna membuka halaman `/login`.
2. Sistem menampilkan form email dan password.
3. Setelah submit, Laravel memvalidasi kredensial.
4. Jika valid, pengguna diarahkan ke:
   - `/admin/dashboard` jika memiliki role `admin` atau `system_admin`.
   - `/seller/dashboard` jika memiliki role `seller`.
   - `/` (landing page) untuk role `user`.

## Catatan Implementasi

- Komponen Livewire: `App\Livewire\Auth\Login`.
- Route: `GET /login` dengan middleware `guest`.
- Menggunakan autentikasi bawaan Laravel.
- Session diregenerasi setelah login berhasil.
- Akses halaman tertentu dibatasi dengan middleware dan Spatie Permission.

## Kebutuhan Keamanan

- Password tidak disimpan dalam bentuk plaintext.
- Session diregenerasi setelah login berhasil.
- Halaman login hanya bisa diakses oleh tamu (middleware `guest`).
