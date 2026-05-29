

<?php $__env->startSection('title', 'Data Kader'); ?>

<?php $__env->startSection('content'); ?>
<div class="breadcrumb-area">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="<?php echo e(url('/admin/dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Kader</li>
        </ol>
    </nav>
    <div class="d-flex gap-2">
        <a href="<?php echo e(url('/admin/kader/create')); ?>" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Tambah Data
        </a>
    </div>
</div>

<div id="kaderAlert" class="alert alert-danger d-none" role="alert"></div>

<div class="card-table">
    <div class="card-header">
        <h5 class="card-title"><i class="bi bi-person-badge me-2 text-success"></i>Daftar Kader</h5>
        <span class="text-muted small" id="kaderSummary">Memuat data...</span>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>User ID</th>
                    <th>No. Telepon</th>
                    <th>Wilayah</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="kaderTableBody"></tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Code\UAS\posyandu_pintar_api\posyandu-pintar\resources\views/kader/index.blade.php ENDPATH**/ ?>