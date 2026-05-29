# Posyandu Pintar - Agent Guide

## 1. Non-Negotiable Rules

- **Backend Laravel sudah SELESAI dan TERKUNCI.** Jangan ubah, refactor, atau hapus file apapun di dalam folder `app/`, `config/`, `database/`, `routes/api.php`, dan `bootstrap/app.php` kecuali user secara eksplisit meminta perubahan backend.
- **Frontend/web adalah scope utama pengerjaan sekarang.** Semua implementasi baru harus di folder frontend yang terpisah dari Laravel backend.
- Klarifikasi sebelum implementasi besar. Kalau requirement ambigu, tanya dulu daripada menulis banyak kode ke arah yang salah.
- Stick ke scope. Jangan refactor file di luar scope task. Kalau melihat masalah lain, sebutkan di akhir response sebagai catatan saja.
- Jangan print, expose, atau edit nilai di `.env` kecuali user secara eksplisit meminta.

### 1.1 File Backend yang TIDAK BOLEH Diubah

File-file berikut sudah final dan tidak boleh disentuh:

```
posyandu-pintar/
|- app/
|  |- Http/
|  |  |- Controllers/Api/
|  |  |  |- AuthController.php          ← JANGAN UBAH
|  |  |  |- WargaController.php         ← JANGAN UBAH
|  |  |  |- KaderController.php         ← JANGAN UBAH
|  |  |  |- PenimbanganController.php   ← JANGAN UBAH
|  |  |  |- ImunisasiController.php     ← JANGAN UBAH
|  |  |- Middleware/
|  |  |  |- ApiKeyMiddleware.php        ← JANGAN UBAH
|- Models/
|  |  |- User.php                       ← JANGAN UBAH
|  |  |- Warga.php                      ← JANGAN UBAH
|  |  |- Kader.php                      ← JANGAN UBAH
|  |  |- Penimbangan.php                ← JANGAN UBAH
|  |  |- Imunisasi.php                  ← JANGAN UBAH
|  |  |- ApiKey.php                     ← JANGAN UBAH
|- config/
|  |- auth.php                          ← JANGAN UBAH
|  |- jwt.php                           ← JANGAN UBAH
|- database/
|  |- migrations/                       ← JANGAN UBAH semua file migration
|  |- seeders/                          ← JANGAN UBAH
|- routes/
|  |- api.php                           ← JANGAN UBAH
|- bootstrap/
|  |- app.php                           ← JANGAN UBAH
```

---

## 2. Source of Truth

**Laravel backend di `posyandu-pintar/` adalah source of truth** untuk semua API contract dan behavior. Frontend harus mengikuti contract yang sudah ada, bukan sebaliknya.

Prioritas keputusan:

1. Contract API Laravel yang sudah berjalan
2. Response format yang sudah didefinisikan di controller
3. Struktur database dari migration yang sudah ada

Kalau ada perubahan contract yang diperlukan untuk frontend, **diskusikan dulu** — jangan langsung ubah backend.

---

## 3. Struktur Proyek

```
posyandu-pintar/          ← BACKEND LARAVEL (SUDAH SELESAI, JANGAN UBAH)
|- app/
|  |- Http/
|  |  |- Controllers/Api/ ← 5 controller sudah final
|  |  |- Middleware/      ← ApiKeyMiddleware sudah final
|  |- Models/             ← 6 model sudah final
|- config/                ← Konfigurasi sudah final
|- database/
|  |- migrations/         ← 8 migration sudah final
|  |- seeders/            ← Seeder sudah final
|- routes/
|  |- api.php             ← 24 endpoint sudah final
|- bootstrap/
|  |- app.php             ← Middleware alias sudah final
```

---

## 4. API Contract yang Sudah Final

Base URL: `http://127.0.0.1:8000/api`

### 4.1 Autentikasi

Ada 3 jenis autentikasi:

| Jenis | Cara Pakai | Keterangan |
|-------|-----------|------------|
| **Public** | Tidak perlu header apapun | Hanya register & login |
| **JWT Bearer** | Header `Authorization: Bearer <token>` | Semua CRUD data |
| **API Key** | Header `X-API-Key: <api_key>` | Akses public read-only |

Role yang tersedia: `admin` dan `kader`.

### 4.2 Daftar Endpoint Lengkap (24 endpoint)

#### Auth (Public)
```
POST /api/auth/register
POST /api/auth/login
```

#### Auth (JWT Protected)
```
POST /api/auth/logout
GET  /api/auth/me
POST /api/auth/generate-api-key
```

#### Warga (JWT Protected)
```
GET    /api/wargas
POST   /api/wargas
GET    /api/wargas/{id}
PUT    /api/wargas/{id}
DELETE /api/wargas/{id}
POST   /api/wargas/bulk
```

#### Kader (JWT Protected)
```
GET    /api/kaders
POST   /api/kaders
GET    /api/kaders/{id}
PUT    /api/kaders/{id}
DELETE /api/kaders/{id}
```

#### Penimbangan (JWT Protected)
```
GET    /api/penimbangans
POST   /api/penimbangans
GET    /api/penimbangans/{id}
PUT    /api/penimbangans/{id}
DELETE /api/penimbangans/{id}
POST   /api/penimbangans/bulk
```

