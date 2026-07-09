# bonsaiku - Bonsai Marketplace

bonsaiku adalah proyek website yang mencoba memfasilitasi komunitas untuk bertransaksi (jual-beli) bonsai, bahan bonsai, peralatan, media tanam dan pernak pernik nya sehingga diharapkan tidak terlalu spam grub whatsapp komunitas.
agar grub whatsapp menjadi wadah tempat untuk diskusi, berbagi pengalaman perihal bonsai.

## Stack Teknologi

- PHP 8.2+
- Laravel 12
- Livewire 4
- Blade
- Alpine.js
- Tailwind CSS 4
- Vite 7
- MySQL
- Spatie Laravel Permission
- Spatie Laravel Media Library
- Spatie Laravel Activitylog
- Laravel Socialite
- Barryvdh DomPDF
- Maatwebsite Excel
- PHPUnit
- Laravel Pint

## Setup Lokal

Install dependency PHP:

```bash
composer install
```

Install dependency frontend:

```bash
npm install
```

Siapkan environment:

```bash
cp .env.example .env
php artisan key:generate
```

Pastikan database MySQL sudah dibuat dan .env mengarah ke koneksi MySQL. Lalu jalankan migrasi dan seeder:

```bash
php artisan migrate --seed
```

Jalankan development server:

```bash
composer run dev
```

## Perintah Umum

Build frontend:

```bash
npm run build
```

Jalankan test:

```bash
composer test
```

Format kode PHP:

```bash
vendor/bin/pint
```

Clear cache Laravel:

```bash
php artisan optimize:clear
```
