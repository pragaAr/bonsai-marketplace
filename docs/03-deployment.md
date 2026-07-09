# Deployment

## Persiapan Server

Aplikasi ini cocok dijalankan pada server dengan environment sebagai berikut:

- PHP 8.2+
- Composer
- Node.js dan npm
- MySQL atau database yang kompatibel
- Web server seperti Nginx atau Apache
- SSL untuk domain produksi

## Langkah Umum

1. Clone repository ke server.
2. Install dependency PHP:
   ```bash
   composer install --no-dev --optimize-autoloader
   ```
3. Install dependency frontend:
   ```bash
   npm install
   npm run build
   ```
4. Siapkan file environment:
   ```bash
   cp .env.example .env
   ```
   lalu isi konfigurasi database, mail, storage, dan OAuth Google.
5. Generate application key:
   ```bash
   php artisan key:generate
   ```
6. Jalankan migrasi:
   ```bash
   php artisan migrate --force
   ```
7. Jalankan seed bila diperlukan:
   ```bash
   php artisan db:seed
   ```
8. Atur permission storage:
   ```bash
   chmod -R 775 storage bootstrap/cache
   ```

## Konfigurasi Environment Penting

Beberapa variabel yang wajib disesuaikan:

- `APP_ENV=production`
- `APP_URL=https://domain-anda.com`
- `DB_CONNECTION=mysql`
- `DB_HOST=...`
- `DB_DATABASE=...`
- `DB_USERNAME=...`
- `DB_PASSWORD=...`
- `GOOGLE_CLIENT_ID=...`
- `GOOGLE_CLIENT_SECRET=...`
- `GOOGLE_REDIRECT_URI=...`

## Optimisasi Production

Setelah deploy, jalankan:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

## Queue dan Background Jobs

Jika aplikasi memakai queue, jalankan worker:

```bash
php artisan queue:work
```

## Catatan Operasional

- Pastikan folder storage dan public/storage dapat ditulis oleh web server.
- Aktifkan log monitoring untuk mendeteksi error runtime.
- Backup database secara berkala.
- Jalankan maintenance mode saat deploy besar.

## Contoh Deploy ke Hostinger (Subdomain) ssh access

Berikut langkah yang umum dipakai untuk deploy aplikasi ini ke domain/subdomain Hostinger (praktek pakai subdomain).

### 1. Buat subdomain

Contoh:

- subdomain: `subdomain.domain.com`
- folder root: `public_html/subdomain`

### 2. Aktifkan PHP 8.2 untuk subdomain

Edit file `.htaccess` di folder subdomain dan tambahkan konfigurasi agar subdomain memakai PHP 8.2, karena domain masih pakai php 7.4:

```apache
<FilesMatch "\.(php4|php5|php3|php2|php|phtml)$">
    SetHandler application/x-lsphp82
</FilesMatch>
```

Setelah itu, verifikasi dengan `phpinfo()` dan hapus file `phpinfo.php` jika sudah tidak dibutuhkan.

### 3. Masuk ke direktori proyek

```bash
cd ~/domains/domain.com/public_html/subdomain
```

### 4. Clone repository

```bash
git clone https://github.com/pragaAr/bonsai-marketplace.git .
```

### 5. Install dependency

```bash
composer install
```

### 6. Siapkan file environment

```bash
cp .env.example .env
```

Edit `.env` dan isi nilai:

- `APP_URL=https://subdomain.domain.com`
- `DB_HOST=...`
- `DB_DATABASE=...`
- `DB_USERNAME=...`
- `DB_PASSWORD=...`

### 7. Generate key aplikasi

```bash
php artisan key:generate
```

### 8. Jalankan migrasi

```bash
php artisan migrate --seed
```

### 9. Bersihkan cache

```bash
php artisan optimize:clear
```

### 10. Buat symlink storage manual

Karena `php artisan storage:link` bisa gagal pada beberapa hosting yang membatasi fungsi symlink, gunakan cara manual:

```bash
rm -rf public/storage
ln -s ../storage/app/public public/storage
```

Verifikasi dengan:

```bash
ls -l public | grep storage
```

### 11. Build frontend di lokal

```bash
npm install
npm run build
```

Upload hanya folder hasil build:

- `public/build`

Jangan upload folder `node_modules`.

### 12. Permission file dan folder

- Folder: `755`
- File: `644`

### 13. Jika muncul 403 Forbidden

Pastikan document root mengarah ke folder publik aplikasi:

```text
public_html/subdomain/public
```

Jika tidak bisa diubah, sesuaikan rewrite rule `.htaccess` sesuai kebutuhan hosting.

### 14. Pastikan konfigurasi Google OAuth sesuai

- GOOGLE*CLIENT_ID=\_clientId anda*
- GOOGLE*CLIENT_SECRET=\_secret anda*
- GOOGLE*REDIRECT_URI=\_redirect url*

## Checklist Verifikasi Setelah Deploy

- Website dapat dibuka.
- CSS dan JS termuat dengan benar.
- File upload dan storage berjalan.
- Database terkoneksi.
- Login Google dan fitur autentikasi berjalan.
