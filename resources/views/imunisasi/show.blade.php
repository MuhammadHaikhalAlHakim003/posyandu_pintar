@extends('layouts.dashboard')

@section('title', 'Detail Imunisasi')

@section('content')
@php $base = (auth()->check() && auth()->user()->role === 'kader') ? '/kader' : '/admin'; @endphp
<div class="breadcrumb-area">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ url($base . '/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ url($base . '/imunisasi') }}">Imunisasi</a></li>
            <li class="breadcrumb-item active" id="imunisasiBreadcrumb">Detail</li>
        </ol>
    </nav>
    <div class="d-flex gap-2">
        <a href="#" class="btn btn-warning btn-sm" id="imunisasiEditLink"><i class="bi bi-pencil me-1"></i>Edit</a>
        <a href="#" class="btn btn-danger btn-sm" id="imunisasiDeleteLink"><i class="bi bi-trash me-1"></i>Hapus</a>
        <a href="{{ url($base . '/imunisasi') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
    </div>
</div>

<div class="profile-card" id="imunisasiDetail">
    <h5 class="mb-3"><i class="bi bi-shield-plus me-2 text-info"></i>Detail Imunisasi</h5>
    <div class="info-grid">
        <div class="info-item">
            <div class="info-label">Tanggal Pemberian</div>
            <div class="info-value"><i class="bi bi-calendar me-1 text-muted"></i><span data-field="tanggal_pemberian">-</span></div>
        </div>
        <div class="info-item">
            <div class="info-label">Tanggal Berikutnya</div>
            <div class="info-value"><i class="bi bi-calendar2 me-1 text-muted"></i><span data-field="tanggal_berikutnya">-</span></div>
        </div>
        <div class="info-item">
            <div class="info-label">Warga</div>
            <div class="info-value"><i class="bi bi-people me-1 text-muted"></i><span data-field="warga_nama">-</span></div>
        </div>
        <div class="info-item">
            <div class="info-label">Kader</div>
            <div class="info-value"><i class="bi bi-person-badge me-1 text-muted"></i><span data-field="kader_nama">-</span></div>
        </div>
        <div class="info-item">
            <div class="info-label">Jenis Imunisasi</div>
            <div class="info-value"><span data-field="jenis_imunisasi">-</span></div>
        </div>
        <div class="info-item" style="grid-column: 1 / -1;">
            <div class="info-label">Keterangan</div>
            <div class="info-value"><span data-field="keterangan">-</span></div>
        </div>
    </div>
</div>
@endsection
