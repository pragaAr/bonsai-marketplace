# ADR-002: Penggunaan Media Library untuk File Gambar

- Status: Accepted
- Tanggal: 2026-07-09

## Konteks

Aplikasi ini menangani berbagai jenis media, terutama avatar pengguna dan gambar produk bonsai. Media perlu disimpan secara terstruktur, mudah diakses, dan aman dari broken image akibat path yang tidak konsisten.

## Keputusan

Kami menggunakan Spatie Media Library sebagai solusi utama untuk mengelola file media. Media dikelola pada disk `public` dan dipetakan ke folder yang terstruktur dengan custom path generator.

Penerapan yang dipilih:

- Avatar pengguna disimpan di collection `avatar` dengan batas satu file.
- Gambar produk disimpan di collection `images` dan dibatasi maksimal 4 gambar per produk.
- Hanya format gambar tertentu yang diterima, yaitu JPEG, PNG, dan WebP.
- Sistem memakai fallback image bila media tidak tersedia.
- Path penyimpanan dibuat khusus agar media user dan produk tidak tercampur.

## Dampak

- Media lebih terorganisir dan mudah dikelola dari satu layer yang konsisten.
- Aplikasi lebih siap untuk kebutuhan upload gambar dan fitur pengelolaan konten di masa depan.
- Perlu diperhatikan validasi file, kebersihan storage, dan penanganan file yang hilang.
