@extends('layouts.dashboard')

@section('title', 'Detail Warga')

@section('content')
@php $base = (auth()->check() && auth()->user()->role === 'kader') ? '/kader' : '/admin'; @endphp

<div class="breadcrumb-area">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ url($base . '/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ url($base . '/warga') }}">Warga</a></li>
            <li class="breadcrumb-item active" id="wargaBreadcrumbName">Detail</li>
        </ol>
    </nav>
    <div class="d-flex gap-2">
        <a href="#" class="btn btn-warning btn-sm" id="wargaEditLink"><i class="bi bi-pencil me-1"></i>Edit</a>
        <a href="#" class="btn btn-danger btn-sm admin-only" id="wargaDeleteLink"><i class="bi bi-trash me-1"></i>Hapus</a>
        <a href="{{ url($base . '/warga') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
    </div>
</div>

<div class="profile-card" id="wargaDetail">
    <div class="row align-items-center">
        <div class="col-md-auto text-center mb-3 mb-md-0">
            <div class="profile-avatar" id="wargaAvatar">--</div>
        </div>
        <div class="col-md">
            <h2 class="profile-name" data-field="nama_lengkap">-</h2>
            <div class="profile-meta">
                <span class="badge bg-light text-dark border"><i class="bi bi-credit-card me-1"></i><span data-field="nik">-</span></span>
                <span class="badge-category" id="wargaKategoriBadge">-</span>
                <span class="badge bg-light text-dark border" id="wargaGenderBadge">-</span>
                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Aktif</span>
            </div>
        </div>
    </div>
</div>

<div class="profile-card" id="wargaInfoDetail">
    <h5 class="mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>Informasi Lengkap</h5>
    <div class="info-grid">
        <div class="info-item">
            <div class="info-label">Tanggal Lahir</div>
            <div class="info-value"><i class="bi bi-calendar me-1 text-muted"></i><span data-field="tanggal_lahir">-</span></div>
        </div>
        <div class="info-item">
            <div class="info-label">Umur</div>
            <div class="info-value"><span id="wargaUmur">-</span></div>
        </div>
        <div class="info-item">
            <div class="info-label">RT/RW</div>
            <div class="info-value"><i class="bi bi-geo-alt me-1 text-muted"></i><span data-field="rt_rw">-</span></div>
        </div>
        <div class="info-item">
            <div class="info-label">Jadwal Posyandu</div>
            <div class="info-value"><span id="wargaJadwalInfo">-</span></div>
        </div>
        <div class="info-item">
            <div class="info-label">Terakhir Diupdate</div>
            <div class="info-value"><i class="bi bi-arrow-repeat me-1 text-muted"></i><span data-field="updated_at">-</span></div>
        </div>
        <div class="info-item" style="grid-column: 1 / -1;">
            <div class="info-label">Alamat</div>
            <div class="info-value"><i class="bi bi-house me-1 text-muted"></i><span data-field="alamat">-</span></div>
        </div>
    </div>
</div>

<div class="card-table">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs">
            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#penimbangan" type="button">
                    <i class="bi bi-clipboard-data me-1"></i>Riwayat Penimbangan
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#imunisasi" type="button">
                    <i class="bi bi-shield-plus me-1"></i>Riwayat Imunisasi
                </button>
            </li>
        </ul>
    </div>
    <div class="card-body p-0">
        <div class="tab-content">
            <div class="tab-pane fade show active" id="penimbangan">
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
                        <tbody id="wargaPenimbanganBody"></tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="imunisasi">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Jenis Imunisasi</th>
                                <th>Petugas</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody id="wargaImunisasiBody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
