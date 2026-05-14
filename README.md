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

## Instalasi

1. Clone repository ini
2. Jalankan `composer install`
3. Salin `.env.example` ke `.env` dan sesuaikan konfigurasi
4. Jalankan `php artisan key:generate`
5. Jalankan migrasi database: `php artisan migrate`
6. Jalankan `npm install` dan `npm run dev` untuk asset
7. Akses halaman registrasi/login untuk membuat akun
8. Gunakan halaman management API untuk membuat API key

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
- File disimpan di direktori `storage/app/public/uploads/`
- Gunakan struktur folder berdasarkan tanggal untuk organisasi
- Pastikan direktori `storage/app/public` telah ditautkan ke `public/storage`

## Logging
- Log semua permintaan API termasuk IP address
- Lacak penggunaan API per API key

## Performance
- Gunakan indexing pada kolom yang sering diquery
- Pertimbangkan caching untuk endpoint yang sering diakses