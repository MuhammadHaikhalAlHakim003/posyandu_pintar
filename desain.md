# Posyandu Pintar - desain.md

## Dokumen Desain UI/UX Frontend (Laravel Blade PHP Edition)

> Versi: 2.0 (Blade Edition)  
> Projek: Sistem Informasi Posyandu Pintar  
> Stack: Laravel 12 + Blade + Bootstrap 5 + Bootstrap Icons + Vanilla JavaScript  
> Pattern: Repository Pattern (JavaScript layer untuk API calls)  

---

## 1. Design System

### 1.1 Palet Warna

| Token | Hex | Penggunaan |
|-------|-----|------------|
| `--primary` | `#0d6efd` | Primary buttons, active nav, links |
| `--primary-dark` | `#0b5ed7` | Hover primary |
| `--secondary` | `#6c757d` | Secondary buttons, muted text |
| `--success` | `#198754` | Success alerts, save buttons, status OK |
| `--danger` | `#dc3545` | Delete buttons, error alerts, validation error |
| `--warning` | `#ffc107` | Warning alerts, pending status |
| `--info` | `#0dcaf0` | Info alerts, tips |
| `--light` | `#f8f9fa` | Card backgrounds, table striping |
| `--dark` | `#212529` | Text primary, sidebar dark mode |
| `--sidebar-bg` | `#1e293b` | Sidebar background (slate-800) |
| `--sidebar-hover` | `#334155` | Sidebar item hover |
| `--sidebar-active` | `#0d6efd` | Sidebar active indicator |
| `--bg-body` | `#f1f5f9` | Page background (slate-100) |
| `--card-bg` | `#ffffff` | Card surface |
| `--border` | `#dee2e6` | Borders, dividers |
| `--text-primary` | `#212529` | Headings, primary text |
| `--text-secondary` | `#6c757d` | Labels, placeholders |
| `--text-muted` | `#adb5bd` | Disabled, hints |

### 1.2 Tipografi

| Elemen | Font | Size | Weight | Color |
|--------|------|------|--------|-------|
| H1 (Page Title) | Inter/System | 1.75rem (28px) | 700 | `--text-primary` |
| H2 (Section) | Inter/System | 1.25rem (20px) | 600 | `--text-primary` |
| H3 (Card Title) | Inter/System | 1rem (16px) | 600 | `--text-primary` |
| Body | Inter/System | 0.875rem (14px) | 400 | `--text-primary` |
| Small | Inter/System | 0.75rem (12px) | 400 | `--text-secondary` |
| Label | Inter/System | 0.8125rem (13px) | 500 | `--text-secondary` |
| Nav Item | Inter/System | 0.875rem (14px) | 500 | `#cbd5e1` |
| Nav Active | Inter/System | 0.875rem (14px) | 600 | `#ffffff` |

### 1.3 Spacing System

| Token | Value | Penggunaan |
|-------|-------|------------|
| `--space-1` | 0.25rem (4px) | Tight gaps |
| `--space-2` | 0.5rem (8px) | Icon gaps, inline spacing |
| `--space-3` | 0.75rem (12px) | Small padding |
| `--space-4` | 1rem (16px) | Standard padding |
| `--space-5` | 1.25rem (20px) | Card padding |
| `--space-6` | 1.5rem (24px) | Section gaps |
| `--space-8` | 2rem (32px) | Large sections |
| `--space-10` | 2.5rem (40px) | Page padding |

### 1.4 Border Radius

| Token | Value | Penggunaan |
|-------|-------|------------|
| `--radius-sm` | 0.25rem | Buttons small, tags |
| `--radius-md` | 0.375rem | Inputs, cards |
| `--radius-lg` | 0.5rem | Cards, modals |
| `--radius-xl` | 0.75rem | Large cards |
| `--radius-full` | 9999px | Avatars, pills |

### 1.5 Shadow

| Token | Value | Penggunaan |
|-------|-------|------------|
| `--shadow-sm` | `0 1px 2px rgba(0,0,0,0.05)` | Cards resting |
| `--shadow-md` | `0 4px 6px rgba(0,0,0,0.07)` | Cards hover, dropdowns |
| `--shadow-lg` | `0 10px 15px rgba(0,0,0,0.1)` | Modals, popovers |

### 1.6 Icons

- **Library**: Bootstrap Icons (bi-*)
- **Size**: 1rem (16px) default, 1.25rem for nav, 1.5rem for feature icons
- **Stroke**: 1.5px feel (Bootstrap Icons default)

---

## 2. Layout Architecture

### 2.1 Dashboard Layout (Authenticated)

```
+-------------------------------------------------------------+
|  [Sidebar]        |  [Topbar]                                |
|                   |  Brand | Search | Notif | UserDropdown |
|  Logo             |------------------------------------------|
|  Nav Item 1       |                                        |
|  Nav Item 2       |  [Breadcrumb]    [Action Buttons]      |
|  Nav Item 3       |                                        |
|                   |  +----------------------------------+  |
|  (Role Section)   |  |          Card Stats              |  |
|                   |  +----------------------------------+  |
|                   |                                        |
|                   |  +----------------------------------+  |
|                   |  |          Data Table / Form       |  |
|                   |  +----------------------------------+  |
|                   |                                        |
+-------------------------------------------------------------+
```

