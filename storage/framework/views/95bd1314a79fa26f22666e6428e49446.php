

<?php $__env->startSection('title', 'Panel Kader'); ?>

<?php $__env->startSection('content'); ?>
<div class="breadcrumb-area">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="<?php echo e(url('/kader/dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Ringkasan</li>
        </ol>
    </nav>
    <div><span class="text-muted small" id="kaderDashboardDate">-</span></div>
</div>
<div id="kaderDashboardAlert" class="alert alert-danger d-none mt-3" role="alert"></div>

<div class="quick-actions">
    <a href="<?php echo e(url('/kader/penimbangan/create')); ?>" class="btn btn-success">
        <i class="bi bi-clipboard-data me-2"></i>Tambah Penimbangan
    </a>
    <a href="<?php echo e(url('/kader/imunisasi/create')); ?>" class="btn btn-info text-white">
        <i class="bi bi-shield-plus me-2"></i>Tambah Imunisasi
    </a>
    <a href="<?php echo e(url('/kader/warga')); ?>" class="btn btn-outline-secondary">
        <i class="bi bi-search me-2"></i>Cari Warga
    </a>
</div>

    <div class="row g-4 mb-4">
    <div class="col-xl-4 col-md-6">
        <?php if (isset($component)) { $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-card','data' => ['title' => 'Total Warga','value' => '0','valueId' => 'kaderTotalWarga','icon' => 'bi-people','color' => 'primary','trend' => ['value' => 0, 'label' => 'bulan ini']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Total Warga','value' => '0','valueId' => 'kaderTotalWarga','icon' => 'bi-people','color' => 'primary','trend' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['value' => 0, 'label' => 'bulan ini'])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $attributes = $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $component = $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
    </div>
    <div class="col-xl-4 col-md-6">
        <?php if (isset($component)) { $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-card','data' => ['title' => 'Penimbangan Bulan Ini','value' => '0','valueId' => 'kaderPenimbanganBulanIni','icon' => 'bi-clipboard-data','color' => 'warning','trend' => ['value' => 0, 'label' => 'minggu ini']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Penimbangan Bulan Ini','value' => '0','valueId' => 'kaderPenimbanganBulanIni','icon' => 'bi-clipboard-data','color' => 'warning','trend' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['value' => 0, 'label' => 'minggu ini'])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $attributes = $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $component = $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
    </div>
    <div class="col-xl-4 col-md-6">
        <?php if (isset($component)) { $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-card','data' => ['title' => 'Imunisasi Bulan Ini','value' => '0','valueId' => 'kaderImunisasiBulanIni','icon' => 'bi-shield-plus','color' => 'info','trend' => ['value' => 0, 'label' => 'minggu ini']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Imunisasi Bulan Ini','value' => '0','valueId' => 'kaderImunisasiBulanIni','icon' => 'bi-shield-plus','color' => 'info','trend' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['value' => 0, 'label' => 'minggu ini'])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $attributes = $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $component = $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
    </div>
</div>

<div class="card-table">
    <div class="card-header">
        <h5 class="card-title"><i class="bi bi-activity me-2 text-primary"></i>Aktivitas Hari Ini</h5>
        <span class="text-muted small">Data terbaru</span>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Warga</th>
                    <th>Kategori</th>
                    <th>Tanggal Penimbangan</th>
                    <th>Status Gizi</th>
                </tr>
            </thead>
            <tbody id="kaderTodayScheduleBody">
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">Memuat aktivitas hari ini...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Code\UAS\posyandu_pintar_api\posyandu-pintar\resources\views/dashboard/kader.blade.php ENDPATH**/ ?>