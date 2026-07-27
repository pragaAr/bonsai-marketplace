# Pembayaran

## Tujuan

Menangani proses pembayaran setelah checkout selesai.

## Status Implementasi

**Belum diimplementasikan.** Saat ini checkout hanya menghasilkan mock order tanpa integrasi gateway pembayaran nyata. Pilihan metode pembayaran tersedia di form checkout sebagai placeholder.

## Metode yang Direncanakan

- Rekening bersama (biaya layanan Rp 10.000)
- QRIS
- COD (Cash on Delivery)

## Catatan Implementasi

- Integrasi pembayaran resmi (Midtrans, Xendit, atau sejenisnya) belum ada.
- Untuk status mock order saat ini, lihat [checkout.md](checkout.md).
- Setelah integrasi pembayaran tersedia, status pesanan perlu diperbarui otomatis berdasarkan callback dari gateway.
