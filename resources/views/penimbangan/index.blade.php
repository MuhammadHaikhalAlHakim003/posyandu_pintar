@extends('layouts.dashboard')

@section('title', 'Data Penimbangan')

@section('content')
@php $base = (auth()->check() && auth()->user()->role === 'kader') ? '/kader' : '/admin'; @endphp
<div class="breadcrumb-area">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ url($base . '/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Penimbangan</li>
        </ol>
    </nav>
    <a href="{{ url($base . '/penimbangan/create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Input Penimbangan</a>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <x-stat-card title="Total Bulan Ini" value="0" valueId="penimbanganTotalBulanIni" icon="bi-clipboard-data" color="primary" />
    </div>
    <div class="col-xl-3 col-md-6">
        <x-stat-card title="Status Baik" value="0" valueId="penimbanganStatusBaik" icon="bi-check-circle" color="success" />
    </div>
    <div class="col-xl-3 col-md-6">
        <x-stat-card title="Status Kurang" value="0" valueId="penimbanganStatusKurang" icon="bi-exclamation-circle" color="warning" />
    </div>
    <div class="col-xl-3 col-md-6">
        <x-stat-card title="Status Buruk" value="0" valueId="penimbanganStatusBuruk" icon="bi-x-circle" color="info" />
    </div>
</div>

<div class="filter-bar">
    <form class="row g-3 align-items-end" id="penimbanganFilterForm">
        <div class="col-md-4">
            <label class="form-label small text-muted">Cari Nama Warga</label>
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Nama warga...">
            </div>
        </div>
        <div class="col-md-3">
            <label class="form-label small text-muted">Rentang Tanggal</label>
            <input type="date" name="tanggal" class="form-control">
        </div>
        <div class="col-md-3">
            <label class="form-label small text-muted">Kategori</label>
            <select name="kategori" class="form-select">
                <option value="">Semua</option>
                <option value="balita">Balita</option>
                <option value="ibu_hamil">Ibu Hamil</option>
                <option value="lansia">Lansia</option>
            </select>
        </div>
        <div class="col-md-2 d-grid gap-2">
            <button class="btn btn-primary" type="submit">
                <i class="bi bi-funnel me-1"></i>Filter
            </button>
            <button class="btn btn-outline-secondary" type="button" id="penimbanganResetFilter">Reset</button>
        </div>
    </form>
</div>

<div id="penimbanganAlert" class="alert alert-danger d-none" role="alert"></div>

<div class="card-table">
    <div class="card-header">
        <h5 class="card-title"><i class="bi bi-clipboard-data me-2 text-warning"></i>Daftar Penimbangan</h5>
        <span class="text-muted small" id="penimbanganSummary">Memuat data...</span>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nama Warga</th>
                    <th>Kategori</th>
                    <th>Berat</th>
                    <th>Tinggi</th>
                    <th>Lingkar Kepala</th>
                    <th>Tekanan Darah</th>
                    <th>Lingkar Lengan Atas</th>
                    <th>Lingkar Perut</th>
                    <th>Kolesterol</th>
                    <th>Asam Urat</th>
                    <th>Keterangan</th>
                    <th>Status Gizi</th>
                    <th>Petugas</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="penimbanganTableBody"></tbody>
        </table>
    </div>
</div>
@endsection