#### Sidebar Specs
- **Width**: 260px desktop, 280px tablet, hidden mobile (drawer)
- **Background**: `--sidebar-bg` (#1e293b)
- **Position**: Fixed left, full height, z-index 1040
- **Behavior**: Collapsible on desktop (mini: 70px), overlay on mobile
- **Scroll**: Independent scroll if content overflows

#### Topbar Specs
- **Height**: 60px
- **Background**: `--card-bg` (white)
- **Border**: 1px solid `--border`
- **Position**: Sticky top, z-index 1030
- **Left margin**: 260px (follows sidebar)

#### Content Area Specs
- **Background**: `--bg-body` (#f1f5f9)
- **Padding**: 1.5rem (24px)
- **Min-height**: calc(100vh - 60px)
- **Left margin**: 260px desktop, 0 mobile

### 2.2 Auth Layout (Unauthenticated)

```
+-------------------------------------------------------------+
|                                                             |
|           [Logo Posyandu Pintar]                            |
|                                                             |
|     +------------------------------------------------+      |
|     |                                                |      |
|     |           [Login Card / Form Card]               |      |
|     |                                                |      |
|     |     Email                                      |      |
|     |     [________________________]                   |      |
|     |                                                |      |
|     |     Password                                   |      |
|     |     [________________________]                   |      |
|     |                                                |      |
|     |     [  Login Button  ]                         |      |
|     |                                                |      |
|     +------------------------------------------------+      |
|                                                             |
|           [Footer / Version]                                |
|                                                             |
+-------------------------------------------------------------+
```

- **Background**: Gradient from `#0d6efd` to `#0a58ca`, or solid `#f1f5f9` with centered card
- **Card**: Max-width 420px, centered vertically and horizontally
- **Logo**: 64px icon + "Posyandu Pintar" text

### 2.3 Public Layout (Portal Warga)

```
+-------------------------------------------------------------+
|  [Navbar Public]                                            |
|  Logo | Beranda | Daftar | Cek Status | Login Petugas       |
|-------------------------------------------------------------|
|                                                             |
|           [Hero Section - Landing Page]                     |
|                                                             |
|     +------------------------------------------------+      |
|     |                                                |      |
|     |           [Content / Form / Status]            |      |
|     |                                                |      |
|     +------------------------------------------------+      |
|                                                             |
|           [Footer Public]                                   |
|                                                             |
+-------------------------------------------------------------+
```

- **Navbar**: Sticky top, white background, responsive hamburger
- **Footer**: Simple, dark background, contact info Posyandu
- **No Sidebar**: Full width content

---

## 3. Laravel Blade Architecture

### 3.1 Blade Template Inheritance

Gunakan `@extends` dan `@section` untuk struktur layout:

```blade
{{-- resources/views/layouts/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Posyandu Pintar')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    @include('partials.sidebar')
    @include('partials.topbar')

    <div class="main-content">
        <div class="content-area">
            @yield('content')
        </div>
    </div>

    @include('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
```

### 3.2 Blade Components (Reusable)

Gunakan blade components Laravel:

```blade
{{-- resources/views/components/stat-card.blade.php --}}
@props(['title', 'value', 'icon', 'color', 'trend' => null])

<div class="stat-card {{ $color }}">
    <div class="stat-icon {{ $color }}">
        <i class="bi {{ $icon }}"></i>
    </div>
    <div>
        <p class="stat-value">{{ $value }}</p>
        <p class="stat-title">{{ $title }}</p>
        @if($trend)
            <span class="stat-trend text-{{ $trend['value'] >= 0 ? 'success' : 'danger' }}">
                <i class="bi bi-arrow-{{ $trend['value'] >= 0 ? 'up' : 'down' }}"></i>
                {{ abs($trend['value']) }}% {{ $trend['label'] }}
            </span>
        @endif
    </div>
</div>
```

Penggunaan:
```blade
<x-stat-card 
    title="Total Warga" 
    value="1,234" 
    icon="bi-people" 
    color="primary"
    :trend="['value' => 12, 'label' => 'bulan ini']" />
```

### 3.3 Blade Directives untuk Logic

```blade
{{-- Conditional Rendering --}}
@auth
    @if(auth()->user()->role === 'admin')
        {{-- Admin only content --}}
    @elseif(auth()->user()->role === 'kader')
        {{-- Kader content --}}
    @endif
@endauth

@guest
    {{-- Public content --}}
@endguest

{{-- Loops --}}
@forelse($wargas as $index => $warga)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $warga->nama }}</td>
    </tr>
@empty
    @include('components.empty-state', ['message' => 'Belum ada data warga'])
@endforelse

{{-- Pagination --}}
{{ $wargas->links('pagination::bootstrap-5') }}

{{-- Form Errors --}}
@error('nik')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror

{{-- Old Input --}}
<input type="text" name="nama" value="{{ old('nama', $warga->nama ?? '') }}" class="form-control @error('nama') is-invalid @enderror">

{{-- CSRF --}}
@csrf

{{-- Method Spoofing --}}
@method('PUT')
```

---

## 4. Navigation Structure

### 4.1 Admin Navigation

```
Sidebar Admin:
├── Header: Logo + "Posyandu Pintar"
├── Divider
├── Dashboard (bi-speedometer2)
├── Data Master
│   ├── Warga (bi-people)
│   ├── Kader (bi-person-badge)
├── Transaksi
│   ├── Penimbangan (bi-clipboard-data)
│   ├── Imunisasi (bi-shield-plus)
├── Laporan
│   ├── Laporan Warga (bi-file-earmark-text)
│   ├── Laporan Penimbangan (bi-graph-up)
├── Divider
├── Pengaturan (bi-gear)
├── Logout (bi-box-arrow-right)
```

### 4.2 Kader Navigation

```
Sidebar Kader:
├── Header: Logo + "Posyandu Pintar"
├── Divider
├── Dashboard (bi-speedometer2)
├── Transaksi
│   ├── Penimbangan (bi-clipboard-data)
│   ├── Imunisasi (bi-shield-plus)
├── Data Warga (bi-people) [read-only or limited]
├── Divider
├── Logout (bi-box-arrow-right)
```

### 4.3 Navigation Item States

| State | Background | Text | Icon | Left Border |
|-------|-----------|------|------|-------------|
| Default | transparent | `#cbd5e1` | `#94a3b8` | none |
| Hover | `#334155` | `#f8fafc` | `#f8fafc` | none |
| Active | `#0f172a` | `#ffffff` | `#0d6efd` | 3px solid `#0d6efd` |
| Submenu Open | `#1e293b` | `#f8fafc` | - | - |

---

## 5. Page Designs (Blade Views)

### 5.1 Login Page

**Route**: `GET /login` -> `AuthController@showLoginForm`
**View**: `resources/views/auth/login.blade.php`
**Layout**: `layouts.auth`

```blade
{{-- login.blade.php --}}
@extends('layouts.auth')

@section('title', 'Login - Posyandu Pintar')

@section('content')
<div class="login-card">
    <div class="logo-area">
        <i class="bi bi-heart-pulse-fill"></i>
        <h2>Posyandu Pintar</h2>
        <p>Sistem Informasi Posyandu</p>
    </div>

    <form action="{{ route('login') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label fw-medium">Email</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                       placeholder="Masukkan email" value="{{ old('email') }}">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-medium">Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                       placeholder="Masukkan password">
                <button class="btn btn-outline-secondary toggle-password" type="button">
                    <i class="bi bi-eye"></i>
                </button>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">Ingat saya</label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-login">
            <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
        </button>
    </form>

    <div class="footer-text">
        &copy; {{ date('Y') }} Posyandu Pintar. UAS Pemrograman Web Lanjut.
    </div>
</div>
@endsection
```

#### Validation Rules (Frontend + Backend):
- Email: required, valid email format
- Password: required, min 6 characters

---

### 5.2 Dashboard Admin Page

**Route**: `GET /admin/dashboard` -> `DashboardController@admin`
**View**: `resources/views/dashboard/admin.blade.php`
**Layout**: `layouts.dashboard`

```blade
{{-- admin.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Dashboard Admin - Posyandu Pintar')

@section('content')
    {{-- Breadcrumb --}}
    <div class="breadcrumb-area">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Overview</li>
            </ol>
        </nav>
        <div><span class="text-muted small">{{ now()->format('d F Y') }}</span></div>
    </div>

    {{-- Quick Actions --}}
    <div class="quick-actions">
        <a href="{{ route('warga.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>Tambah Warga
        </a>
        <a href="{{ route('penimbangan.create') }}" class="btn btn-success">
            <i class="bi bi-clipboard-data me-2"></i>Input Penimbangan
        </a>
        <a href="{{ route('imunisasi.create') }}" class="btn btn-info text-white">
            <i class="bi bi-shield-plus me-2"></i>Input Imunisasi
        </a>
    </div>

    {{-- Stats Row --}}
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <x-stat-card title="Total Warga" value="{{ $stats['total_warga'] }}" 
                        icon="bi-people" color="primary" 
                        :trend="['value' => 12, 'label' => 'bulan ini']" />
        </div>
        <div class="col-xl-3 col-md-6">
            <x-stat-card title="Total Kader" value="{{ $stats['total_kader'] }}" 
                        icon="bi-person-badge" color="success"
                        :trend="['value' => 2, 'label' => 'baru']" />
        </div>
        <div class="col-xl-3 col-md-6">
            <x-stat-card title="Total Penimbangan" value="{{ $stats['total_penimbangan'] }}" 
                        icon="bi-clipboard-data" color="warning"
                        :trend="['value' => 150, 'label' => 'bulan ini']" />
        </div>
        <div class="col-xl-3 col-md-6">
            <x-stat-card title="Total Imunisasi" value="{{ $stats['total_imunisasi'] }}" 
                        icon="bi-shield-plus" color="info"
                        :trend="['value' => 98, 'label' => 'bulan ini']" />
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="card-table">
        <div class="card-header">
            <h5 class="card-title">
                <i class="bi bi-clock-history me-2 text-primary"></i>Aktivitas Terbaru
            </h5>
            <a href="#" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Nama</th>
                        <th>Petugas</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $index => $activity)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $activity->created_at->format('d F Y') }}</td>
                            <td>
                                @switch($activity->jenis)
                                    @case('penimbangan')
                                        <span class="badge bg-warning text-dark">Penimbangan</span>
                                        @break
                                    @case('imunisasi')
                                        <span class="badge bg-info text-dark">Imunisasi</span>
                                        @break
                                    @default
                                        <span class="badge bg-primary">Warga Baru</span>
                                @endswitch
                            </td>
                            <td><strong>{{ $activity->warga->nama }}</strong></td>
                            <td>{{ $activity->petugas->nama }}</td>
                            <td><span class="badge bg-success">Selesai</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                @include('components.empty-state', ['message' => 'Belum ada aktivitas'])
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
```

---

### 5.3 Dashboard Kader Page

**Route**: `GET /kader/dashboard` -> `DashboardController@kader`
**View**: `resources/views/dashboard/kader.blade.php`
**Layout**: `layouts.dashboard`

#### Stat Cards (3 cards):
- Total Warga (read-only count)
- Penimbangan Bulan Ini
- Imunisasi Bulan Ini

#### Quick Actions:
- "Input Penimbangan"
- "Input Imunisasi"
- "Cari Data Warga"

#### Today's Schedule (if applicable):
- List of warga scheduled for today

---

### 5.4 Warga List Page

**Route**: `GET /admin/warga` atau `GET /kader/warga`
**Controller**: `WargaController@index`
**View**: `resources/views/warga/index.blade.php`

```blade
{{-- index.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Data Warga - Posyandu Pintar')

@section('content')
    {{-- Breadcrumb --}}
    <div class="breadcrumb-area">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Warga</li>
            </ol>
        </nav>
        <div class="d-flex gap-2">
            @can('create', App\\Models\\Warga::class)
                <a href="{{ route('warga.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg me-1"></i>Tambah Warga
                </a>
            @endcan
            <a href="#" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-upload me-1"></i>Import
            </a>
            <a href="#" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-download me-1"></i>Export
            </a>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="filter-bar">
        <form action="{{ route('warga.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label small text-muted">Cari</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Cari nama atau NIK..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted">Kategori</label>
                <select name="kategori" class="form-select">
                    <option value="">Semua Kategori</option>
                    <option value="balita" {{ request('kategori') == 'balita' ? 'selected' : '' }}>Balita</option>
                    <option value="ibu_hamil" {{ request('kategori') == 'ibu_hamil' ? 'selected' : '' }}>Ibu Hamil</option>
                    <option value="lansia" {{ request('kategori') == 'lansia' ? 'selected' : '' }}>Lansia</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="form-select">
                    <option value="">Semua</option>
                    <option value="L" {{ request('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ request('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>
            <div class="col-md-2">
                <a href="{{ route('warga.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Data Table --}}
    <div class="card-table">
        <div class="card-header">
            <h5 class="card-title"><i class="bi bi-people me-2 text-primary"></i>Daftar Warga</h5>
            <span class="text-muted small">Menampilkan {{ $wargas->firstItem() }}-{{ $wargas->lastItem() }} dari {{ $wargas->total() }} data</span>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th width="150">NIK</th>
                        <th>Nama</th>
                        <th width="120">Kategori</th>
                        <th width="80">JK</th>
                        <th width="80">Umur</th>
                        <th width="140" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($wargas as $index => $warga)
                        <tr>
                            <td>{{ $wargas->firstItem() + $index }}</td>
                            <td><code>{{ $warga->nik }}</code></td>
                            <td><strong>{{ $warga->nama }}</strong></td>
                            <td>
                                @switch($warga->kategori)
                                    @case('balita')
                                        <span class="badge-category badge-balita">Balita</span>
                                        @break
                                    @case('ibu_hamil')
                                        <span class="badge-category badge-ibu">Ibu Hamil</span>
                                        @break
                                    @case('lansia')
                                        <span class="badge-category badge-lansia">Lansia</span>
                                        @break
                                @endswitch
                            </td>
                            <td class="text-center">
                                @if($warga->jenis_kelamin == 'L')
                                    <i class="bi bi-gender-male text-primary"></i> L
                                @else
                                    <i class="bi bi-gender-female text-danger"></i> P
                                @endif
                            </td>
                            <td>{{ $warga->umur }} thn</td>
                            <td class="text-center action-btns">
                                <a href="{{ route('warga.show', $warga->id) }}" class="btn btn-outline-info btn-sm" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @can('update', $warga)
                                    <a href="{{ route('warga.edit', $warga->id) }}" class="btn btn-outline-warning btn-sm" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                @endcan
                                @can('delete', $warga)
                                    <button type="button" class="btn btn-outline-danger btn-sm" 
                                            data-bs-toggle="modal" data-bs-target="#deleteModal{{ $warga->id }}" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                @include('components.empty-state', [
                                    'icon' => 'bi-inbox',
                                    'title' => 'Belum ada data warga',
                                    'description' => 'Klik tombol Tambah Warga untuk membuat data baru'
                                ])
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="pagination-area">
            <div class="d-flex align-items-center gap-2">
                <select class="form-select form-select-sm" style="width:70px" onchange="window.location.href=this.value">
                    <option value="{{ request()->fullUrlWithQuery(['per_page' => 10]) }}" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                    <option value="{{ request()->fullUrlWithQuery(['per_page' => 25]) }}" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="{{ request()->fullUrlWithQuery(['per_page' => 50]) }}" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                </select>
                <span class="text-muted">data per halaman</span>
            </div>
            {{ $wargas->links('pagination::bootstrap-5') }}
        </div>
    </div>

    {{-- Delete Modals --}}
    @foreach($wargas as $warga)
        @can('delete', $warga)
            @include('components.confirm-delete-modal', [
                'id' => 'deleteModal' . $warga->id,
                'title' => 'Konfirmasi Hapus',
                'message' => 'Apakah Anda yakin ingin menghapus data ' . $warga->nama . '?',
                'action' => route('warga.destroy', $warga->id)
            ])
        @endcan
    @endforeach
@endsection
```

---

### 5.5 Warga Create/Edit Page

**Route**: `GET|POST /admin/warga/create` -> `WargaController@create|store`
**Route**: `GET|PUT /admin/warga/{id}/edit` -> `WargaController@edit|update`
**View**: `resources/views/warga/create.blade.php` / `resources/views/warga/edit.blade.php`

```blade
{{-- create.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Tambah Warga - Posyandu Pintar')

@section('content')
    <div class="breadcrumb-area">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('warga.index') }}">Warga</a></li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </nav>
        <a href="{{ route('warga.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>

    <div class="form-card">
        <div class="card-header">
            <h5 class="card-title"><i class="bi bi-person-plus me-2 text-primary"></i>Formulir Data Warga</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('warga.store') }}" method="POST">
                @csrf

                {{-- Section 1: Data Pribadi --}}
                <div class="section-divider">Data Pribadi</div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">NIK <span class="text-danger">*</span></label>
                        <div class="input-group has-validation">
                            <span class="input-group-text"><i class="bi bi-credit-card"></i></span>
                            <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror" 
                                   placeholder="3201xxxxxxxxxxxx" maxlength="16" value="{{ old('nik') }}">
                            @error('nik')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-text">Masukkan 16 digit nomor NIK</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" 
                                   placeholder="Nama lengkap" value="{{ old('nama') }}">
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                        <div class="d-flex gap-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="jenis_kelamin" id="jkL" value="L" 
                                       {{ old('jenis_kelamin') == 'L' ? 'checked' : '' }}>
                                <label class="form-check-label" for="jkL">
                                    <i class="bi bi-gender-male text-primary me-1"></i>Laki-laki
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="jenis_kelamin" id="jkP" value="P"
                                       {{ old('jenis_kelamin') == 'P' ? 'checked' : '' }}>
                                <label class="form-check-label" for="jkP">
                                    <i class="bi bi-gender-female text-danger me-1"></i>Perempuan
                                </label>
                            </div>
                        </div>
                        @error('jenis_kelamin')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                            <input type="date" name="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                   value="{{ old('tanggal_lahir') }}">
                            @error('tanggal_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Tempat Lahir</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                            <input type="text" name="tempat_lahir" class="form-control" 
                                   placeholder="Kota kelahiran" value="{{ old('tempat_lahir') }}">
                        </div>
                    </div>
                </div>

                {{-- Section 2: Kategori & Kontak --}}
                <div class="section-divider">Kategori & Kontak</div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Kategori <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-tags"></i></span>
                            <select name="kategori" class="form-select @error('kategori') is-invalid @enderror">
                                <option value="">Pilih kategori...</option>
                                <option value="balita" {{ old('kategori') == 'balita' ? 'selected' : '' }}>Balita</option>
                                <option value="ibu_hamil" {{ old('kategori') == 'ibu_hamil' ? 'selected' : '' }}>Ibu Hamil</option>
                                <option value="lansia" {{ old('kategori') == 'lansia' ? 'selected' : '' }}>Lansia</option>
                            </select>
                            @error('kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">No. Telepon</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                            <input type="tel" name="telepon" class="form-control @error('telepon') is-invalid @enderror" 
                                   placeholder="08xxxxxxxxxx" value="{{ old('telepon') }}">
                            @error('telepon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" 
                                  rows="3" placeholder="Alamat lengkap">{{ old('alamat') }}</textarea>
                        <div class="form-text">Maksimal 255 karakter</div>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Actions --}}
                <div class="d-flex gap-2 justify-content-end mt-4 pt-3 border-top">
                    <a href="{{ route('warga.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-lg me-1"></i>Batal
                    </a>
                    <button type="reset" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                    </button>
                    <button type="submit" class="btn btn-primary btn-submit">
                        <i class="bi bi-check-lg me-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
```

---

### 5.6 Warga Detail Page

**Route**: `GET /admin/warga/{id}` atau `GET /kader/warga/{id}`
**Controller**: `WargaController@show`
**View**: `resources/views/warga/show.blade.php`

```blade
{{-- show.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Detail Warga - Posyandu Pintar')

@section('content')
    <div class="breadcrumb-area">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('warga.index') }}">Warga</a></li>
                <li class="breadcrumb-item active">{{ $warga->nama }}</li>
            </ol>
        </nav>
        <div class="d-flex gap-2">
            @can('update', $warga)
                <a href="{{ route('warga.edit', $warga->id) }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil me-1"></i>Edit
                </a>
            @endcan
            @can('delete', $warga)
                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="bi bi-trash me-1"></i>Hapus
                </button>
            @endcan
            <a href="{{ route('warga.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>

    {{-- Profile Card --}}
    <div class="profile-card">
        <div class="row align-items-center">
            <div class="col-md-auto text-center mb-3 mb-md-0">
                <div class="profile-avatar">{{ strtoupper(substr($warga->nama, 0, 2)) }}</div>
            </div>
            <div class="col-md">
                <h2 class="profile-name">{{ $warga->nama }}</h2>
                <div class="profile-meta">
                    <span class="badge bg-light text-dark border">
                        <i class="bi bi-credit-card me-1"></i>{{ $warga->nik }}
                    </span>
                    @switch($warga->kategori)
                        @case('balita')
                            <span class="badge-category badge-balita">Balita</span>
                            @break
                        @case('ibu_hamil')
                            <span class="badge-category badge-ibu">Ibu Hamil</span>
                            @break
                        @case('lansia')
                            <span class="badge-category badge-lansia">Lansia</span>
                            @break
                    @endswitch
                    <span class="badge bg-light text-dark border">
                        @if($warga->jenis_kelamin == 'L')
                            <i class="bi bi-gender-male text-primary me-1"></i>Laki-laki
                        @else
                            <i class="bi bi-gender-female text-danger me-1"></i>Perempuan
                        @endif
                    </span>
                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Aktif</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Info Grid --}}
    <div class="profile-card">
        <h5 class="mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>Informasi Lengkap</h5>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Tanggal Lahir</div>
                <div class="info-value">
                    <i class="bi bi-calendar me-1 text-muted"></i>
                    {{ $warga->tanggal_lahir->format('d F Y') }}
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">Umur</div>
                <div class="info-value">
                    <i class="bi bi-clock me-1 text-muted"></i>
                    {{ $warga->tanggal_lahir->age }} Tahun
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">Tempat Lahir</div>
                <div class="info-value">
                    <i class="bi bi-geo-alt me-1 text-muted"></i>
                    {{ $warga->tempat_lahir ?? '-' }}
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">No. Telepon</div>
                <div class="info-value">
                    <i class="bi bi-telephone me-1 text-muted"></i>
                    {{ $warga->telepon ?? '-' }}
                </div>
            </div>
            <div class="info-item" style="grid-column: 1 / -1;">
                <div class="info-label">Alamat</div>
                <div class="info-value">
                    <i class="bi bi-house me-1 text-muted"></i>
                    {{ $warga->alamat ?? '-' }}
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">Terdaftar Pada</div>
                <div class="info-value">
                    <i class="bi bi-clock-history me-1 text-muted"></i>
                    {{ $warga->created_at->format('d F Y') }}
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">Terakhir Diupdate</div>
                <div class="info-value">
                    <i class="bi bi-arrow-repeat me-1 text-muted"></i>
                    {{ $warga->updated_at->format('d F Y') }}
                </div>
            </div>
        </div>
    </div>

    {{-- History Tabs --}}
    <div class="card-table">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="historyTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="penimbangan-tab" data-bs-toggle="tab" data-bs-target="#penimbangan" type="button">
                        <i class="bi bi-clipboard-data me-1"></i>Riwayat Penimbangan
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="imunisasi-tab" data-bs-toggle="tab" data-bs-target="#imunisasi" type="button">
                        <i class="bi bi-shield-plus me-1"></i>Riwayat Imunisasi
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body p-0">
            <div class="tab-content" id="historyTabContent">
                <div class="tab-pane fade show active" id="penimbangan" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Berat Badan</th>
                                    <th>Tinggi Badan</th>
                                    <th>Status Gizi</th>
                                    <th>Petugas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($warga->penimbangans as $index => $penimbangan)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $penimbangan->tanggal->format('d F Y') }}</td>
                                        <td><strong>{{ $penimbangan->berat_badan }} kg</strong></td>
                                        <td>{{ $penimbangan->tinggi_badan }} cm</td>
                                        <td>
                                            @switch($penimbangan->status_gizi)
                                                @case('baik')
                                                    <span class="badge bg-success">Baik</span>
                                                    @break
                                                @case('kurang')
                                                    <span class="badge bg-warning text-dark">Kurang</span>
                                                    @break
                                                @case('buruk')
                                                    <span class="badge bg-danger">Buruk</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>{{ $penimbangan->petugas->nama }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            @include('components.empty-state', ['message' => 'Belum ada riwayat penimbangan'])
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="imunisasi" role="tabpanel">
                    {{-- Similar structure for imunisasi --}}
                </div>
            </div>
        </div>
    </div>

    @include('components.confirm-delete-modal', [
        'id' => 'deleteModal',
        'title' => 'Konfirmasi Hapus',
        'message' => 'Apakah Anda yakin ingin menghapus data ' . $warga->nama . '?',
        'action' => route('warga.destroy', $warga->id)
    ])
@endsection
```

---

### 5.7 Kader List Page

**Route**: `GET /admin/kader` -> `KaderController@index`
**View**: `resources/views/kader/index.blade.php`
**Layout**: `layouts.dashboard`
**Role**: admin only

Similar to Warga List but columns:
- No, Nama (with avatar), Email, No. Telepon, Jabatan, Status, Aksi

```blade
{{-- Jabatan badges --}}
<span class="badge bg-primary">Ketua</span>
<span class="badge bg-info text-dark">Sekretaris</span>
<span class="badge bg-warning text-dark">Bendahara</span>
<span class="badge bg-secondary">Kader</span>

{{-- Status badges --}}
<span class="status-badge status-aktif"><i class="bi bi-check-circle me-1"></i>Aktif</span>
<span class="status-badge status-nonaktif"><i class="bi bi-x-circle me-1"></i>Nonaktif</span>
```

---

### 5.8 Penimbangan List Page

**Route**: `GET /admin/penimbangan` atau `GET /kader/penimbangan`
**Controller**: `PenimbanganController@index`
**View**: `resources/views/penimbangan/index.blade.php`

#### Summary Cards (4 cards):
- Total Bulan Ini
- Status Baik (green)
- Status Kurang (warning)
- Status Buruk (danger)

#### Filter Bar:
- Search nama warga
- Filter tanggal (date range)
- Filter kategori warga

#### Data Table Columns:
- No
- Tanggal
- Nama Warga (link to detail)
- Kategori (badge)
- Berat Badan (kg) - bold
- Tinggi Badan (cm)
- Status Gizi (badge)
  - `baik`: bg-success
  - `kurang`: bg-warning text-dark
  - `buruk`: bg-danger
- Petugas
- Aksi

---

### 5.9 Penimbangan Create/Edit Page

**Route**: `GET|POST /admin/penimbangan/create` -> `PenimbanganController@create|store`
**View**: `resources/views/penimbangan/create.blade.php`

#### Form Fields:
- Warga: Select2/searchable dropdown (fetch from API warga)
  - Display: "NIK - Nama (Kategori)"
  - Required
- Tanggal Penimbangan: date input, default today
- Berat Badan: number input, step 0.1, suffix "kg", required
- Tinggi Badan: number input, step 0.1, suffix "cm", required
- Lingkar Kepala (optional): number, step 0.1, suffix "cm"
- Status Gizi: auto-calculate or select
  - Options: baik, kurang, buruk
- Catatan: textarea

```blade
{{-- Warga preview after selection --}}
<div class="warga-preview" id="wargaPreview">
    <div class="warga-preview-avatar">BS</div>
    <div>
        <div class="fw-bold">Budi Santoso</div>
        <div class="small text-muted">NIK: 3201123456789012 &bull; Balita &bull; Laki-laki</div>
    </div>
</div>
```

---

### 5.10 Imunisasi List Page

**Route**: `GET /admin/imunisasi` atau `GET /kader/imunisasi`
**Controller**: `ImunisasiController@index`
**View**: `resources/views/imunisasi/index.blade.php`

#### Data Table Columns:
- No
- Tanggal
- Nama Warga (link)
- Kategori
- Jenis Imunisasi (badge info)
- Petugas
- Catatan
- Aksi

---

### 5.11 Imunisasi Create/Edit Page

**Route**: `GET|POST /admin/imunisasi/create` -> `ImunisasiController@create|store`
**View**: `resources/views/imunisasi/create.blade.php`

#### Form Fields:
- Warga: searchable dropdown
- Tanggal: date input
- Jenis Imunisasi: grid selection (radio cards)
  - BCG
  - DPT-HB-Hib 1, 2, 3
  - Polio 1, 2, 3, 4
  - Campak
  - MR
  - DT, Td, TT
  - Lainnya
- Petugas: auto-filled from logged user
- Catatan: textarea

```blade
{{-- Vaksin Grid Selection --}}
<div class="vaksin-grid">
    @foreach($jenisImunisasi as $jenis)
        <div class="vaksin-item {{ old('jenis') == $jenis->kode ? 'selected' : '' }}">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="jenis_imunisasi" 
                       id="{{ $jenis->kode }}" value="{{ $jenis->kode }}"
                       {{ old('jenis') == $jenis->kode ? 'checked' : '' }}>
                <label class="form-check-label" for="{{ $jenis->kode }}">{{ $jenis->nama }}</label>
            </div>
            <div class="vaksin-desc">{{ $jenis->deskripsi }}</div>
        </div>
    @endforeach
</div>
```

---

### 5.12 Laporan Pages (Admin)

**Route**: `GET /admin/laporan/*`
**View**: `resources/views/laporan/*.blade.php`

#### Design:
- Filter panel (card): rentang tanggal, kategori, format export
- Summary cards
- Data table (read-only)
- Export buttons: PDF, Excel, Print
- Chart visualizations where applicable

---

### 5.13 404 Page / 403 Page

**Route**: fallback
**View**: `resources/views/errors/404.blade.php` / `resources/views/errors/403.blade.php`

#### 404 Not Found:
- Illustration: `bi-geo-alt` or empty state image
- Title: "Halaman Tidak Ditemukan"
- Text: "Halaman yang Anda cari tidak tersedia."
- Button: "Kembali ke Dashboard"

#### 403 Forbidden:
- Icon: `bi-shield-lock` size 4rem, color warning
- Title: "Akses Ditolak"
- Text: "Anda tidak memiliki izin untuk mengakses halaman ini."
- Button: "Kembali ke Dashboard"

---

## 6. Blade Component Specifications

### 6.1 Sidebar Component

```blade
{{-- resources/views/partials/sidebar.blade.php --}}
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <i class="bi bi-heart-pulse-fill"></i>
        <span>Posyandu Pintar</span>
    </div>

    <nav class="sidebar-nav">
        <ul>
            <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2"></i><span>Dashboard</span>
                </a>
            </li>

            @can('viewAny', App\\Models\\Warga::class)
                <div class="section-title">Data Master</div>
                <li class="{{ request()->routeIs('warga.*') ? 'active' : '' }}">
                    <a href="{{ route('warga.index') }}">
                        <i class="bi bi-people"></i><span>Warga</span>
                    </a>
                </li>
            @endcan

            @can('viewAny', App\\Models\\Kader::class)
                <li class="{{ request()->routeIs('kader.*') ? 'active' : '' }}">
                    <a href="{{ route('kader.index') }}">
                        <i class="bi bi-person-badge"></i><span>Kader</span>
                    </a>
                </li>
            @endcan

            <div class="section-title">Transaksi</div>
            <li class="{{ request()->routeIs('penimbangan.*') ? 'active' : '' }}">
                <a href="{{ route('penimbangan.index') }}">
                    <i class="bi bi-clipboard-data"></i><span>Penimbangan</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('imunisasi.*') ? 'active' : '' }}">
                <a href="{{ route('imunisasi.index') }}">
                    <i class="bi bi-shield-plus"></i><span>Imunisasi</span>
                </a>
            </li>

            @can('admin')
                <div class="section-title">Laporan</div>
                <li class="{{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                    <a href="{{ route('laporan.index') }}">
                        <i class="bi bi-file-earmark-text"></i><span>Laporan</span>
                    </a>
                </li>
            @endcan

            <div class="section-title">Sistem</div>
            <li>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="bi bi-box-arrow-right"></i><span>Logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </nav>

    <div class="sidebar-footer">
        <div class="user-profile">
            <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
            <div class="user-info">
                <span class="name">{{ auth()->user()->name }}</span>
                <span class="role">{{ ucfirst(auth()->user()->role) }}</span>
            </div>
        </div>
    </div>
</aside>
```

### 6.2 Topbar Component

```blade
{{-- resources/views/partials/topbar.blade.php --}}
<header class="topbar">
    <div class="topbar-left">
        <button class="menu-toggle d-lg-none" onclick="document.getElementById('sidebar').classList.toggle('open')">
            <i class="bi bi-list"></i>
        </button>
        <h1 class="page-title">@yield('title', 'Dashboard')</h1>
    </div>
    <div class="topbar-right">
        <div class="dropdown">
            <button class="btn-icon" data-bs-toggle="dropdown">
                <i class="bi bi-bell"></i>
                <span class="badge bg-danger rounded-pill">3</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><h6 class="dropdown-header">Notifikasi</h6></li>
                <li><a class="dropdown-item" href="#">Warga baru mendaftar</a></li>
                <li><a class="dropdown-item" href="#">Jadwal penimbangan hari ini</a></li>
            </ul>
        </div>
        <div class="dropdown">
            <button class="user-toggle" data-bs-toggle="dropdown">
                <div class="avatar" style="width:32px;height:32px;font-size:0.75rem;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <span class="d-none d-md-block fw-medium">{{ auth()->user()->name }}</span>
                <i class="bi bi-chevron-down small"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profil</a></li>
                <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Pengaturan</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</header>
```

### 6.3 DataTable Component

```blade
{{-- resources/views/components/data-table.blade.php --}}
@props(['columns', 'data', 'emptyMessage' => 'Tidak ada data'])

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                @foreach($columns as $column)
                    <th {{ $column['width'] ? 'width="' . $column['width'] . '"' : '' }}>
                        {{ $column['label'] }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($data as $row)
                <tr>
                    @foreach($columns as $column)
                        <td>
                            @if(isset($column['render']))
                                {!! $column['render']($row) !!}
                            @else
                                {{ data_get($row, $column['field']) }}
                            @endif
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($columns) }}" class="text-center py-4">
                        @include('components.empty-state', ['message' => $emptyMessage])
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
```

### 6.4 ModalForm Component

```blade
{{-- resources/views/components/modal-form.blade.php --}}
@props(['id', 'title', 'action', 'method' => 'POST', 'size' => 'lg'])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-{{ $size }} modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ $action }}" method="POST">
                @csrf
                @if($method !== 'POST')
                    @method($method)
                @endif
                <div class="modal-body">
                    {{ $slot }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
```

### 6.5 ConfirmDeleteModal Component

```blade
{{-- resources/views/components/confirm-delete-modal.blade.php --}}
@props(['id', 'title' => 'Konfirmasi Hapus', 'message', 'action'])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size:3rem;"></i>
                <h5 class="mt-3">{{ $title }}</h5>
                <p class="text-muted">{{ $message }} Tindakan ini tidak dapat dibatalkan.</p>
                <div class="d-flex justify-content-center gap-2 mt-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ $action }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-1"></i>Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
```

### 6.6 AlertMessage Component

```blade
{{-- resources/views/components/alert-message.blade.php --}}
@props(['type' => 'info', 'message', 'dismissible' => true])

@php
$icons = [
    'success' => 'bi-check-circle-fill',
    'danger' => 'bi-x-circle-fill',
    'warning' => 'bi-exclamation-triangle-fill',
    'info' => 'bi-info-circle-fill'
];
@endphp

<div class="alert alert-{{ $type }} {{ $dismissible ? 'alert-dismissible fade show' : '' }}" role="alert">
    <i class="bi {{ $icons[$type] }} me-2"></i>{{ $message }}
    @if($dismissible)
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    @endif
</div>
```

### 6.7 LoadingSpinner Component

```blade
{{-- resources/views/components/loading-spinner.blade.php --}}
@props(['size' => 'md', 'text' => null])

<div class="text-center py-4">
    <div class="spinner-border text-primary" style="width: {{ $size == 'sm' ? '1rem' : ($size == 'lg' ? '3rem' : '2rem') }}; height: {{ $size == 'sm' ? '1rem' : ($size == 'lg' ? '3rem' : '2rem') }};" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
    @if($text)
        <p class="text-muted mt-2 mb-0">{{ $text }}</p>
    @endif
</div>
```

### 6.8 EmptyState Component

```blade
{{-- resources/views/components/empty-state.blade.php --}}
@props(['icon' => 'bi-inbox', 'title' => 'Tidak ada data', 'description' => null, 'action' => null])

<div class="empty-state">
    <i class="bi {{ $icon }}"></i>
    <h5 class="mt-3">{{ $title }}</h5>
    @if($description)
        <p class="text-muted">{{ $description }}</p>
    @endif
    @if($action)
        {{ $action }}
    @endif
</div>
```

### 6.9 StatCard Component

```blade
{{-- resources/views/components/stat-card.blade.php --}}
@props(['title', 'value', 'icon', 'color', 'trend' => null])

<div class="stat-card {{ $color }}">
    <div class="stat-icon {{ $color }}">
        <i class="bi {{ $icon }}"></i>
    </div>
    <div>
        <p class="stat-value">{{ $value }}</p>
        <p class="stat-title">{{ $title }}</p>
        @if($trend)
            <span class="stat-trend text-{{ $trend['value'] >= 0 ? 'success' : 'danger' }}">
                <i class="bi bi-arrow-{{ $trend['value'] >= 0 ? 'up' : 'down' }}"></i>
                {{ abs($trend['value']) }}% {{ $trend['label'] }}
            </span>
        @endif
    </div>
</div>
```

### 6.10 FormInput Component

```blade
{{-- resources/views/components/form-input.blade.php --}}
@props(['label', 'name', 'type' => 'text', 'placeholder' => null, 'value' => null, 'icon' => null, 'required' => false, 'help' => null])

<div class="mb-3">
    <label class="form-label">
        {{ $label }}
        @if($required)
            <span class="text-danger">*</span>
        @endif
    </label>
    <div class="input-group">
        @if($icon)
            <span class="input-group-text"><i class="bi {{ $icon }}"></i></span>
        @endif
        <input type="{{ $type }}" name="{{ $name }}" 
               class="form-control @error($name) is-invalid @enderror"
               placeholder="{{ $placeholder }}"
               value="{{ old($name, $value) }}"
               {{ $required ? 'required' : '' }}>
        @error($name)
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    @if($help)
        <div class="form-text">{{ $help }}</div>
    @endif
</div>
```

---

## 7. Responsive Design Rules

### 7.1 Breakpoints

| Breakpoint | Width | Behavior |
|------------|-------|----------|
| Mobile | < 576px | Single column, hidden sidebar, stacked tables |
| Tablet | 576-992px | Overlay sidebar, 2-column grids, horizontal scroll tables |
| Desktop | > 992px | Full sidebar, multi-column, all features visible |

### 7.2 Mobile Adaptations

- **Sidebar**: Hidden by default, hamburger menu toggles overlay
- **Tables**: Horizontal scroll with `table-responsive`, sticky first column for row identity
- **Forms**: Full width inputs, stacked layout
- **Cards**: Single column stack
- **Topbar**: Compact, user name hidden, only avatar
- **Action Buttons**: Icon only on small screens, text + icon on larger
- **Filters**: Collapsible filter panel (accordion)

### 7.3 Touch Targets

- Minimum button size: 44x44px
- Input height: 44px minimum
- Spacing between touch elements: 8px minimum

---

## 8. Interaction Design

### 8.1 Feedback Patterns

| Action | Feedback | Duration |
|--------|----------|----------|
| Save Data | Toast success: "Data berhasil disimpan" | 3s |
| Delete Data | Toast success: "Data berhasil dihapus" | 3s |
| Validation Error | Shake form + highlight fields | instant |
| API Error | Alert danger with message | manual dismiss |
| Loading | Spinner in button / skeleton | until complete |
| Export | Toast info: "Mengunduh file..." | 3s |

### 8.2 Loading States

1. **Page Load**: Skeleton screens for cards and table
2. **Button Load**: Spinner replaces icon, text changes to "Menyimpan..."
3. **Table Load**: Skeleton rows (5 rows)
4. **Select Load**: "Memuat data..." option

### 8.3 Confirmation Patterns

- **Delete**: Always show ConfirmDeleteModal
- **Bulk Delete**: Confirm with count "Hapus 5 data terpilih?"
- **Navigate away from dirty form**: Browser confirm or custom modal
- **Logout**: Confirm modal "Yakin ingin keluar?"

### 8.4 Animation

| Element | Animation | Duration |
|---------|-----------|----------|
| Sidebar open | Slide from left + fade | 300ms ease-out |
| Modal open | Fade + scale from 0.95 | 200ms ease-out |
| Toast | Slide from right + fade | 300ms ease-out |
| Page transition | Fade content | 150ms |
| Row delete | Fade out + collapse height | 300ms |
| Skeleton | Pulse opacity | 1.5s infinite |

---

## 9. State & API Integration (JavaScript Layer)

### 9.1 Auth Flow

```
[Login Form] --(POST /api/auth/login)--> [Store JWT] --> [Fetch Me] --> [Redirect Dashboard]
     |                                              |
     |-- 401 --> [Show Error]                       |-- 401 --> [Redirect Login]
     |-- 422 --> [Show Validation]                    |-- 403 --> [Show 403 Page]
```

### 9.2 Data Fetching Pattern (Repository Pattern in JS)

```javascript
// resources/js/repositories/api.js
const api = axios.create({
    baseURL: '{{ env('API_BASE_URL') }}' || 'http://127.0.0.1:8000/api',
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
    }
});

// Request interceptor - add JWT token
api.interceptors.request.use(config => {
    const token = localStorage.getItem('jwt_token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

// Response interceptor - handle errors
api.interceptors.response.use(
    response => response,
    error => {
        if (error.response?.status === 401) {
            localStorage.removeItem('jwt_token');
            window.location.href = '{{ route('login') }}';
        }
        return Promise.reject(error);
    }
);

// resources/js/repositories/wargaRepository.js
const wargaRepository = {
    getAll: (params) => api.get('/wargas', { params }),
    getById: (id) => api.get(`/wargas/${id}`),
    create: (data) => api.post('/wargas', data),
    update: (id, data) => api.put(`/wargas/${id}`, data),
    delete: (id) => api.delete(`/wargas/${id}`),
    bulkCreate: (data) => api.post('/wargas/bulk', data),
};
```

### 9.3 Error Handling UI

| API Status | UI Behavior |
|------------|-------------|
| 200/201 | Success toast, refresh data |
| 401 | Redirect to /login, clear token |
| 403 | Show 403 page or disable action |
| 404 | Show 404 page or "Data tidak ditemukan" |
| 422 | Show field-level validation errors |
| 500 | Alert danger "Terjadi kesalahan server. Silakan coba lagi." |
| Network Error | Alert warning "Koneksi terputus. Periksa internet Anda." |

---

## 10. Iconography Guide

### 10.1 Feature Icons

| Feature | Icon | Class |
|---------|------|-------|
| Dashboard | bi-speedometer2 | text-primary |
| Warga | bi-people | text-info |
| Kader | bi-person-badge | text-success |
| Penimbangan | bi-clipboard-data | text-warning |
| Imunisasi | bi-shield-plus | text-success |
| Laporan | bi-file-earmark-text | text-secondary |
| Pengaturan | bi-gear | text-secondary |
| Logout | bi-box-arrow-right | text-danger |

### 10.2 Action Icons

| Action | Icon |
|--------|------|
| Tambah | bi-plus-lg |
| Edit | bi-pencil |
| Hapus | bi-trash |
| Detail | bi-eye |
| Simpan | bi-check-lg |
| Batal | bi-x-lg |
| Kembali | bi-arrow-left |
| Cari | bi-search |
| Filter | bi-funnel |
| Export | bi-download |
| Import | bi-upload |
| Reset | bi-arrow-counterclockwise |
| Refresh | bi-arrow-clockwise |
| Print | bi-printer |
| Close | bi-x-lg |
| Notification | bi-bell |
| User | bi-person |
| Calendar | bi-calendar |
| Phone | bi-telephone |
| Address | bi-geo-alt |
| Email | bi-envelope |
| Lock | bi-lock |
| Eye (show pass) | bi-eye |
| Eye slash | bi-eye-slash |

---

## 11. File Structure (Laravel Blade)

```
resources/
├── css/
│   ├── app.css                    # Custom styles & overrides
│   └── variables.css              # CSS variables
├── js/
│   ├── app.js                     # Main JS file
│   ├── bootstrap.js               # Bootstrap JS
│   └── repositories/              # JS API repositories
│       ├── api.js
│       ├── authRepository.js
│       ├── wargaRepository.js
│       ├── kaderRepository.js
│       ├── penimbanganRepository.js
│       ├── imunisasiRepository.js
│       └── publicRepository.js
├── views/
│   ├── layouts/
│   │   ├── dashboard.blade.php    # Main dashboard layout (sidebar + topbar)
│   │   ├── auth.blade.php         # Auth layout (login)
│   │   └── public.blade.php       # Public layout (landing, daftar, cek status)
│   ├── components/
│   │   ├── alert-message.blade.php
│   │   ├── loading-spinner.blade.php
│   │   ├── empty-state.blade.php
│   │   ├── pagination.blade.php
│   │   ├── breadcrumb.blade.php
│   │   ├── stat-card.blade.php
│   │   ├── data-table.blade.php
│   │   ├── modal-form.blade.php
│   │   ├── confirm-delete-modal.blade.php
│   │   ├── form-input.blade.php
│   │   └── kategori-card.blade.php
│   ├── partials/
│   │   ├── sidebar.blade.php
│   │   ├── topbar.blade.php
│   │   ├── footer.blade.php
│   │   ├── navbar-public.blade.php
│   │   └── footer-public.blade.php
│   ├── auth/
│   │   └── login.blade.php
│   ├── dashboard/
│   │   ├── admin.blade.php
│   │   └── kader.blade.php
│   ├── warga/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   ├── edit.blade.php
│   │   └── show.blade.php
│   ├── kader/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   ├── edit.blade.php
│   │   └── show.blade.php
│   ├── penimbangan/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   ├── edit.blade.php
│   │   └── show.blade.php
│   ├── imunisasi/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   ├── edit.blade.php
│   │   └── show.blade.php
│   ├── laporan/
│   │   └── index.blade.php
│   ├── public/                    # Public Portal
│   │   ├── landing.blade.php      # Landing page
│   │   ├── daftar.blade.php       # Pendaftaran mandiri
│   │   ├── cek-status.blade.php   # Cek status pendaftaran
│   │   └── tentang.blade.php      # Tentang Posyandu
│   └── errors/
│       ├── 404.blade.php
│       └── 403.blade.php
```

---

## 12. Role-Based UI Matrix

| Halaman | Admin | Kader | Public |
|---------|-------|-------|--------|
| Dashboard | Full stats + all quick actions | Limited stats + input actions | - |
| Warga List | CRUD + Import/Export | Read-only view | - |
| Warga Create | Yes | No | - |
| Warga Edit | Yes | No | - |
| Warga Delete | Yes | No | - |
| Kader List | CRUD | No access / 403 | - |
| Penimbangan | CRUD + Import | CRUD (own data) | - |
| Imunisasi | CRUD | CRUD (own data) | - |
| Laporan | Full access | Limited / No access | - |
| Pengaturan | Yes | No | - |
| Landing Page | - | - | Full Access |
| Pendaftaran | - | - | Full Access |
| Cek Status | - | - | Full Access |

---

## 13. Form Validation Rules

### 13.1 Warga Form

| Field | Rules | Error Message |
|-------|-------|---------------|
| NIK | required, numeric, exact 16 | "NIK wajib diisi 16 digit angka" |
| Nama | required, min 3, max 100 | "Nama wajib diisi (3-100 karakter)" |
| Jenis Kelamin | required, in [L,P] | "Pilih jenis kelamin" |
| Tanggal Lahir | required, date, not future | "Tanggal lahir tidak valid" |
| Kategori | required, in [balita,ibu_hamil,lansia] | "Pilih kategori" |
| Telepon | nullable, regex /^08[0-9]{8,12}$/ | "Format nomor telepon tidak valid" |
| Alamat | nullable, max 255 | "Alamat maksimal 255 karakter" |

### 13.2 Kader Form

| Field | Rules | Error Message |
|-------|-------|---------------|
| Nama | required, min 3, max 100 | "Nama wajib diisi" |
| Email | required, email | "Format email tidak valid" |
| Password | required (create), min 6 | "Password minimal 6 karakter" |
| Telepon | required, regex phone | "Nomor telepon tidak valid" |
| Jabatan | required | "Jabatan wajib diisi" |

### 13.3 Penimbangan Form

| Field | Rules | Error Message |
|-------|-------|---------------|
| Warga ID | required, exists | "Pilih warga" |
| Tanggal | required, date | "Tanggal wajib diisi" |
| Berat Badan | required, numeric, min 0.1, max 300 | "Berat badan tidak valid (0.1-300 kg)" |
| Tinggi Badan | required, numeric, min 1, max 300 | "Tinggi badan tidak valid (1-300 cm)" |
| Status Gizi | required, in [baik,kurang,buruk] | "Pilih status gizi" |

### 13.4 Imunisasi Form

| Field | Rules | Error Message |
|-------|-------|---------------|
| Warga ID | required, exists | "Pilih warga" |
| Tanggal | required, date | "Tanggal wajib diisi" |
| Jenis Imunisasi | required | "Pilih jenis imunisasi" |

---

## 14. Public Portal (Pendaftaran Mandiri Warga)

> **Fitur Baru**: Portal publik untuk memudahkan warga mendaftar sendiri tanpa harus datang ke Posyandu. Warga bisa mendaftarkan diri sendiri atau anak/balitanya secara online.

### 14.1 Konsep

Aplikasi Posyandu Pintar memiliki 2 sisi:
1. **Sisi Admin/Kader** (Dashboard) - Untuk petugas mengelola data
2. **Sisi Publik/Warga** (Portal) - Untuk warga mendaftar & cek status

### 14.2 Public Routes (Tanpa Login)

| Route | Halaman | Deskripsi |
|-------|---------|-----------|
| `/` | LandingPage | Halaman utama publik |
| `/daftar` | PendaftaranWarga | Form pendaftaran mandiri |
| `/cek-status` | CekStatusPage | Cek status pendaftaran |
| `/tentang` | TentangPage | Informasi Posyandu |

### 14.3 Landing Page Publik

**Route**: `/`
**Layout**: `layouts.public`
**Background**: Gradient biru, clean, modern

#### Sections:
1. **Hero Section**:
   - Title besar: "Posyandu Pintar"
   - Subtitle: penjelasan layanan
   - 2 CTA buttons: "Daftar Sekarang" (primary light) + "Cek Data Saya" (outline light)
   - Background: gradient `#0d6efd` -> `#1e293b`

2. **Features Section** (4 cards):
   - Pendaftaran Online (icon `bi-person-plus`, color blue)
   - Cek Data Kesehatan (icon `bi-clipboard-data`, color green)
   - Reminder Jadwal (icon `bi-bell`, color orange)
   - Grafik Pertumbuhan (icon `bi-graph-up`, color red)

3. **How It Works Section** (4 steps):
   - Step 1: Daftar Online
   - Step 2: Verifikasi Data
   - Step 3: Ikut Jadwal Posyandu
   - Step 4: Pantau Perkembangan
   - Numbered timeline dengan icon

4. **CTA Section**:
   - Background dark
   - "Siap Mendaftar?"
   - Button: "Daftar Gratis Sekarang"

5. **Footer**:
   - Logo + copyright
   - Simple, minimal

#### Navbar Public:
- Brand: Logo + "Posyandu Pintar"
- Links: Beranda, Daftar, Cek Status, Tentang
- Button: "Login Petugas" -> redirect ke `/login`
- Sticky top, white background, border-bottom
- Mobile: hamburger menu

---

### 14.4 Pendaftaran Mandiri Warga

**Route**: `/daftar`
**Layout**: `layouts.public`
**Background**: Light gradient `#f1f5f9` -> `#e2e8f0`

#### Page Structure:
1. **Hero mini**: "Pendaftaran Warga Posyandu" + subtitle
2. **Form Card** (max-width 900px, centered):

#### Form Sections:

**Section 1: Pilih Kategori** (Card selection)
- 3 option cards side by side:
  - **Balita** (icon `bi-baby`, blue): "Untuk anak usia dini"
  - **Ibu Hamil** (icon `bi-person-heart`, red): "Untuk ibu hamil & menyusui"
  - **Lansia** (icon `bi-person-walking`, green): "Untuk lansia & dewasa"
- Selection state: border `#0d6efd`, background `#e7f1ff`
- Click to select, radio button hidden

**Section 2: Data Pribadi**
- NIK: text input, placeholder "3201xxxxxxxxxxxx", required
  - Validation: 16 digit numeric
  - Help text: "16 digit nomor KTP/KK"
- Nama Lengkap: required
- Jenis Kelamin: radio L/P with icons
- Tanggal Lahir: date input, required
- Tempat Lahir: optional
- No. WhatsApp: tel input, required
  - Help text: "Untuk konfirmasi & reminder jadwal"

**Section 3: Data Tambahan**
- Alamat Lengkap: textarea, required
- Nama Orang Tua / Wali: text (wajib untuk balita)
- No. HP Orang Tua / Wali: tel (wajib untuk balita)

**Section 4: Persetujuan**
- Checkbox: "Saya menyetujui data yang saya isi adalah benar..."

**Section 5: Tombol Aksi**
- Submit: "Kirim Pendaftaran" `bi-send-check` btn-primary btn-lg
- Reset: "Reset" `bi-arrow-counterclockwise` btn-outline-secondary

#### Info Box:
- Alert info di atas form:
  - Icon `bi-info-circle-fill`
  - Text: "Setelah mendaftar, data Anda akan diverifikasi oleh petugas Posyandu. Anda akan menerima konfirmasi melalui WhatsApp/SMS."

#### Success State (after submit):
- Hide form, show success card:
  - Icon: `bi-check-lg` in green circle, size 80px
  - Title: "Pendaftaran Berhasil!"
  - Text: "Data Anda telah terkirim. Petugas Posyandu akan menghubungi Anda dalam 1-2 hari kerja."
  - Buttons:
    - "Kembali" (outline)
    - "Cek Status Pendaftaran" (primary)

#### Validation Rules:
| Field | Rules | Error Message |
|-------|-------|---------------|
| NIK | required, 16 digit numeric | "NIK harus 16 digit angka" |
| Nama | required, min 3 | "Nama wajib diisi" |
| JK | required | "Pilih jenis kelamin" |
| Tgl Lahir | required, not future | "Tanggal lahir tidak valid" |
| WhatsApp | required, valid phone | "Nomor WhatsApp tidak valid" |
| Alamat | required, min 10 | "Alamat wajib diisi" |
| Ortu/Wali | required if balita | "Wajib diisi untuk pendaftaran balita" |
| Persetujuan | checked | "Anda harus menyetujui" |

---

### 14.5 Cek Status Pendaftaran

**Route**: `/cek-status`
**Layout**: `layouts.public`

#### Page Structure:
1. **Hero mini**: "Cek Status Pendaftaran"
2. **Search Box** (centered, max-width 600px):
   - Input group large: icon `bi-search` + input + button `bi-arrow-right`
   - Placeholder: "Masukkan NIK atau No. WhatsApp..."

3. **Result Card** (shown after search):
   - Header: Nama + NIK + Kategori + Status badge
   - Status badges:
     - `menunggu`: bg-warning text-dark "Menunggu Verifikasi"
     - `diverifikasi`: bg-success "Terverifikasi"
     - `ditolak`: bg-danger "Ditolak"

   - **Timeline Status** (vertical):
     ```
     [dot] Pendaftaran Dikirim     - 18 Mei 2024, 09:30
     [dot] Sedang Ditinjau         - 18 Mei 2024, 10:15
     [dot active] Terverifikasi    - 19 Mei 2024, 08:00
     ```
     - Dots: completed (green), active (blue), pending (gray)
     - Line connecting dots

   - **Info Alert** (if verified):
     - "Jadwal Posyandu: Setiap hari Selasa & Kamis, 08:00 - 12:00 WIB. Lokasi: Balai Desa Mekar Jaya."

   - Empty state (if not found):
     - Icon: `bi-search` gray, size 4rem
     - "Data tidak ditemukan"
     - "Pastikan NIK atau nomor WhatsApp sudah benar"

---

### 14.6 API Integration untuk Public Portal

```javascript
// resources/js/repositories/publicRepository.js
const publicRepository = {
    // Pendaftaran mandiri warga
    registerWarga: (data) => api.post('/public/wargas/register', data),

    // Cek status pendaftaran
    checkStatus: (identifier) => api.get(`/public/wargas/status?search=${identifier}`),

    // Get public data (landing page stats)
    getPublicStats: () => api.get('/public/stats'),
};
```

> **Catatan**: Endpoint `/api/public/wargas` sudah tersedia di backend (lihat AGENTS.md). Untuk pendaftaran mandiri, gunakan endpoint yang sudah ada atau diskusikan dengan dosen/backend developer jika perlu endpoint baru.

---

### 14.7 Role-Based Access untuk Public Portal

| Halaman | Public (No Login) | Admin | Kader |
|---------|-------------------|-------|-------|
| Landing Page | Full Access | Redirect ke dashboard | Redirect ke dashboard |
| Pendaftaran | Full Access | Bisa daftarkan warga | Bisa daftarkan warga |
| Cek Status | Full Access | Full Access | Full Access |
| Dashboard | Redirect login | Full | Limited |

---

### 14.8 Public Portal Design Rules

1. **No Authentication Required**: Public pages tidak perlu login
2. **Simple & Friendly**: UI yang ramah untuk warga umum (bukan teknis)
3. **Mobile First**: Banyak warga akan akses via HP
4. **WhatsApp Integration**: Gunakan format nomor WA Indonesia (+62/08)
5. **Clear Feedback**: Status pendaftaran harus jelas dan transparan
6. **Trust Signals**: Tampilkan info kontak Posyandu, alamat, jam operasional
7. **Accessibility**: Font size minimal 16px, contrast tinggi

---

### 14.9 Checklist Public Portal

- [ ] Landing page dengan hero, features, how-it-works, CTA
- [ ] Navbar public dengan link navigasi
- [ ] Form pendaftaran mandiri (3 kategori)
- [ ] Validasi form frontend
- [ ] Success state setelah submit
- [ ] Halaman cek status dengan timeline
- [ ] Empty state jika data tidak ditemukan
- [ ] Integrasi API public endpoints
- [ ] Responsive design (mobile first)
- [ ] Footer dengan info kontak Posyandu

---

## 15. Checklist Implementasi UI (Blade)

### Setup
- [ ] Install Laravel 12 + Bootstrap 5 + Bootstrap Icons
- [ ] Setup folder structure sesuai desain.md (views, components, partials, layouts)
- [ ] Setup CSS variables (variables.css)
- [ ] Setup JS repositories (api.js + all repositories)
- [ ] Setup Auth middleware dan role middleware
- [ ] Setup route groups (admin, kader, public)

### Layout
- [ ] Buat layouts/dashboard.blade.php (sidebar + topbar + content)
- [ ] Buat layouts/auth.blade.php (login page centered)
- [ ] Buat layouts/public.blade.php (navbar + footer)
- [ ] Implementasi Sidebar responsive (desktop/mobile)
- [ ] Implementasi Topbar dengan user dropdown
- [ ] Implementasi Breadcrumb

### Components
- [ ] AlertMessage (success, error, warning, info)
- [ ] LoadingSpinner (inline & full page)
- [ ] EmptyState
- [ ] StatCard (4 variants)
- [ ] DataTable (sortable, loading, empty)
- [ ] ModalForm
- [ ] ConfirmDeleteModal
- [ ] FormInput (with icon, suffix, validation)
- [ ] FormSelect
- [ ] Pagination
- [ ] KategoriCard (for public portal)

### Pages - Auth
- [ ] Login page dengan validasi
- [ ] Logout functionality

### Pages - Dashboard
- [ ] AdminDashboard dengan stat cards
- [ ] KaderDashboard dengan stat cards
- [ ] Quick action buttons

### Pages - Warga
- [ ] WargaList (table, filter, search, pagination)
- [ ] WargaCreate (form with validation)
- [ ] WargaEdit (prefilled form)
- [ ] WargaDetail (profile + history tabs)

### Pages - Kader
- [ ] KaderList (admin only)
- [ ] KaderCreate
- [ ] KaderEdit
- [ ] KaderDetail

### Pages - Penimbangan
- [ ] PenimbanganList
- [ ] PenimbanganCreate (with warga search)
- [ ] PenimbanganEdit
- [ ] PenimbanganDetail

### Pages - Imunisasi
- [ ] ImunisasiList
- [ ] ImunisasiCreate
- [ ] ImunisasiEdit
- [ ] ImunisasiDetail

### Pages - Public Portal
- [ ] LandingPage (hero, features, how-it-works, CTA)
- [ ] PendaftaranWarga (form kategori cards)
- [ ] CekStatusPage (search + timeline)
- [ ] TentangPage

### Pages - Errors
- [ ] 404 NotFound
- [ ] 403 Forbidden

### Polish
- [ ] Responsive testing (mobile, tablet, desktop)
- [ ] Loading states (skeleton, spinner)
- [ ] Empty states
- [ ] Toast notifications
- [ ] Confirm dialogs
- [ ] Role-based menu hiding
- [ ] Role-based route protection
- [ ] Public portal no-auth access
- [ ] Form validation feedback

---

## 16. Catatan Penting untuk Copilot/Agent

1. **JANGAN ubah backend** - Semua endpoint sudah final di AGENTS.md
2. **Gunakan repository pattern** - Jangan fetch/axios langsung di blade, gunakan JS layer
3. **Bootstrap 5 wajib** - Gunakan class utilities, jangan CSS custom berlebihan
4. **Bootstrap Icons** - Gunakan prefix `bi-*`, jangan mix dengan Font Awesome
5. **Responsive first** - Test mobile layout, gunakan `table-responsive`, `d-none d-md-block`
6. **JWT di localStorage** - Simpan token, kirim Bearer di setiap request via JS
7. **API Key** - Header `X-API-Key` untuk endpoint public (jika diperlukan)
8. **Role check** - Selalu cek role sebelum render menu atau akses halaman
9. **Error handling** - 401 redirect login, 403 tampilkan forbidden page
10. **Form validation** - Frontend validation sesuai backend, tampilkan pesan error per field
11. **NIK 16 digit** - Validasi exact 16 digit numeric
12. **Kategori enum** - Hanya balita, ibu_hamil, lansia
13. **Jenis Kelamin** - Hanya L atau P
14. **Tanggal format** - Gunakan YYYY-MM-DD untuk input date
15. **Currency/Number** - Format angka dengan toLocaleString('id-ID')
16. **Date display** - Format: "15 Mei 2024" menggunakan toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })
17. **Skeleton loading** - Gunakan Bootstrap placeholder classes untuk skeleton
18. **Modal size** - Gunakan modal-lg untuk form kompleks, modal-md untuk delete confirm
19. **Sidebar active state** - Cocokkan dengan current route, gunakan request()->routeIs()
20. **Avatar fallback** - Jika tidak ada foto, tampilkan inisial nama dengan background color deterministik
21. **Blade components** - Gunakan @props untuk passing data ke component
22. **Old input** - Selalu gunakan old() untuk mempertahankan input setelah validasi gagal
23. **CSRF token** - Selalu sertakan @csrf di setiap form
24. **Method spoofing** - Gunakan @method('PUT') atau @method('DELETE') untuk RESTful routes
25. **Public Portal** - Halaman publik tidak memerlukan autentikasi, gunakan layout terpisah
26. **Pendaftaran mandiri** - Warga bisa daftar tanpa login, data masuk ke approval queue
27. **Cek status** - Gunakan NIK atau WhatsApp sebagai identifier publik
28. **Landing page** - Desain yang menarik dan informatif untuk menarik warga mendaftar
29. **Blade @auth/@guest** - Gunakan directive ini untuk bedakan konten authenticated vs public
30. **Route model binding** - Gunakan Route Model Binding Laravel untuk cleaner controller

