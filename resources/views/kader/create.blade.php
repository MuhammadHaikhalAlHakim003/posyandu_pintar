@extends('layouts.dashboard')

@section('title', 'Tambah Data Kader')

@section('content')
<div class="breadcrumb-area">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ url('/admin/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/admin/kader') }}">Kader</a></li>
            <li class="breadcrumb-item active">Tambah Data</li>
        </ol>
    </nav>
    <a href="{{ url('/admin/kader') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar
    </a>
</div>

<div class="form-card">
    <div class="card-header">
        <h5 class="card-title"><i class="bi bi-person-plus me-2 text-success"></i>Form Data Kader</h5>
    </div>
    <div class="card-body">
        <div id="kaderFormAlert" class="alert alert-danger d-none" role="alert"></div>
        <form id="kaderForm" data-mode="create" novalidate>
            <div class="section-divider">Data Akun</div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Akun <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="name" class="form-control" placeholder="Nama login akun" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control" placeholder="Email login" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="Password login" required>
                        <button class="btn btn-outline-secondary toggle-password" type="button">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password login" required>
                        <button class="btn btn-outline-secondary toggle-password" type="button">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="section-divider">Data Profil</div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                        <input type="text" name="nama_lengkap" class="form-control" placeholder="Nama lengkap kader" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">No. HP <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                        <input type="text" name="no_hp" class="form-control" placeholder="08xxxxxxxxxx" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Wilayah <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                        <input type="text" name="wilayah" class="form-control" placeholder="Wilayah kerja" required>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="3" placeholder="Alamat lengkap"></textarea>
                </div>
            </div>

            <div class="d-flex gap-2 justify-content-end mt-4 pt-3 border-top">
                <a href="{{ url('/admin/kader') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg me-1"></i>Batal
                </a>
                <button type="reset" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i>Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
