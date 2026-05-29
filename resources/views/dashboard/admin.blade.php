@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')

@section('content')
<div class="breadcrumb-area">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ url('/admin/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Ringkasan</li>
        </ol>
    </nav>
    <div><span class="text-muted small" id="adminDashboardDate">-</span></div>
</div>

<div class="quick-actions">
    <a href="{{ url('/admin/warga/create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i>Tambah Warga
    </a>
    <a href="{{ url('/admin/penimbangan/create') }}" class="btn btn-success">
        <i class="bi bi-clipboard-data me-2"></i>Tambah Penimbangan
    </a>
    <a href="{{ url('/admin/imunisasi/create') }}" class="btn btn-info text-white">
        <i class="bi bi-shield-plus me-2"></i>Tambah Imunisasi
    </a>
    <a href="#" id="adminDashboardSync" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-repeat me-2"></i>Sinkron
    </a>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <x-stat-card title="Total Warga" value="0" valueId="adminTotalWarga" icon="bi-people" color="primary" />
    </div>
    <div class="col-xl-3 col-md-6">
        <x-stat-card title="Total Kader" value="0" valueId="adminTotalKader" icon="bi-person-badge" color="success" />
    </div>
    <div class="col-xl-3 col-md-6">
        <x-stat-card title="Total Penimbangan" value="0" valueId="adminTotalPenimbangan" icon="bi-clipboard-data" color="warning" />
    </div>
    <div class="col-xl-3 col-md-6">
        <x-stat-card title="Total Imunisasi" value="0" valueId="adminTotalImunisasi" icon="bi-shield-plus" color="info" />
    </div>
</div>

<div id="adminDashboardAlert" class="alert alert-danger d-none" role="alert"></div>

<div class="card-table">
    <div class="card-header">
        <h5 class="card-title"><i class="bi bi-clock-history me-2 text-primary"></i>Aktivitas Terbaru</h5>
        <a href="#" class="btn btn-sm btn-outline-primary">Lihat Detail</a>
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
            <tbody id="adminRecentActivityTableBody">
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">Memuat aktivitas terbaru...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
