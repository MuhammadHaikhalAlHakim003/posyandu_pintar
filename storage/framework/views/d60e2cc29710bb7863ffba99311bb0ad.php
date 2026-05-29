

<?php $__env->startSection('title', 'Login - Posyandu Pintar'); ?>

<?php $__env->startSection('content'); ?>
<div class="login-card">
    <div class="logo-area">
        <i class="bi bi-heart-pulse-fill"></i>
        <h2 class="mb-1">Posyandu Pintar</h2>
        <p class="text-muted mb-0">Sistem Informasi Posyandu</p>
    </div>

    <div id="loginAlert" class="alert alert-danger d-none" role="alert"></div>

    <form id="loginForm" novalidate>
        <div class="mb-3">
            <label class="form-label fw-medium">Email</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" class="form-control" placeholder="Masukkan email" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-medium">Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                <button class="btn btn-outline-secondary toggle-password" type="button">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="remember">
                <label class="form-check-label" for="remember">Ingat saya</label>
            </div>
            <a href="#" class="small text-decoration-none">Lupa password?</a>
        </div>

        <button type="submit" class="btn btn-primary btn-login">
            <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
        </button>

        <div class="text-center mt-3 small">
            <div class="mb-1 text-muted">
                Belum punya akun? <a href="<?php echo e(route('register')); ?>" class="text-decoration-none fw-semibold">Registrasi akun disini</a>
            </div>
            <a href="<?php echo e(url('/')); ?>" class="text-decoration-none">Kembali ke halaman utama</a>
        </div>
    </form>

    <div class="text-center mt-3 text-muted small">
        &copy; <?php echo e(date('Y')); ?> Posyandu Pintar. UAS Pemrograman Web Lanjut.
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Code\UAS\posyandu_pintar_api\posyandu-pintar\resources\views/auth/login.blade.php ENDPATH**/ ?>