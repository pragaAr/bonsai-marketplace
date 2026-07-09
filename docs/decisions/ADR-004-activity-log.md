# ADR-004: Pencatatan Aktivitas dan Audit Log

- Status: Accepted
- Tanggal: 2026-07-09

## Konteks

Marketplace membutuhkan jejak aktivitas untuk memantau perubahan penting, terutama pada produk, registrasi pengguna, dan aksi bisnis lain yang berpengaruh pada data utama. Hal ini penting untuk audit, debugging, dan pemantauan operasional.

## Keputusan

Kami menggunakan Spatie Activitylog untuk mencatat perubahan dan aktivitas penting. Implementasi yang dipilih adalah:

- Model produk menggunakan logging otomatis untuk perubahan data penting.
- Log hanya mencatat field yang berubah agar catatan tetap ringkas.
- Aktivitas non-model seperti registrasi pengguna dan aksi tertentu dicatat melalui helper `activity()`.
- Setiap log dikaitkan dengan entity yang dikenai perubahan dan actor yang melakukannya bila tersedia.

## Dampak

- Memudahkan audit terhadap perubahan produk dan aktivitas pengguna.
- Membantu tim memantau kejadian penting saat proses bisnis berjalan.
- Perlu dikelola agar log tidak terlalu banyak dan tetap bermanfaat untuk kebutuhan operasional.
