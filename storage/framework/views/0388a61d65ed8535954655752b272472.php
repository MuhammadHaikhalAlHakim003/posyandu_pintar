

<?php $__env->startSection('title', 'Tambah Data Penimbangan'); ?>

<?php $__env->startSection('content'); ?>
<?php $base = (auth()->check() && auth()->user()->role === 'kader') ? '/kader' : '/admin'; ?>
<div class="breadcrumb-area">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="<?php echo e(url($base . '/dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(url($base . '/penimbangan')); ?>">Penimbangan</a></li>
            <li class="breadcrumb-item active">Tambah Data</li>
        </ol>
    </nav>
    <a href="<?php echo e(url($base . '/penimbangan')); ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar</a>
</div>

<div class="form-card">
    <div class="card-header">
        <h5 class="card-title"><i class="bi bi-clipboard-data me-2 text-warning"></i>Form Data Penimbangan</h5>
    </div>
    <div class="card-body">
        <div id="penimbanganFormAlert" class="alert alert-danger d-none" role="alert"></div>
        <form id="penimbanganForm" data-mode="create" novalidate>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Warga <span class="text-danger">*</span></label>
                    <select name="warga_id" class="form-select" id="penimbanganWargaSelect" required>
                        <option value="">Memuat warga...</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kader <span class="text-danger">*</span></label>
                    <select name="kader_id" class="form-select" id="penimbanganKaderSelect" required>
                        <option value="">Memuat kader...</option>
                    </select>
                </div>
            </div>

            <div class="mb-3 p-3 border rounded" id="penimbanganWargaPreview">
                <div class="text-muted small">Pilih warga untuk melihat ringkasan.</div>
            </div>

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Berat Badan (kg) <span class="text-danger">*</span></label>
                    <input type="number" name="berat_badan" class="form-control" step="0.1" placeholder="0.0" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tinggi Badan (cm) <span class="text-danger">*</span></label>
                    <input type="number" name="tinggi_badan" class="form-control" step="0.1" placeholder="0.0" required>
                </div>
                <div class="col-md-4" id="penimbanganLingkarGroup">
                    <label class="form-label">Lingkar Kepala (cm)</label>
                    <input type="number" name="lingkar_kepala" class="form-control" step="0.1" placeholder="0.0">
                </div>
                <div class="col-md-4" id="penimbanganTekananGroup">
                    <label class="form-label">Tekanan Darah</label>
                    <input type="text" name="tekanan_darah" class="form-control" placeholder="120/80">
                </div>
                <div class="col-md-4" id="penimbanganLenganGroup">
                    <label class="form-label">Lingkar Lengan Atas (cm)</label>
                    <input type="number" name="lingkar_lengan_atas" class="form-control" step="0.1" placeholder="0.0">
                </div>
                <div class="col-md-4" id="penimbanganPerutGroup">
                    <label class="form-label">Lingkar Perut (cm)</label>
                    <input type="number" name="lingkar_perut" class="form-control" step="0.1" placeholder="0.0">
                </div>
                <div class="col-md-4" id="penimbanganKolesterolGroup">
                    <label class="form-label">Kolesterol (mg/dL)</label>
                    <input type="number" name="kolesterol" class="form-control" step="0.1" placeholder="0.0">
                </div>
                <div class="col-md-4" id="penimbanganAsamUratGroup">
                    <label class="form-label">Asam Urat (mg/dL)</label>
                    <input type="number" name="asam_urat" class="form-control" step="0.1" placeholder="0.0">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status Gizi</label>
                    <input type="text" name="status_gizi" class="form-control" placeholder="Otomatis dihitung" readonly>
                    <div class="form-text">Status gizi dihitung otomatis berdasarkan kategori dan hasil pengukuran.</div>
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="catatan" class="form-control" rows="3" placeholder="Catatan tambahan"></textarea>
                </div>
            </div>

            <div class="d-flex gap-2 justify-content-end mt-4 pt-3 border-top">
                <button type="reset" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Simpan Data</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Code\UAS\posyandu_pintar_api\posyandu-pintar\resources\views/penimbangan/create.blade.php ENDPATH**/ ?>