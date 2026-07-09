# ADR-005: Alur Pengajuan dan Persetujuan Seller

- Status: Accepted
- Tanggal: 2026-07-09

## Konteks

Agar marketplace bisa berkembang dengan kualitas yang lebih baik, proses menjadi penjual tidak boleh langsung terbuka untuk semua pengguna. Diperlukan mekanisme pengajuan seller yang dapat ditinjau oleh admin sebelum akun tersebut benar-benar bisa mengelola produk dan transaksi.

## Keputusan

Kami menerapkan alur pengajuan seller melalui model `SellerRequest` dengan status review yang jelas:

- Pengguna mengajukan permohonan menjadi seller.
- Data pengajuan disimpan dengan status `pending` untuk ditinjau admin.
- Admin dapat menyetujui, menolak, atau membekukan permohonan seller.
- Saat disetujui, akun pengguna diberikan role `seller` dan dapat mengakses fitur penjual.
- Saat ditolak atau dibekukan, sistem menyimpan alasan peninjauan untuk transparansi.

## Dampak

- Proses onboarding penjual menjadi lebih terkontrol dan aman.
- Marketplace memiliki mekanisme moderasi yang lebih baik untuk mengurangi spam dan akun tidak layak.
- Admin perlu mengelola alur review secara konsisten agar proses tetap efisien.
