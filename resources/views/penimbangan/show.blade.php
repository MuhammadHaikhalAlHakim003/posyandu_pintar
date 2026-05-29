@extends('layouts.dashboard')

@section('title', 'Detail Penimbangan')

@section('content')
@php $base = (auth()->check() && auth()->user()->role === 'kader') ? '/kader' : '/admin'; @endphp
<div class="breadcrumb-area">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ url($base . '/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ url($base . '/penimbangan') }}">Penimbangan</a></li>
            <li class="breadcrumb-item active" id="penimbanganBreadcrumb">Detail</li>
        </ol>
    </nav>
    <div class="d-flex gap-2">
        <a href="#" class="btn btn-warning btn-sm" id="penimbanganEditLink"><i class="bi bi-pencil me-1"></i>Edit</a>
        <a href="#" class="btn btn-danger btn-sm" id="penimbanganDeleteLink"><i class="bi bi-trash me-1"></i>Hapus</a>
        <a href="{{ url($base . '/penimbangan') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
    </div>
</div>

<div class="profile-card" id="penimbanganDetail">
    <h5 class="mb-3"><i class="bi bi-clipboard-data me-2 text-warning"></i>Detail Penimbangan</h5>
    <div class="info-grid">
        <div class="info-item">
            <div class="info-label">Tanggal</div>
            <div class="info-value"><i class="bi bi-calendar me-1 text-muted"></i><span data-field="tanggal">-</span></div>
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
            <div class="info-label">Berat Badan</div>
            <div class="info-value"><span data-field="berat_badan">-</span> kg</div>
        </div>
        <div class="info-item">
            <div class="info-label">Tinggi Badan</div>
            <div class="info-value"><span data-field="tinggi_badan">-</span> cm</div>
        </div>
        <div class="info-item">
            <div class="info-label">Lingkar Kepala</div>
            <div class="info-value"><span data-field="lingkar_kepala">-</span> cm</div>
        </div>
        <div class="info-item">
            <div class="info-label">Tekanan Darah</div>
            <div class="info-value"><span data-field="tekanan_darah">-</span></div>
        </div>
        <div class="info-item">
            <div class="info-label">Lingkar Lengan Atas</div>
            <div class="info-value"><span data-field="lingkar_lengan_atas">-</span> cm</div>
        </div>
        <div class="info-item">
            <div class="info-label">Lingkar Perut</div>
            <div class="info-value"><span data-field="lingkar_perut">-</span> cm</div>
        </div>
        <div class="info-item">
            <div class="info-label">Kolesterol</div>
            <div class="info-value"><span data-field="kolesterol">-</span> mg/dL</div>
        </div>
        <div class="info-item">
            <div class="info-label">Asam Urat</div>
            <div class="info-value"><span data-field="asam_urat">-</span> mg/dL</div>
        </div>
        <div class="info-item">
            <div class="info-label">Status Gizi</div>
            <div class="info-value"><span data-field="status_gizi">-</span></div>
        </div>
        <div class="info-item" style="grid-column: 1 / -1;">
            <div class="info-label">Catatan</div>
            <div class="info-value"><span data-field="catatan">-</span></div>
        </div>
    </div>
</div>
@endsection
