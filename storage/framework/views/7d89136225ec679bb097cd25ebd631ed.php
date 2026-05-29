<header class="topbar">
    <?php
        // Prefer authenticated user's role when available, otherwise fall back to request path
        if (auth()->check()) {
            $base = auth()->user()->role === 'kader' ? '/kader' : '/admin';
        } else {
            $base = request()->is('kader/*') ? '/kader' : '/admin';
        }
        $verifikasiUrl = url($base . '/verifikasi-data');
        $penimbanganUrl = url($base . '/penimbangan');
        $imunisasiUrl = url($base . '/imunisasi');
    ?>
    <div class="topbar-left">
        <button class="menu-toggle d-lg-none" type="button" data-toggle="sidebar">
            <i class="bi bi-list"></i>
        </button>
        <h1 class="page-title"><?php echo $__env->yieldContent('title', 'Dashboard'); ?></h1>
    </div>
    <div class="topbar-right">
        <div class="dropdown">
            <button class="btn-icon" id="notificationToggle" data-bs-toggle="dropdown" type="button" aria-label="Buka notifikasi">
                <i class="bi bi-bell"></i>
                <span class="badge bg-danger rounded-pill d-none" id="notificationBadge">0</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" id="notificationMenu">
                <li><h6 class="dropdown-header">Notifikasi</h6></li>
                <li><a class="dropdown-item notification-item" href="<?php echo e($verifikasiUrl); ?>" data-notification-id="warga-baru-mendaftar">
                    <span id="notificationSummaryLabel">Ada warga baru mendaftar</span>
                </a></li>
            </ul>
        </div>
        <div class="dropdown">
            <button class="user-toggle" data-bs-toggle="dropdown" type="button">
                <div class="avatar" id="topbarAvatar">AD</div>
                <span class="d-none d-md-block fw-medium" id="topbarUserName">Admin Demo</span>
                <i class="bi bi-chevron-down small"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="<?php echo e(url($base . '/profile')); ?>"><i class="bi bi-person me-2"></i>Profil</a></li>
                <!-- Pengaturan menu dihapus sesuai permintaan -->
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#" data-action="logout"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
            </ul>
        </div>
    </div>
</header>
<?php /**PATH D:\Code\UAS\posyandu_pintar_api\posyandu-pintar\resources\views/partials/topbar.blade.php ENDPATH**/ ?>