@extends('layouts.dashboard')

@section('title', 'Data Warga')

@section('content')
@php $base = (auth()->check() && auth()->user()->role === 'kader') ? '/kader' : '/admin'; @endphp
<div class="breadcrumb-area">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ url($base . '/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Warga</li>
        </ol>
    </nav>
    <div class="d-flex gap-2">
        <a href="{{ url($base . '/warga/create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Tambah Warga
        </a>
        <a href="#" class="btn btn-outline-secondary btn-sm admin-only">
            <i class="bi bi-upload me-1"></i>Import
        </a>
        <a href="#" id="wargaExportBtn" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-download me-1"></i>Export
        </a>
    </div>
</div>

<div class="filter-bar">
    <form class="row g-3 align-items-end" id="wargaFilterForm">
        <div class="col-md-4">
            <label class="form-label small text-muted">Cari</label>
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" name="search" placeholder="Cari nama atau NIK...">
            </div>
        </div>
        <div class="col-md-3">
            <label class="form-label small text-muted">Kategori</label>
            <select class="form-select" name="kategori">
                <option value="">Semua Kategori</option>
                <option value="balita">Balita</option>
                <option value="ibu_hamil">Ibu Hamil</option>
                <option value="lansia">Lansia</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label small text-muted">Jenis Kelamin</label>
            <select class="form-select" name="jenis_kelamin">
                <option value="">Semua</option>
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
            </select>
        </div>
        <div class="col-md-2 d-grid gap-2">
            <button class="btn btn-primary" type="submit">
                <i class="bi bi-funnel me-1"></i>Filter
            </button>
            <button class="btn btn-outline-secondary" type="button" id="wargaResetFilter">
                <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
            </button>
        </div>
    </form>
</div>

<div id="wargaAlert" class="alert alert-danger d-none" role="alert"></div>

<div class="card-table">
    <div class="card-header">
        <h5 class="card-title"><i class="bi bi-people me-2 text-primary"></i>Daftar Warga</h5>
        <span class="text-muted small" id="wargaSummary">Memuat data...</span>
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
            <tbody id="wargaTableBody"></tbody>
        </table>
    </div>
    <div class="pagination-area">
        <div class="d-flex align-items-center gap-2">
            <select class="form-select form-select-sm" style="width:70px" id="wargaPerPage">
                <option value="10" selected>10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
            <span class="text-muted">data per halaman</span>
        </div>
        <nav>
            <ul class="pagination pagination-sm mb-0" id="wargaPagination"></ul>
        </nav>
    </div>
</div>
@endsection
