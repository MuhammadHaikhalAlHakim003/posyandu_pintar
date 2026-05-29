@extends('layouts.dashboard')

@section('title', 'Jadwal Posyandu')

@section('content')
@php $base = (auth()->check() && auth()->user()->role === 'kader') ? '/kader' : '/admin'; @endphp
<div class="breadcrumb-area">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ url($base . '/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ url($base . '/jadwal-posyandu') }}">Jadwal Posyandu</a></li>
            <li class="breadcrumb-item active">Jadwal Posyandu</li>
        </ol>
    </nav>
    <div class="d-flex gap-2">
        <a href="{{ url($base . '/jadwal-posyandu') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-clockwise me-1"></i>Muat Ulang
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <x-stat-card title="Total Jadwal" value="0" valueId="jadwalPosyanduTotal" icon="bi-calendar-week" color="primary" />
    </div>
    <div class="col-xl-3 col-md-6">
        <x-stat-card title="Balita" value="0" valueId="jadwalPosyanduBalita" icon="bi-baby" color="success" />
    </div>
    <div class="col-xl-3 col-md-6">
        <x-stat-card title="Ibu Hamil" value="0" valueId="jadwalPosyanduIbuHamil" icon="bi-person-heart" color="warning" />
    </div>
    <div class="col-xl-3 col-md-6">
        <x-stat-card title="Lansia" value="0" valueId="jadwalPosyanduLansia" icon="bi-person-walking" color="info" />
    </div>
</div>

<div id="jadwalPosyanduAlert" class="alert alert-danger d-none" role="alert"></div>

<div class="form-card mb-4">
    <div class="card-header">
        <h5 class="card-title"><i class="bi bi-calendar-plus me-2 text-primary"></i>Form Data Jadwal Posyandu</h5>
    </div>
    <div class="card-body">
        <form id="jadwalPosyanduForm" novalidate>
            <input type="hidden" name="id" id="jadwalPosyanduId">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Tanggal Pelaksanaan <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_pelaksanaan" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Waktu Mulai <span class="text-danger">*</span></label>
                    <input type="time" name="waktu_mulai" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Waktu Selesai <span class="text-danger">*</span></label>
                    <input type="time" name="waktu_selesai" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kategori Posyandu <span class="text-danger">*</span></label>
                    <select name="kategori_posyandu" class="form-select" required>
                        <option value="">Pilih kategori</option>
                        <option value="balita">Balita</option>
                        <option value="ibu_hamil">Ibu Hamil</option>
                        <option value="lansia">Lansia</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Lokasi / Tempat <span class="text-danger">*</span></label>
                    <select name="lokasi" class="form-select" required>
                        <option value="">Pilih lokasi</option>
                        <option value="balai_rw_terdekat">Balai RW terdekat</option>
                        <option value="rumah_pak_rt_terdekat">Rumah Pak RT terdekat</option>
                        <option value="gedung_pkk_terdekat">Gedung PKK terdekat</option>
                    </select>
                </div>
            </div>

            <div class="d-flex gap-2 justify-content-end mt-4 pt-3 border-top">
                <button type="button" class="btn btn-outline-secondary d-none" id="jadwalPosyanduCancelEdit">
                    <i class="bi bi-x-lg me-1"></i>Batal Ubah
                </button>
                <button type="reset" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                </button>
                <button type="submit" class="btn btn-primary" id="jadwalPosyanduSubmitBtn">
                    <i class="bi bi-check-lg me-1"></i>Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card-table">
    <div class="card-header">
        <h5 class="card-title"><i class="bi bi-list-ul me-2 text-success"></i>Daftar Jadwal Posyandu</h5>
        <span class="text-muted small" id="jadwalPosyanduSummary">Memuat data...</span>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Waktu</th>
                    <th>Kategori</th>
                    <th>Lokasi</th>
                    <th>Dibuat</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="jadwalPosyanduTableBody"></tbody>
        </table>
    </div>
</div>
@endsection