#### Imunisasi (JWT Protected)
```
GET    /api/imunisasis
POST   /api/imunisasis
GET    /api/imunisasis/{id}
PUT    /api/imunisasis/{id}
DELETE /api/imunisasis/{id}
```

#### Public (API Key Protected)
```
GET /api/public/wargas
GET /api/public/penimbangans
```

### 4.3 Response Format

Semua response sudah diformat rapi oleh controller. Frontend **tidak boleh** mengubah format ini, hanya mengonsumsinya.

**Response List (GET all):**
```json
{
    "message": "Data berhasil diambil",
    "total": 10,
    "current_page": 1,
    "last_page": 1,
    "data": [ ... ]
}
```

**Response Create (POST):**
```json
{
    "message": "Data berhasil ditambahkan",
    "data": { ... }
}
```

**Response Error Validasi (422):**
```json
{
    "errors": {
        "field": ["pesan error"]
    }
}
```

**Response Unauthorized (401):**
```json
{
    "message": "Pesan error"
}
```

### 4.4 Field Warga

```json
{
    "nama_lengkap": "string",
    "nik": "string (unik, 16 digit)",
    "tanggal_lahir": "YYYY-MM-DD",
    "jenis_kelamin": "L | P",
    "kategori": "balita | ibu_hamil | lansia",
    "nama_orang_tua": "string | null",
    "alamat": "string",
    "rt_rw": "string"
}
```

### 4.5 Field Penimbangan

```json
{
    "warga_id": "integer",
    "kader_id": "integer",
    "tanggal": "YYYY-MM-DD",
    "berat_badan": "decimal (kg)",
    "tinggi_badan": "decimal (cm)",
    "lingkar_kepala": "decimal | null (cm)",
    "status_gizi": "string | null",
    "catatan": "string | null"
}
```

### 4.6 Field Imunisasi

```json
{
    "warga_id": "integer",
    "kader_id": "integer",
    "jenis_imunisasi": "string",
    "tanggal_pemberian": "YYYY-MM-DD",
    "tanggal_berikutnya": "YYYY-MM-DD | null",
    "keterangan": "string | null"
}
```

### 4.7 Field Kader

```json
{
    "user_id": "integer",
    "nama_lengkap": "string",
    "no_hp": "string",
    "alamat": "string",
    "wilayah": "string"
}
```

---

## 5. Database Schema (Sudah Final)

```
users
|- id, name, email, password
|- role: enum('admin', 'kader')
|- timestamps

kaders
|- id, user_id (FK users)
|- nama_lengkap, no_hp, alamat, wilayah
|- timestamps

wargas
|- id
|- nama_lengkap, nik (unique)
|- tanggal_lahir, jenis_kelamin: enum('L','P')
|- kategori: enum('balita','ibu_hamil','lansia')
|- nama_orang_tua (nullable), alamat, rt_rw
|- timestamps

penimbangans
|- id, warga_id (FK), kader_id (FK)
|- tanggal, berat_badan, tinggi_badan
|- lingkar_kepala (nullable), status_gizi (nullable)
|- catatan (nullable)
|- timestamps

imunisasis
|- id, warga_id (FK), kader_id (FK)
|- jenis_imunisasi, tanggal_pemberian
|- tanggal_berikutnya (nullable), keterangan (nullable)
|- timestamps

api_keys
|- id, user_id (FK)
|- key (64 char, unique), name
|- is_active (boolean), expires_at (nullable)
|- timestamps
```

---

## 6. Stack Teknologi Backend (Referensi Saja)

- **Framework:** Laravel 12
- **PHP:** >= 8.1
- **Database:** MySQL
- **Auth:** JWT via `php-open-source-saver/jwt-auth`
- **API Key:** Custom middleware `ApiKeyMiddleware`
- **Port dev:** `http://127.0.0.1:8000`

Frontend **tidak perlu** install atau mengubah dependency backend.

---

## 7. Aturan Pengerjaan Frontend/Web

Saat mengerjakan frontend/web yang terhubung ke API ini:

- Semua request ke backend harus ke `http://127.0.0.1:8000/api` (atau dari env variable).
- Simpan JWT token di `localStorage` atau `sessionStorage` dengan key yang konsisten.
- Untuk request yang butuh JWT, selalu tambahkan header `Authorization: Bearer <token>`.
- Untuk request public route, tambahkan header `X-API-Key: <api_key>`.
- Jangan hardcode token atau api_key di source code.
- Tangani response error dengan baik: 401 (redirect ke login), 422 (tampilkan pesan validasi), 403 (tampilkan akses ditolak).
- Jangan membuat endpoint atau logika backend baru di Laravel — kalau butuh data tambahan, diskusikan dulu.

---

## 8. Checklist Sebelum Implementasi Frontend

Sebelum membuat halaman atau fitur baru di frontend:

- [ ] Pastikan endpoint yang dibutuhkan sudah ada di daftar 24 endpoint di atas
- [ ] Baca format request dan response yang dibutuhkan dari section 4
- [ ] Pastikan tidak ada perubahan yang perlu dilakukan ke backend
- [ ] Kalau butuh data yang belum ada di endpoint yang ada, diskusikan dulu sebelum implementasi
