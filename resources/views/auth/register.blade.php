@extends('layouts.auth')

@section('title', 'Registrasi Akun User - Posyandu Pintar')

@section('content')
<div class="login-card">
    <div class="logo-area">
        <i class="bi bi-person-plus-fill"></i>
        <h2 class="mb-1">Buat Akun User</h2>
        <p class="text-muted mb-0">Registrasi akun untuk pengguna umum</p>
    </div>

    <div id="registerAlert" class="alert alert-danger d-none" role="alert"></div>

    <form id="registerForm" novalidate>
        <div class="mb-3">
            <label class="form-label fw-medium">Nama Lengkap</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input type="text" name="name" class="form-control" placeholder="Masukkan nama lengkap" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-medium">Email</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" class="form-control" placeholder="Masukkan email" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-medium">Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                <button class="btn btn-outline-secondary toggle-password" type="button">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-medium">Konfirmasi Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
                <button class="btn btn-outline-secondary toggle-password" type="button">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </div>

        <div class="mb-3 form-text">
            Akun ini untuk user biasa. Akun kader dibuat oleh admin melalui panel admin.
        </div>

        <button type="submit" class="btn btn-primary btn-login">
            <i class="bi bi-person-check me-2"></i>Daftar
        </button>

        <div class="text-center mt-3 small">
            <span class="text-muted">Sudah punya akun? </span><a href="{{ route('login') }}" class="text-decoration-none fw-semibold">Masuk di sini</a>
        </div>

        <div class="text-center mt-2 small">
            <a href="{{ url('/') }}" class="text-decoration-none">kembali ke halaman utama</a>
        </div>
    </form>

    <div class="text-center mt-3 text-muted small">
        &copy; {{ date('Y') }} Posyandu Pintar. UAS Pemrograman Web Lanjut.
    </div>
</div>
@endsection