<nav class="navbar navbar-expand-lg public-navbar">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?php echo e(url('/')); ?>">
            <i class="bi bi-heart-pulse-fill text-primary me-2"></i>Posyandu Pintar
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#publicNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="publicNav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-3">
                <li class="nav-item"><a class="nav-link" href="<?php echo e(url('/')); ?>">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo e(url('/daftar')); ?>">Daftar</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo e(url('/cek-status')); ?>">Cek Status</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo e(url('/tentang')); ?>">Tentang</a></li>
                <li class="nav-item" id="publicAuthArea">
                    <a class="btn btn-primary btn-sm" href="<?php echo e(url('/login')); ?>" id="publicAuthLink">Login</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<?php /**PATH D:\Code\UAS\posyandu_pintar_api\posyandu-pintar\resources\views/partials/navbar-public.blade.php ENDPATH**/ ?>