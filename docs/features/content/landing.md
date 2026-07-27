# Landing Page

## Tujuan

Menampilkan halaman utama marketplace bonsai sebagai pintu masuk utama bagi pengunjung.

## Status Implementasi

Sudah diimplementasikan sepenuhnya.

## Fitur yang Tersedia

- Hero section dengan ajakan bertindak (CTA) utama.
- Menampilkan produk unggulan (field `featured = true`) dari database.
- Akses cepat ke halaman shop, about, care guide, dan artikel.
- Navigasi publik untuk pengguna baru maupun pengguna yang sudah login.

## Catatan Implementasi

- Komponen Livewire: `App\Livewire\LandingPage`.
- Route: `GET /` dengan nama `home`.
- Produk unggulan ditandai melalui field `featured` (boolean) pada model `Product`.
- Hanya produk dengan status `approved` dan `featured = true` yang ditampilkan di landing page.
- Tampilan dibuat dengan Blade dan Tailwind CSS.
