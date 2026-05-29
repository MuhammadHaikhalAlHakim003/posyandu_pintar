@extends('layouts.dashboard')

@section('title', 'Ubah Data Imunisasi')

@section('content')
@php $base = (auth()->check() && auth()->user()->role === 'kader') ? '/kader' : '/admin'; @endphp
<div class="breadcrumb-area">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ url($base . '/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ url($base . '/imunisasi') }}">Imunisasi</a></li>
            <li class="breadcrumb-item active">Ubah Data</li>
        </ol>
    </nav>
    <a href="{{ url($base . '/imunisasi') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar</a>
</div>

<div class="form-card">
    <div class="card-header">
        <h5 class="card-title"><i class="bi bi-shield-plus me-2 text-info"></i>Form Ubah Data Imunisasi</h5>
    </div>
    <div class="card-body">
        <div id="imunisasiFormAlert" class="alert alert-danger d-none" role="alert"></div>
        <form id="imunisasiForm" data-mode="edit" novalidate>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Warga <span class="text-danger">*</span></label>
                    <select name="warga_id" class="form-select" id="imunisasiWargaSelect" required>
                        <option value="">Memuat warga...</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kader <span class="text-danger">*</span></label>
                    <select name="kader_id" class="form-select" id="imunisasiKaderSelect" required>
                        <option value="">Memuat kader...</option>
                    </select>
                </div>
            </div>

            <div class="mb-3 p-3 border rounded" id="imunisasiWargaPreview">
                <div class="text-muted small">Pilih warga untuk melihat ringkasan.</div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Tanggal Pemberian <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_pemberian" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Berikutnya</label>
                    <input type="date" name="tanggal_berikutnya" class="form-control">
                </div>
            </div>

            <div class="section-divider">Pilih Jenis Imunisasi</div>
            <div class="vaksin-grid">
                <div class="vaksin-item selected">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="jenis_imunisasi" id="bcgEdit" value="BCG" checked>
                        <label class="form-check-label" for="bcgEdit">BCG</label>
                    </div>
                    <div class="vaksin-desc text-muted small">Imunisasi dasar untuk TB</div>
                </div>
                <div class="vaksin-item">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="jenis_imunisasi" id="dptEdit" value="DPT-HB-Hib 1">
                        <label class="form-check-label" for="dptEdit">DPT-HB-Hib 1</label>
                    </div>
                    <div class="vaksin-desc text-muted small">Tahap pertama imunisasi DPT</div>
                </div>
                <div class="vaksin-item">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="jenis_imunisasi" id="polioEdit" value="Polio 1">
                        <label class="form-check-label" for="polioEdit">Polio 1</label>
                    </div>
                    <div class="vaksin-desc text-muted small">Perlindungan polio tahap 1</div>
                </div>
                <div class="vaksin-item">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="jenis_imunisasi" id="campakEdit" value="Campak">
                        <label class="form-check-label" for="campakEdit">Campak</label>
                    </div>
                    <div class="vaksin-desc text-muted small">Mencegah infeksi campak</div>
                </div>
            </div>

            <div class="mt-3">
                <label class="form-label">Catatan</label>
                <textarea name="keterangan" class="form-control" rows="3" placeholder="Catatan tambahan"></textarea>
            </div>

            <div class="d-flex gap-2 justify-content-end mt-4 pt-3 border-top">
                <a href="{{ url($base . '/imunisasi') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg me-1"></i>Batal</a>
                <button type="reset" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
