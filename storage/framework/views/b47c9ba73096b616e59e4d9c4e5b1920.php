

<?php $__env->startSection('title', 'Posyandu Pintar'); ?>

<?php $__env->startSection('content'); ?>
<section class="public-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="animate-fade-up">
                    <h1 class="hero-title">Posyandu Pintar</h1>
                    <p class="hero-subtitle">Sistem informasi Posyandu untuk pendaftaran online, pemantauan kesehatan, dan layanan terintegrasi.</p>
                    <div class="d-flex flex-wrap gap-2 mt-4">
                        <a href="/daftar" class="btn btn-light btn-lg">Daftar Sekarang</a>
                        <a href="/cek-status" class="btn btn-outline-light btn-lg">Cek Data Saya</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mt-4 mt-lg-0">
                <div class="bg-white rounded-4 p-4 shadow-lg text-dark" id="landingSummary">
                    <h5 class="fw-bold">Ringkasan Layanan</h5>
                    <p class="text-muted mb-2">Akses layanan Posyandu lebih mudah dari rumah.</p>
                    <p class="small text-muted" id="landingSummaryUpdatedAt">Memuat data terbaru...</p>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="p-3 bg-light rounded">
                                <div class="d-flex align-items-center justify-content-between gap-2">
                                    <div class="fw-bold" id="landingTotalWarga"><?php echo e(isset($landingSummary['warga']) ? number_format($landingSummary['warga'], 0, ',', '.') : '...'); ?></div>
                                    <span class="badge text-bg-warning d-none" id="landingTotalWargaAccessBadge">Perlu login</span>
                                </div>
                                <div class="small text-muted">Warga terdaftar</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-light rounded">
                                <div class="d-flex align-items-center justify-content-between gap-2">
                                    <div class="fw-bold" id="landingTotalKader"><?php echo e(isset($landingSummary['kader']) ? number_format($landingSummary['kader'], 0, ',', '.') : '...'); ?></div>
                                    <span class="badge text-bg-warning d-none" id="landingTotalKaderAccessBadge">Perlu login</span>
                                </div>
                                <div class="small text-muted">Kader aktif</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-light rounded">
                                <div class="d-flex align-items-center justify-content-between gap-2">
                                    <div class="fw-bold" id="landingTotalPenimbangan"><?php echo e(isset($landingSummary['penimbangan']) ? number_format($landingSummary['penimbangan'], 0, ',', '.') : '...'); ?></div>
                                    <span class="badge text-bg-warning d-none" id="landingTotalPenimbanganAccessBadge">Perlu login</span>
                                </div>
                                <div class="small text-muted">Total penimbangan</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-light rounded">
                                <div class="d-flex align-items-center justify-content-between gap-2">
                                    <div class="fw-bold" id="landingTotalImunisasi"><?php echo e(isset($landingSummary['imunisasi']) ? number_format($landingSummary['imunisasi'], 0, ',', '.') : '...'); ?></div>
                                    <span class="badge text-bg-warning d-none" id="landingTotalImunisasiAccessBadge">Perlu login</span>
                                </div>
                                <div class="small text-muted">Total imunisasi</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="fw-bold">Fitur Utama</h2>
            <p class="text-muted">Semua kebutuhan Posyandu dalam satu sistem.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon" style="background:#0d6efd;"><i class="bi bi-person-plus"></i></div>
                    <h6 class="fw-bold">Pendaftaran Online</h6>
                    <p class="text-muted mb-0">Daftar warga baru tanpa antre.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon" style="background:#198754;"><i class="bi bi-clipboard-data"></i></div>
                    <h6 class="fw-bold">Cek Data Kesehatan</h6>
                    <p class="text-muted mb-0">Pantau status kesehatan warga.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon" style="background:#f59e0b;"><i class="bi bi-bell"></i></div>
                    <h6 class="fw-bold">Reminder Jadwal</h6>
                    <p class="text-muted mb-0">Notifikasi jadwal posyandu.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon" style="background:#ef4444;"><i class="bi bi-graph-up"></i></div>
                    <h6 class="fw-bold">Grafik Pertumbuhan</h6>
                    <p class="text-muted mb-0">Lihat perkembangan secara visual.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="fw-bold">Cara Kerja</h2>
            <p class="text-muted">Langkah mudah untuk memulai.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-3">
                <div class="how-step">
                    <div class="step-number">1</div>
                    <h6 class="fw-bold">Daftar Online</h6>
                    <p class="text-muted mb-0">Isi data diri dengan cepat.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="how-step">
                    <div class="step-number">2</div>
                    <h6 class="fw-bold">Verifikasi Data</h6>
                    <p class="text-muted mb-0">Petugas memeriksa data Anda.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="how-step">
                    <div class="step-number">3</div>
                    <h6 class="fw-bold">Ikut Jadwal</h6>
                    <p class="text-muted mb-0">Datang sesuai jadwal Posyandu.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="how-step">
                    <div class="step-number">4</div>
                    <h6 class="fw-bold">Pantau Perkembangan</h6>
                    <p class="text-muted mb-0">Lihat hasil dan riwayat.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container text-center">
        <h2 class="fw-bold mb-3">Siap Mendaftar?</h2>
        <p class="mb-4">Mulai pendaftaran mandiri sekarang dan dapatkan informasi Posyandu lebih cepat.</p>
        <a href="/register" class="btn btn-light btn-lg">Registrasi Akun Sekarang</a>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Code\UAS\posyandu_pintar_api\posyandu-pintar\resources\views/public/landing.blade.php ENDPATH**/ ?>