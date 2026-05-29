@extends('layouts.public')

@section('title', '404 - Halaman Tidak Ditemukan')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="text-center">
            <i class="bi bi-geo-alt" style="font-size:4rem;color:#94a3b8;"></i>
            <h2 class="fw-bold mt-3">Halaman Tidak Ditemukan</h2>
            <p class="text-muted">Halaman yang Anda cari tidak tersedia.</p>
            <a href="#" class="btn btn-primary"><i class="bi bi-arrow-left me-1"></i>Kembali ke Dashboard</a>
        </div>
    </div>
</section>
@endsection
