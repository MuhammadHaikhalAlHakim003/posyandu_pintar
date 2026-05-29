<aside class="sidebar" id="sidebar">
@php
    $base = (auth()->check() && auth()->user()->role === 'kader') ? '/kader' : '/admin';
@endphp
    <div class="sidebar-header">
        <i class="bi bi-heart-pulse-fill"></i>
        <span>Posyandu Pintar</span>
    </div>

    <nav class="sidebar-nav">
        <ul>
            <li class="active">
                <a href="{{ url($base . '/dashboard') }}" id="dashboardLink">
                    <i class="bi bi-speedometer2"></i><span>Dashboard</span>
                </a>
            </li>

            <div class="section-title" data-role="admin">Data Master</div>
            <li>
                <a href="{{ url($base . '/warga') }}">
                    <i class="bi bi-people"></i><span>Warga</span>
                </a>
            </li>
            <li data-role="admin">
                <a href="{{ url($base . '/kader') }}">
                    <i class="bi bi-person-badge"></i><span>Kader</span>
                </a>
            </li>
            <li>
                <a href="{{ url($base . '/jadwal-posyandu') }}">
                    <i class="bi bi-calendar-week"></i><span>Jadwal Posyandu</span>
                </a>
            </li>
            <li data-role="admin">
                <a href="{{ url($base . '/verifikasi-data') }}">
                    <i class="bi bi-patch-check"></i><span>Verifikasi Data</span>
                </a>
            </li>
            <li data-role="kader">
                <a href="{{ url('/kader/verifikasi-data') }}">
                    <i class="bi bi-patch-check"></i><span>Verifikasi Data</span>
                </a>
            </li>

            <div class="section-title">Transaksi</div>
            <li>
                <a href="{{ url($base . '/penimbangan') }}">
                    <i class="bi bi-clipboard-data"></i><span>Penimbangan</span>
                </a>
            </li>
            <li>
                <a href="{{ url($base . '/imunisasi') }}">
                    <i class="bi bi-shield-plus"></i><span>Imunisasi</span>
                </a>
            </li>
<!-- 
            <div class="section-title" data-role="admin">Laporan</div>
            <li data-role="admin">
                <a href="#" aria-disabled="true">
                    <i class="bi bi-file-earmark-text"></i><span>Laporan</span>
                </a>
            </li> -->

            <div class="section-title">Sistem</div>
            <li>
                <a href="#" data-action="logout">
                    <i class="bi bi-box-arrow-right"></i><span>Logout</span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="sidebar-footer">
        <div class="user-profile">
            <div class="avatar" id="sidebarAvatar">AD</div>
            <div class="user-info">
                <span class="name" id="sidebarUserName">Admin Demo</span>
                <span class="role" id="sidebarUserRole">Admin</span>
            </div>
        </div>
    </div>
</aside>
