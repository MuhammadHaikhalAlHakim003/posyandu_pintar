

<?php $__env->startSection('title', 'Verifikasi Data Warga'); ?>

<?php $__env->startSection('content'); ?>
<?php $base = (auth()->check() && auth()->user()->role === 'kader') ? '/kader' : '/admin'; ?>
<div class="breadcrumb-area">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="<?php echo e(url($base . '/dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(url($base . '/kader')); ?>">Kader</a></li>
            <li class="breadcrumb-item active">Verifikasi Data</li>
        </ol>
    </nav>
    <div class="d-flex gap-2">
        <a href="<?php echo e(url($base . '/warga')); ?>" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-people me-1"></i>Data Warga
        </a>
        <a href="<?php echo e(url($base . '/dashboard')); ?>" class="btn btn-primary btn-sm">
            <i class="bi bi-speedometer2 me-1"></i>Dashboard
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon warning"><i class="bi bi-hourglass-split"></i></div>
            <div>
                <p class="stat-value" id="verifikasiPendingCount">0</p>
                <p class="stat-title">Menunggu Verifikasi</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon success"><i class="bi bi-check-circle"></i></div>
            <div>
                <p class="stat-value" id="verifikasiApprovedCount">0</p>
                <p class="stat-title">Disetujui</p>
            </div>
        </div>
    </div>
    <!-- 'Ditolak' stat removed per request -->
</div>

<div class="filter-bar mb-4">
    <form class="row g-3 align-items-end" id="verifikasiFilterForm">
        <div class="col-md-5">
            <label class="form-label small text-muted">Cari</label>
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" name="search" placeholder="Cari nama, NIK, atau kategori...">
            </div>
        </div>
        <div class="col-md-3">
            <label class="form-label small text-muted">Status</label>
            <select class="form-select" name="status_verifikasi">
                <option value="pending">Menunggu</option>
                <option value="disetujui">Disetujui</option>
                <option value="ditolak">Ditolak</option>
                <option value="all">Semua Status</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small text-muted">Kategori</label>
            <select class="form-select" name="kategori">
                <option value="">Semua</option>
                <option value="balita">Balita</option>
                <option value="ibu_hamil">Ibu Hamil</option>
                <option value="lansia">Lansia</option>
            </select>
        </div>
        <div class="col-md-2 d-grid">
            <button type="submit" class="btn btn-outline-secondary">
                <i class="bi bi-funnel me-1"></i>Filter
            </button>
        </div>
    </form>
</div>

<div class="card-table">
    <div class="card-header">
        <h5 class="card-title"><i class="bi bi-patch-check me-2 text-primary"></i>Data Pendaftaran Masuk</h5>
        <span class="text-muted small" id="verifikasiSummary">Pengajuan yang menunggu pemeriksaan kader</span>
    </div>
    <div id="verifikasiAlert" class="alert alert-danger d-none m-3" role="alert"></div>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>NIK</th>
                    <th>Kategori</th>
                    <th>Tanggal Daftar</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="verifikasiTableBody">
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">Memuat data...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<!-- Jadwal Assign Modal -->
<div class="modal fade" id="jadwalAssignModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pilih Jadwal Posyandu untuk Warga</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div id="jadwalAssignAlert" class="alert d-none" role="alert"></div>
                <div class="mb-3">
                    <label class="form-label">Daftar Jadwal</label>
                    <div id="jadwalList" style="max-height:320px; overflow:auto;"></div>
                </div>
                <hr />
                <div>
                    <label class="form-label">Tambah Jadwal Baru</label>
                    <form id="jadwalCreateForm">
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="date" class="form-control" name="tanggal_pelaksanaan" required />
                            </div>
                            <div class="col-3">
                                <input type="time" class="form-control" name="waktu_mulai" required />
                            </div>
                            <div class="col-3">
                                <input type="time" class="form-control" name="waktu_selesai" required />
                            </div>
                        </div>
                        <div class="row g-2 mt-2">
                            <div class="col-6">
                                <select class="form-select" name="kategori_posyandu" required>
                                    <option value="balita">Balita</option>
                                    <option value="ibu_hamil">Ibu Hamil</option>
                                    <option value="lansia">Lansia</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <input type="text" class="form-control" name="lokasi" placeholder="Lokasi" required />
                            </div>
                        </div>
                        <div class="mt-3 d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-outline-primary btn-sm">Buat Jadwal</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button id="jadwalAssignConfirm" type="button" class="btn btn-primary">Assign & Setujui</button>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Code\UAS\posyandu_pintar_api\posyandu-pintar\resources\views/verifikasi/index.blade.php ENDPATH**/ ?>