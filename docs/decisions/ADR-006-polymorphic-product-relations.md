# ADR-006: Polymorphic Relation Product

- Status: Accepted
- Tanggal: 2026-07-11

## Konteks

Handling atribut/spesifikasi yang berbeda-beda pada produk berdasaarkan kategori nya

## Keputusan

Menggunakan polymorphic relation untuk detail produk.

Alasan:

- Struktur products tetap generik.
- Setiap jenis produk punya atribut sendiri.
- Mudah dikembangkan jika kategori bertambah.

## Dampak

- Search/filter sedikit lebih kompleks.
- Harus eager loading relasi.
