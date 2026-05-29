<nav class="navbar navbar-expand-lg public-navbar">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ url('/') }}">
            <i class="bi bi-heart-pulse-fill text-primary me-2"></i>Posyandu Pintar
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#publicNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="publicNav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-3">
                <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/daftar') }}">Daftar</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/cek-status') }}">Cek Status</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/tentang') }}">Tentang</a></li>
                <li class="nav-item" id="publicAuthArea">
                    <a class="btn btn-primary btn-sm" href="{{ url('/login') }}" id="publicAuthLink">Login</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
