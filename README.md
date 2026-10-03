# API Image Storage System

Sistem API untuk menyimpan file (terutama gambar) sebagai backup dan menyediakan link publik untuk akses. Sistem mencakup manajemen API key, pelacakan penggunaan, dan endpoint untuk upload/retrieve data.

## Fitur Utama

### 1. Endpoint API
- **GET `/api/health`** - Status kesehatan sistem
- **POST `/api/data2nas`** - Upload file ke sistem
  - Parameter: `file` (base64), `nama_sistem`, `id_tabel`, `tabel_name`, `apikey`
- **GET `/api/data2nas/{id}`** - Retrieve file dari sistem
  - Parameter: `id` (ID file dari database)

### 2. Manajemen API Key
- Halaman manajemen untuk pembuatan dan pencatatan API key
- Tabel API: Nama, User ID pemilik
- Tabel Data API: API ID, Nama File, IP, File Path, URL

## Struktur Database

### Tabel `api_keys`
- id (primary key, auto increment)
- user_id (foreign key ke users table)
- api_key (string, unique, indexed)
- name (string)
- is_active (boolean, default: true)
- created_at (timestamp)
- updated_at (timestamp)

### Tabel `api_data`
- id (primary key, auto increment)
- api_id (foreign key ke api_keys.id)
- nama_file (string)
- ip_address (string)
- file_path (string)
- url (string, unique)
- id_tabel (string)
- tabel_name (string)
- created_at (timestamp)
- updated_at (timestamp)

## Akun Admin & Registrasi

Fitur **registrasi publik dimatikan**. Akun hanya dapat dibuat oleh admin melalui
halaman **User Management**, atau lewat seeder untuk akun pertama.

Buat akun admin awal:

```bash
php artisan db:seed
```

Di server (produksi) tambahkan `--force`:

```bash
php artisan db:seed --force
```

Kredensial diambil dari `.env` (lihat `.env.example`):

```
ADMIN_NAME=Administrator
ADMIN_EMAIL=admin@example.com
ADMIN_PASSWORD=password
```

> ⚠️ Ganti `ADMIN_PASSWORD` sebelum deploy ke produksi.

## Frontend / Aset

Proyek memakai **Bootstrap 5** (via `resources/sass/app.scss`) dan **Vite**.
Aset hasil build disimpan di `public/build` dan **ikut di-commit** agar UI langsung
tampil saat deploy tanpa Node.

- Mode development: `npm run dev`
- Build produksi: `npm run build`

Jika mengubah SCSS/JS, jalankan `npm run build` lalu commit ulang folder `public/build`.

## Instalasi (Development)

1. Clone repository ini
2. Jalankan `composer install`
3. Salin `.env.example` ke `.env` dan sesuaikan konfigurasi (default: **MySQL**)
4. Jalankan `php artisan key:generate`
5. Siapkan database MySQL (lihat bagian berikut), lalu `php artisan migrate --seed`
6. Jalankan `npm install` dan `npm run build` untuk asset produksi (atau `npm run dev` saat development)
7. Login dengan `ADMIN_EMAIL` / `ADMIN_PASSWORD` dari `.env`

## Instalasi di Server (MySQL)

### 1. Prasyarat
- PHP >= 8.3 dengan ekstensi: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`,
  `ctype`, `json`, `bcmath`, `fileinfo`, `gd`/`imagick`, `zip`
- MySQL 8 / MariaDB 10.4+
- Composer
- Web server (Nginx/Apache) dengan **document root ke folder `public`**
- Node.js (opsional — aset `public/build` sudah di-commit)

### 2. Buat database & user
```sql
CREATE DATABASE api2nas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'api2nas'@'localhost' IDENTIFIED BY 'ganti_password_kuat';
GRANT ALL PRIVILEGES ON api2nas.* TO 'api2nas'@'localhost';
FLUSH PRIVILEGES;
```

### 3. Konfigurasi `.env`
```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-anda.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=api2nas
DB_USERNAME=api2nas
DB_PASSWORD=ganti_password_kuat

ADMIN_NAME=Administrator
ADMIN_EMAIL=admin@domain-anda.com
ADMIN_PASSWORD=password_kuat
```

> MySQL di mesin dev ini berjalan di port **3307** (`.env.example` memakai 3307).
> Sesuaikan `DB_PORT` dengan server Anda (umumnya 3306).
> Untuk memakai database lama `apiimg2nas`, set `DB_DATABASE=apiimg2nas`.

### 4. Deploy
```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
php artisan optimize   # cache config, route, view, event
```

### 5. Permission
```bash
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

### 6. Selesai
Login dengan `ADMIN_EMAIL` / `ADMIN_PASSWORD`, lalu buat API key di menu **API Keys**.

> `php artisan migrate --seed` aman dijalankan ulang: migrasi bersifat idempotent dan
> `AdminUserSeeder` memakai `firstOrCreate` (tidak membuat admin duplikat).

## Penggunaan API

### Membuat API Key
1. Login ke sistem
2. Akses halaman "API Management"
3. Klik "Create New API Key"
4. Masukkan nama dan aktifkan API key
5. Simpan API key yang dihasilkan

### Menggunakan Endpoint API
#### Health Check
```
GET /api/health
```

#### Upload File
```
POST /api/data2nas
Headers:
  Content-Type: application/json

Body:
{
  "file": "base64_encoded_file",
  "nama_sistem": "nama_file_asli",
  "id_tabel": "id_record",
  "tabel_name": "nama_tabel",
  "apikey": "your_api_key_here"
}
```

#### Mendapatkan File
```
GET /api/data2nas/{id}
```

## Keamanan
- Validasi API key sebelum setiap operasi
- Batasi ukuran file upload
- Validasi tipe file yang diterima
- Gunakan rate limiting untuk mencegah abuse

## Manajemen File
- File disimpan di `storage/app/public/{nama_api}/{tahun}/{bulan}/`
- URL publik: `{APP_URL}/storage/{path}`
- Jalankan `php artisan storage:link` sekali setelah deploy

## Testing
- Test memakai **SQLite in-memory** (`phpunit.xml`) agar cepat & terisolasi,
  terlepas dari koneksi MySQL aplikasi.
- Jalankan: `composer test`

## Logging
- Log semua permintaan API termasuk IP address
- Lacak penggunaan API per API key

## Performance
- Gunakan indexing pada kolom yang sering diquery
- Pertimbangkan caching untuk endpoint yang sering diakses