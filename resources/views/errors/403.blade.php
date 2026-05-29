@extends('layouts.public')

@section('title', '403 - Akses Ditolak')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="text-center">
            <i class="bi bi-shield-lock" style="font-size:4rem;color:#f59e0b;"></i>
            <h2 class="fw-bold mt-3">Akses Ditolak</h2>
            <p class="text-muted">Anda tidak memiliki izin untuk mengakses halaman ini.</p>
            <a href="#" class="btn btn-primary"><i class="bi bi-arrow-left me-1"></i>Kembali ke Dashboard</a>
        </div>
    </div>
</section>
@endsection
