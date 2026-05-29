# Posyandu Pintar

Posyandu Pintar adalah aplikasi web berbasis Laravel untuk mengelola data posyandu, mulai dari pendaftaran warga, data kader, penimbangan, imunisasi, sampai dashboard ringkasan.

## Fitur

- Autentikasi pengguna
- Data warga dan kader
- Penimbangan dan imunisasi
- Jadwal posyandu
- Dashboard statistik dan aktivitas terbaru
- Endpoint publik dengan API key

## Teknologi

- Laravel 10
- PHP 8.1+
- MySQL
- Blade
- Bootstrap 5
- Bootstrap Icons
- Vanilla JavaScript
- Vite

## Instalasi

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

Jika memakai Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

## Menjalankan Aplikasi

### Development

```bash
composer run dev
```

Atau jalankan terpisah:

```bash
php artisan serve
npm run dev
```

### Production Build

```bash
npm run build
```

## Konfigurasi Penting

Pastikan `.env` berisi minimal:

- `APP_NAME`
- `APP_URL`
- `DB_CONNECTION`
- `DB_HOST`
- `DB_PORT`
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`
- `JWT_SECRET`

## API Singkat

Base URL:

```text
http://127.0.0.1:8000/api
```

### Public

- `POST /api/auth/register`
- `POST /api/auth/login`
- `POST /api/public/pendaftaran-warga`
- `GET /api/public/pendaftaran-warga/status`

### JWT Protected

- `POST /api/auth/logout`
- `GET /api/auth/me`
- `GET /api/wargas`
- `GET /api/kaders`
- `GET /api/jadwal-posyandus`
- `GET /api/penimbangans`
- `GET /api/imunisasis`

### API Key

- `GET /api/public/wargas`
- `GET /api/public/penimbangans`

## Struktur Folder

- `app/` — controller, model, middleware
- `config/` — konfigurasi aplikasi
- `database/` — migration, seeder, factory
- `public/` — file publik dan asset build
- `resources/views/` — template Blade
- `resources/js/` — source JavaScript
- `routes/api.php` — endpoint API

## Catatan

- JWT dipakai untuk akses area internal.
- API key dipakai untuk endpoint publik tertentu.
- Jika source JavaScript berubah, jalankan `npm run build` agar file produksi ikut terbarui.

## License

MIT
