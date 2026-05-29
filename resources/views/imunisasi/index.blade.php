@extends('layouts.dashboard')

@section('title', 'Data Imunisasi')

@section('content')
@php $base = (auth()->check() && auth()->user()->role === 'kader') ? '/kader' : '/admin'; @endphp
<div class="breadcrumb-area">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ url($base . '/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Imunisasi</li>
        </ol>
    </nav>
    <a href="{{ url($base . '/imunisasi/create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Input Imunisasi</a>
</div>

<div class="filter-bar">
    <form class="row g-3 align-items-end" id="imunisasiFilterForm">
        <div class="col-md-4">
            <label class="form-label small text-muted">Cari Nama Warga</label>
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Nama warga...">
            </div>
        </div>
        <div class="col-md-3">
            <label class="form-label small text-muted">Jenis Imunisasi</label>
            <select name="jenis_imunisasi" class="form-select">
                <option value="">Semua</option>
                <option>BCG</option>
                <option>DPT-HB-Hib</option>
                <option>Campak</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label small text-muted">Tanggal</label>
            <input type="date" name="tanggal_pemberian" class="form-control">
        </div>
        <div class="col-md-2 d-grid gap-2">
            <button class="btn btn-primary" type="submit">
                <i class="bi bi-funnel me-1"></i>Filter
            </button>
            <button class="btn btn-outline-secondary" type="button" id="imunisasiResetFilter">Reset</button>
        </div>
    </form>
</div>

<div id="imunisasiAlert" class="alert alert-danger d-none" role="alert"></div>

<div class="card-table">
    <div class="card-header">
        <h5 class="card-title"><i class="bi bi-shield-plus me-2 text-info"></i>Daftar Imunisasi</h5>
        <span class="text-muted small" id="imunisasiSummary">Memuat data...</span>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nama Warga</th>
                    <th>Kategori</th>
                    <th>Jenis Imunisasi</th>
                    <th>Petugas</th>
                    <th>Catatan</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="imunisasiTableBody"></tbody>
        </table>
    </div>
</div>
@endsection
