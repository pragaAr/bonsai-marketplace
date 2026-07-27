# ADR-006: Polymorphic Relation untuk Detail Produk

- Status: Accepted
- Tanggal: 2026-07-11

## Konteks

Marketplace menjual berbagai jenis produk (tanaman bonsai, pot, media tanam, pupuk, alat) yang masing-masing memiliki atribut spesifikasi yang berbeda-beda. Menggabungkan semua atribut ke satu tabel `products` akan menghasilkan banyak kolom nullable dan schema yang sulit dikelola.

## Keputusan

Menggunakan **polymorphic relation** (`morphTo`) untuk menyimpan detail spesifik produk ke tabel terpisah per kategori.

Setiap kategori memiliki model detail sendiri:

| Kategori (slug) | Model Detail | Tabel |
|---|---|---|
| `tanaman` | `PlantDetail` | `plant_details` |
| `pot` | `PotDetail` | `pot_details` |
| `media-tanam` | `MediaDetail` | `media_details` |
| `pupuk` | `FertilizerDetail` | `fertilizer_details` |
| `alat` | `ToolDetail` | `tool_details` |

Model `Product` memiliki relasi `productable()` yang mengarah ke salah satu model di atas melalui kolom `productable_id` dan `productable_type`.

## Alasan

- Struktur tabel `products` tetap generik dan bersih.
- Setiap jenis produk punya atribut sendiri tanpa kolom nullable yang berlebihan.
- Mudah dikembangkan jika kategori bertambah (cukup tambah model dan tabel baru).
- Validasi form dapat dilakukan secara dinamis berdasarkan slug kategori.

## Dampak

- Query perlu menggunakan eager loading (`with(['productable'])`) untuk menghindari N+1.
- Khusus `PlantDetail`, relasi `species` perlu di-lazy load secara terpisah karena tidak ada di model detail lain (mencegah `RelationNotFoundException`).
- Saat seller mengganti kategori produk saat edit, detail lama harus dihapus sebelum detail baru dibuat.
- Filter/search berdasarkan atribut spesifik (misalnya species tertentu) membutuhkan join ke tabel detail yang relevan.
