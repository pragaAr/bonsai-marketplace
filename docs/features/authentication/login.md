# Login

## Tujuan

Memungkinkan pengguna terdaftar untuk masuk ke aplikasi dengan akun mereka.

## Alur

1. Pengguna membuka halaman login.
2. Sistem menampilkan form email dan password.
3. Setelah submit, Laravel memvalidasi kredensial.
4. Jika valid, pengguna diarahkan ke halaman yang sesuai dengan role.

## Catatan Implementasi

- Menggunakan autentikasi bawaan Laravel.
- Halaman login dikelola melalui Livewire.
- Setelah login, sistem memeriksa role pengguna untuk redirect.

## Kebutuhan Keamanan

- Password tidak disimpan dalam bentuk plaintext.
- Session diregenerasi setelah login berhasil.
- Akses halaman tertentu dibatasi dengan middleware dan permission.
