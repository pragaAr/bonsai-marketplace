# Riwayat Pesanan

## Tujuan

Memungkinkan pengguna melihat daftar pesanan yang pernah mereka buat.

## Status Implementasi

Halaman tersedia namun **belum memiliki data nyata** — karena checkout saat ini masih berbasis mock order (tidak disimpan ke database), halaman riwayat pesanan belum menampilkan data transaksi yang sebenarnya.

## Catatan Implementasi

- Komponen Livewire: `App\Livewire\ProfileOrders`.
- Route: `GET /profile/orders` dengan nama `profile.orders`, middleware `auth`.
- Halaman ini akan berisi data pesanan setelah integrasi order database selesai.
- Lihat [checkout.md](checkout.md) untuk detail status implementasi checkout saat ini.
