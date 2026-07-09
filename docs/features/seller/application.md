# Pengajuan Menjadi Seller

## Tujuan

Memungkinkan pengguna biasa untuk mengajukan diri menjadi penjual di marketplace.

## Alur

1. Pengguna masuk ke halaman pengajuan seller.
2. Sistem menampilkan form data toko, nama pemilik, kota, provinsi, nomor WhatsApp, dan catatan.
3. Pengajuan disimpan dengan status `pending`.
4. Admin meninjau permintaan tersebut.
5. Jika disetujui, user mendapatkan role seller.

## Catatan Implementasi

- Data pengajuan disimpan di model `SellerRequest`.
- Status review bisa `pending`, `approved`, `rejected`, atau `banned`.
- Proses review dikelola oleh admin.
