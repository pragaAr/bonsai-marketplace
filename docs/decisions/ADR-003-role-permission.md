# ADR-003: Pengelolaan Role dan Permission

- Status: Accepted
- Tanggal: 2026-07-09

## Konteks

Aplikasi marketplace ini memiliki beberapa jenis pengguna dengan hak akses berbeda, seperti pembeli, penjual, admin, dan super admin. Kebutuhan keamanan dan fleksibilitas pengaturan akses menjadi sangat penting karena fitur admin dan seller akan terus berkembang.

## Keputusan

Kami menggunakan Spatie Permission untuk mengelola role dan permission. Struktur akses yang dipakai adalah:

- Role `user` untuk pembeli umum.
- Role `seller` untuk penjual yang sudah disetujui.
- Role `admin` dan `system_admin` untuk area administrasi.
- Permission dibuat dengan pola `modul.aksi`, misalnya `products.view`, `products.approve`, dan `users.manage`.
- Akses administratif dikelola melalui panel Livewire, termasuk pengaturan role dan permission per user.

## Dampak

- Akses aplikasi menjadi lebih aman dan mudah dikendalikan.
- Administrasi pengguna dan otorisasi dapat dilakukan tanpa mengubah kode secara manual.
- Perlu disiplin dalam mendefinisikan permission agar tidak terlalu luas atau terlalu sempit.
