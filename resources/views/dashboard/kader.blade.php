@extends('layouts.dashboard')

@section('title', 'Panel Kader')

@section('content')
<div class="breadcrumb-area">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ url('/kader/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Ringkasan</li>
        </ol>
    </nav>
    <div><span class="text-muted small" id="kaderDashboardDate">-</span></div>
</div>
<div id="kaderDashboardAlert" class="alert alert-danger d-none mt-3" role="alert"></div>

<div class="quick-actions">
    <a href="{{ url('/kader/penimbangan/create') }}" class="btn btn-success">
        <i class="bi bi-clipboard-data me-2"></i>Tambah Penimbangan
    </a>
    <a href="{{ url('/kader/imunisasi/create') }}" class="btn btn-info text-white">
        <i class="bi bi-shield-plus me-2"></i>Tambah Imunisasi
    </a>
    <a href="{{ url('/kader/warga') }}" class="btn btn-outline-secondary">
        <i class="bi bi-search me-2"></i>Cari Warga
    </a>
</div>

    <div class="row g-4 mb-4">
    <div class="col-xl-4 col-md-6">
        <x-stat-card title="Total Warga" value="0" valueId="kaderTotalWarga" icon="bi-people" color="primary" :trend="['value' => 0, 'label' => 'bulan ini']" />
    </div>
    <div class="col-xl-4 col-md-6">
        <x-stat-card title="Penimbangan Bulan Ini" value="0" valueId="kaderPenimbanganBulanIni" icon="bi-clipboard-data" color="warning" :trend="['value' => 0, 'label' => 'minggu ini']" />
    </div>
    <div class="col-xl-4 col-md-6">
        <x-stat-card title="Imunisasi Bulan Ini" value="0" valueId="kaderImunisasiBulanIni" icon="bi-shield-plus" color="info" :trend="['value' => 0, 'label' => 'minggu ini']" />
    </div>
</div>

<div class="card-table">
    <div class="card-header">
        <h5 class="card-title"><i class="bi bi-activity me-2 text-primary"></i>Aktivitas Hari Ini</h5>
        <span class="text-muted small">Data terbaru</span>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Warga</th>
                    <th>Kategori</th>
                    <th>Tanggal Penimbangan</th>
                    <th>Status Gizi</th>
                </tr>
            </thead>
            <tbody id="kaderTodayScheduleBody">
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">Memuat aktivitas hari ini...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
