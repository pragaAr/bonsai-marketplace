# Pengajuan Menjadi Seller

## Tujuan

Memungkinkan pengguna biasa untuk mengajukan diri menjadi penjual di marketplace.

## Status Implementasi

Sudah diimplementasikan sepenuhnya.

## Alur

1. Pengguna yang sudah login membuka halaman `/seller/apply`.
2. Sistem menampilkan form dengan field: nama toko, nama pemilik, kota, provinsi, nomor WhatsApp, dan catatan tambahan.
3. Pengajuan disimpan ke model `SellerRequest` dengan status `pending`.
4. Admin meninjau permintaan dari halaman `/admin/seller/request`.
5. Jika disetujui, user mendapatkan role `seller` melalui `syncRoles(['seller'])`.
6. Jika ditolak, admin mengisi alasan penolakan yang tersimpan di kolom `rejection_reason`.
7. Seller yang sudah disetujui dapat dibekukan (status `banned`) oleh admin — role dikembalikan ke `user`.

## Status Pengajuan

| Status | Keterangan |
|---|---|
| `pending` | Menunggu review admin |
| `approved` | Disetujui, user mendapat role seller |
| `rejected` | Ditolak dengan alasan tertulis |
| `banned` | Seller dibekukan, role dikembalikan ke user |

## Catatan Implementasi

- Komponen pengajuan: `App\Livewire\SellerApply`.
- Route: `GET /seller/apply` dengan middleware `can-apply-seller` untuk mencegah duplikasi pengajuan.
- Data pengajuan disimpan di model `SellerRequest`.
- Komponen review admin: `App\Livewire\Admin\Seller\Request`.
- Route admin: `GET /admin/seller/request` dengan nama `admin.seller.request`.
- Setiap aksi (setujui, tolak, banned, hapus) dicatat melalui Spatie Activitylog.
- Akses halaman review hanya untuk role `admin` dan `system_admin`.
