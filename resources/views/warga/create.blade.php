@extends('layouts.dashboard')

@section('title', 'Tambah Warga')

@section('content')
@php $base = (auth()->check() && auth()->user()->role === 'kader') ? '/kader' : '/admin'; @endphp
<div class="breadcrumb-area">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ url($base . '/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ url($base . '/warga') }}">Warga</a></li>
            <li class="breadcrumb-item active">Tambah</li>
        </ol>
    </nav>
    <a href="{{ url($base . '/warga') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="form-card">
    <div class="card-header">
        <h5 class="card-title"><i class="bi bi-person-plus me-2 text-primary"></i>Formulir Data Warga</h5>
    </div>
    <div class="card-body">
        <div id="wargaFormAlert" class="alert alert-danger d-none" role="alert"></div>
        <form id="wargaForm" data-mode="create" novalidate>
            <div class="section-divider">Data Pribadi</div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">NIK <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-credit-card"></i></span>
                        <input type="text" name="nik" class="form-control" placeholder="3201xxxxxxxxxxxx" maxlength="16" required>
                    </div>
                    <div class="form-text">Masukkan 16 digit nomor NIK</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="nama_lengkap" class="form-control" placeholder="Nama lengkap" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                    <div class="d-flex gap-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="jenis_kelamin" id="jkL" value="L" required>
                            <label class="form-check-label" for="jkL"><i class="bi bi-gender-male text-primary me-1"></i>Laki-laki</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="jenis_kelamin" id="jkP" value="P" required>
                            <label class="form-check-label" for="jkP"><i class="bi bi-gender-female text-danger me-1"></i>Perempuan</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                        <input type="date" name="tanggal_lahir" class="form-control" required>
                    </div>
                </div>
            </div>

            <div class="section-divider">Kategori dan Kontak</div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Kategori <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-tags"></i></span>
                        <select name="kategori" class="form-select" required>
                            <option value="">Pilih kategori...</option>
                            <option value="balita">Balita</option>
                            <option value="ibu_hamil">Ibu Hamil</option>
                            <option value="lansia">Lansia</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nama Orang Tua / Wali</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person-heart"></i></span>
                        <input type="text" name="nama_orang_tua" class="form-control" placeholder="Nama orang tua atau wali">
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="3" placeholder="Alamat lengkap"></textarea>
                    <div class="form-text">Maksimal 255 karakter</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">RT/RW</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-house"></i></span>
                        <input type="text" name="rt_rw" class="form-control" placeholder="Contoh: 003/009">
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 justify-content-end mt-4 pt-3 border-top">
                <a href="{{ url($base . '/warga') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg me-1"></i>Batal
                </a>
                <button type="reset" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i>Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
