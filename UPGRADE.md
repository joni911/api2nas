# Panduan & Catatan Upgrade Laravel 10 → 11 → 12 → 13

Dokumen ini mencatat proses upgrade `api2nas` dari Laravel 10 hingga Laravel 13,
perubahan yang dilakukan di setiap versi, serta bukti bahwa seluruh fitur tetap
berjalan melalui test suite (TDD) di setiap tahap.

## Ringkasan Hasil

| Tahap | Framework | PHP | PHPUnit | Test | Status |
|-------|-----------|-----|---------|------|--------|
| Baseline | 10.50.0 | ^8.1 (8.4) | 10.5 | 55 tes / 160 assertion | ✅ Lulus |
| Laravel 11 | 11.57.0 | ^8.2 | 11.5 | 55 tes / 160 assertion | ✅ Lulus |
| Laravel 12 | 12.69.3 | ^8.2 | 11.5 | 55 tes / 160 assertion | ✅ Lulus |
| Laravel 13 | 13.34.0 | ^8.3 | 12.5 | 56 tes / 163 assertion | ✅ Lulus |

Git tag untuk setiap tahap:

- `upgrade-laravel-11`
- `upgrade-laravel-12`
- `upgrade-laravel-13`

Branch: `upgrade/laravel-11-12-13`.

Referensi guide yang dipakai:

- https://laravel.com/framework/docs/10.x/upgrade
- https://laravel.com/framework/docs/11.x/upgrade
- https://laravel.com/framework/docs/12.x/upgrade
- https://laravel.com/framework/docs/13.x/upgrade

## Cakupan Test (TDD)

Test ditulis **sebelum** upgrade (di Laravel 10) untuk mengunci perilaku semua
fitur, lalu dijalankan ulang di setiap versi:

| File | Fitur yang diuji |
|------|------------------|
| `tests/Feature/ApiHealthTest.php` | `GET /api/health`, `GET /health` |
| `tests/Feature/ApiDataUploadTest.php` | `POST /api/data2nas` (base64 & data-URI, validasi, API key aktif/nonaktif, struktur folder `nama_api/tahun/bulan`, IP, ekstensi) |
| `tests/Feature/ApiGetDataTest.php` | `GET /api/data2nas/{id}` + 404 |
| `tests/Feature/AuthenticationTest.php` | login, register, duplikat email, logout, proteksi route |
| `tests/Feature/UserManagementTest.php` | CRUD user |
| `tests/Feature/ApiManagementTest.php` | CRUD API key + batasan kepemilikan |
| `tests/Feature/DataApiManagementTest.php` | daftar/lihat/hapus data upload + hapus file |
| `tests/Feature/ModelRelationshipTest.php` | relasi & cast model, `ApiKeyMiddleware` |
| `tests/Feature/ExampleTest.php` | landing page & JSON-LD structured data |

Menjalankan seluruh test:

```bash
php artisan test
# atau
php vendor/bin/phpunit
```

## Perubahan Per Versi

### Baseline (Laravel 10)

- Menambahkan test suite komprehensif dan konfigurasi database test SQLite in-memory.
- `tests/TestCase.php`: memanggil `withoutVite()` agar render Blade tidak butuh build Vite.
- **Perbaikan bug**: `ApiController` memvalidasi format base64 **sebelum** memisahkan
  header `data:` sehingga payload `data:image/png;base64,...` selalu ditolak 400.
  Urutan diperbaiki → dukungan data-URI berfungsi sesuai maksud kode.

### Laravel 11 (dari 10)

Berdasarkan guide 11.x:

- `laravel/framework` `^11.0`, `laravel/sanctum` `^4.0`, `nunomaduro/collision` `^8.1`,
  `laravel/tinker` `^2.9`, `phpunit/phpunit` `^11.0`, `php` `^8.2`.
- `config/sanctum.php`: kunci middleware diperbarui
  (`authenticate_session`, `encrypt_cookies`, `validate_csrf_token`).
- `phpunit.xml` dimigrasikan ke skema PHPUnit 11.
- Struktur aplikasi gaya Laravel 10 **dipertahankan** (guide secara eksplisit
  menyatakan Laravel 11 mendukung struktur lama).
- `nesbot/carbon` naik ke Carbon 3 (tidak ada pemakaian `diffIn*`).
- Catatan: pemblokiran security advisory Composer dinonaktifkan
  (`config.policy.advisories.block = false`) karena Laravel 11 sudah EOL dan
  tidak punya rilis yang bebas advisory.

### Laravel 12 (dari 11)

Berdasarkan guide 12.x:

- `laravel/framework` `^12.0`, `laravel/tinker` `^2.10`, `laravel/pint` `^1.24`,
  `laravel/sail` `^1.41`, `nunomaduro/collision` `^8.6`, `phpunit/phpunit` `^11.5`.
- Carbon 3 sudah terpasang.
- Disk `local` sudah didefinisikan eksplisit di `config/filesystems.php`, sehingga
  perubahan default root `storage/app/private` pada L12 tidak berdampak.

### Laravel 13 (dari 12)

Berdasarkan guide 13.x:

- `laravel/framework` `^13.0`, `laravel/tinker` `^3.0`, `phpunit/phpunit` `^12.0`,
  `php` `^8.3`.
- Menghapus `spatie/laravel-ignition` (tidak mendukung L13 dan tidak ada di skeleton L13).
- **Request Forgery Protection** (High Impact):
  - Middleware `VerifyCsrfToken` di-rename menjadi `PreventRequestForgery`.
  - `app/Http/Middleware/PreventRequestForgery.php` dibuat, middleware lama dihapus.
  - `app/Http/Kernel.php` dan `config/sanctum.php` diperbarui ke kelas baru.
- **Cache** `serializable_classes` diset `false` di `config/cache.php` (hardening
  deserialization).
- **Session** `serialization` diset `json` di `config/session.php` (hardening).
- **Breaking change tidak terdokumentasi eksplisit di version ranges**: Blade 13
  menambahkan directive `@context` (terkait `Context` facade). JSON-LD
  `"@context"` di `resources/views/welcome.blade.php` memicu directive tersebut dan
  membuat view gagal dikompilasi. Diperbaiki dengan meng-escape menjadi `"@@context"`
  / `"@@type"`, plus regression test.
- `laravel/ui` `4.6.3` mendukung `illuminate/* ^13.0`, sehingga `Auth::routes()`
  tetap berfungsi.

## Catatan Produksi

- Setelah upgrade ke L13, `session.serialization = json` akan meng-invalidate
  session aktif (user perlu login ulang). Jika ingin mempertahankan session,
  ubah kembali ke `php`.
- Jalankan `php artisan migrate` (tidak ada migration baru dari package; tabel
  `personal_access_tokens` sudah ada di aplikasi).
- Pastikan `php artisan storage:link` untuk akses file publik.
- Composer advisory blocking dinonaktifkan melalui `composer.json`. Untuk
  produksi sebaiknya evaluasi ulang kebijakan ini.