---

## 17. Daftar Mockup UI Lengkap

| No | Mockup File | Halaman | Deskripsi |
|----|-------------|---------|-----------|
| 1 | `mockup-login.html` | Login | Halaman login dengan gradient background |
| 2 | `mockup-dashboard-admin.html` | Dashboard Admin | Stat cards, quick actions, recent activity |
| 3 | `mockup-dashboard-kader.html` | Dashboard Kader | Stat cards, jadwal hari ini, aktivitas |
| 4 | `mockup-warga-list.html` | Data Warga (List) | Filter bar, data table, pagination |
| 5 | `mockup-warga-form.html` | Form Warga | Create/edit form dengan validation states |
| 6 | `mockup-warga-detail.html` | Detail Warga | Profile card, info grid, history tabs |
| 7 | `mockup-penimbangan-list.html` | Data Penimbangan | Summary cards, filter, data table |
| 8 | `mockup-penimbangan-form.html` | Input Penimbangan | Form dengan warga search dropdown |
| 9 | `mockup-imunisasi-list.html` | Data Imunisasi | Filter by jenis, data table |
| 10 | `mockup-imunisasi-form.html` | Input Imunisasi | Grid selection jenis vaksin |
| 11 | `mockup-kader-list.html` | Data Kader | Avatar list, status badge, jabatan |
| 12 | `mockup-landing-public.html` | Landing Page Publik | Hero, features, how-it-works, CTA |
| 13 | `mockup-pendaftaran-warga.html` | Pendaftaran Mandiri | Kategori cards, form lengkap |
| 14 | `mockup-cek-status.html` | Cek Status Pendaftaran | Search box, timeline status |

---

*Dokumen ini adalah panduan lengkap untuk implementasi frontend Posyandu Pintar menggunakan Laravel Blade PHP. Ikuti secara konsisten untuk menghasilkan UI yang rapi, responsif, dan sesuai requirement UAS.*
