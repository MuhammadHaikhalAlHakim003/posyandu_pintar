@extends('layouts.dashboard')

@section('title', 'Profil Saya')

@section('content')
@php
    if (auth()->check()) {
        $base = auth()->user()->role === 'kader' ? '/kader' : '/admin';
        $panelLabel = auth()->user()->role === 'kader' ? 'Kader' : 'Admin';
    } else {
        $base = request()->is('kader/*') ? '/kader' : '/admin';
        $panelLabel = request()->is('kader/*') ? 'Kader' : 'Admin';
    }
@endphp

<section class="py-3 py-md-4">
    <div class="container-fluid">
        <div class="mb-4">
            <h2 class="fw-bold mb-1">Profil {{ $panelLabel }}</h2>
            <p class="text-muted mb-0">Informasi akun yang sedang aktif di panel.</p>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5">
                <div id="profileAlert" class="alert alert-danger d-none" role="alert"></div>

                <div class="row g-4 align-items-center">
                    <div class="col-md-4 text-center">
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" style="width: 110px; height: 110px; font-size: 2rem; font-weight: 700;" id="profileAvatar">--</div>
                    </div>
                    <div class="col-md-8">
                        <div class="mb-3">
                            <div class="text-muted small">Nama Akun</div>
                            <div class="fs-5 fw-semibold" id="profileName">-</div>
                        </div>
                        <div class="mb-3">
                            <div class="text-muted small">Email Akun</div>
                            <div class="fs-6" id="profileEmail">-</div>
                        </div>
                        <div class="mb-3">
                            <div class="text-muted small">Peran</div>
                            <span class="badge bg-secondary text-uppercase" id="profileRole">-</span>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ url($base . '/dashboard') }}" class="btn btn-outline-secondary">Kembali ke Dashboard</a>
                            <a href="#" class="btn btn-outline-danger" data-action="logout">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection