@extends('layouts.public')

@section('title', 'Cek Status Pendaftaran')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="fw-bold">Cek Status Pendaftaran</h2>
            <p class="text-muted">Masukkan NIK atau nomor WhatsApp untuk melihat status.</p>
        </div>

        <div id="cekStatusAlert" class="alert d-none mx-auto" style="max-width: 600px;" role="alert"></div>

        <form id="cekStatusForm" class="mx-auto" style="max-width: 600px;">
            <div class="input-group input-group-lg mb-4">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" name="query" placeholder="Masukkan NIK atau No. WhatsApp..." required>
                <button class="btn btn-primary" type="submit"><i class="bi bi-arrow-right"></i></button>
            </div>
        </form>

        <div class="card shadow-sm border-0 mx-auto d-none" style="max-width: 800px;" id="cekStatusCard">
            <div class="card-body p-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="mb-1" id="cekStatusName">-</h5>
                        <div class="text-muted small" id="cekStatusNik">-</div>
                    </div>
                    <div>
                        <span class="badge bg-warning text-dark" id="cekStatusBadge">Menunggu Verifikasi</span>
                    </div>
                </div>

                <div class="mb-3">
                    <span class="badge-category badge-balita" id="cekStatusKategori">Balita</span>
                </div>

                <div class="timeline" id="cekStatusTimeline"></div>

                <div class="alert alert-info mt-4 mb-0" id="cekStatusJadwalBox">
                    <div class="fw-semibold mb-1">Jadwal Posyandu</div>
                    <div id="cekStatusJadwalText">Jadwal akan tampil setelah kader menetapkannya.</div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
