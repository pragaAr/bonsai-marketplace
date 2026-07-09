# ADR-001: Alur Login dan Registrasi Pengguna

- Status: Accepted
- Tanggal: 2026-07-09

## Konteks

Marketplace bonsai membutuhkan proses masuk yang cepat, aman, dan ramah pengguna. Pengguna bisa datang dari login manual, login Google, atau pendaftaran awal melalui OAuth. Setelah masuk, sistem juga perlu mengarahkan pengguna ke area yang sesuai dengan perannya, misalnya pembeli, penjual, atau admin.

## Keputusan

Kami menggunakan Laravel Authentication bawaan yang dipadukan dengan Laravel Socialite untuk login Google. Alur login ditentukan sebagai berikut:

1. Pengguna melakukan login melalui Google OAuth.
2. Sistem mencari akun berdasarkan `google_id` terlebih dahulu, lalu fallback ke `email` bila akun sudah pernah dibuat sebelumnya.
3. Jika akun belum ada, sistem membuat akun baru, memberi role `user`, dan mencatat aktivitas registrasi.
4. Setelah login berhasil, sistem mengarahkan pengguna ke dashboard admin, seller, atau halaman utama berdasarkan role yang dimiliki.
5. Avatar Google di-cache ke storage lokal melalui Spatie Media Library agar tampil konsisten dan lebih stabil.

## Dampak

- Proses masuk menjadi lebih sederhana untuk pengguna dan lebih cepat untuk diadopsi.
- Akun bisa terhubung antar metode login tanpa membuat duplikasi data.
- Pengalihan ke halaman yang sesuai menjadi lebih konsisten karena sistem mengandalkan role.
- Kebutuhan integrasi OAuth dan penanganan error login menjadi bagian yang harus dijaga.
