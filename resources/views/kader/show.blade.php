@extends('layouts.dashboard')

@section('title', 'Detail Data Kader')

@section('content')
<div class="breadcrumb-area">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ url('/admin/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/admin/kader') }}">Kader</a></li>
            <li class="breadcrumb-item active" id="kaderBreadcrumbName">Detail Data</li>
        </ol>
    </nav>
    <div class="d-flex gap-2">
        <a href="#" class="btn btn-warning btn-sm" id="kaderEditLink"><i class="bi bi-pencil me-1"></i>Ubah</a>
        <a href="#" class="btn btn-danger btn-sm" id="kaderDeleteLink"><i class="bi bi-trash me-1"></i>Hapus</a>
        <a href="{{ url('/admin/kader') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar</a>
    </div>
</div>

<div class="profile-card" id="kaderDetail">
    <div class="row align-items-center">
        <div class="col-md-auto text-center mb-3 mb-md-0">
            <div class="profile-avatar" id="kaderAvatar">--</div>
        </div>
        <div class="col-md">
            <h2 class="profile-name" data-field="nama_lengkap">-</h2>
            <div class="profile-meta">
                <span class="badge bg-light text-dark border"><i class="bi bi-person me-1"></i>ID: <span data-field="user_id">-</span></span>
                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Aktif</span>
            </div>
        </div>
    </div>
</div>

<div class="profile-card" id="kaderInfoDetail">
    <h5 class="mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>Informasi Kader</h5>
    <div class="info-grid">
        <div class="info-item">
            <div class="info-label">No. Telepon</div>
            <div class="info-value"><i class="bi bi-telephone me-1 text-muted"></i><span data-field="no_hp">-</span></div>
        </div>
        <div class="info-item">
            <div class="info-label">Wilayah</div>
            <div class="info-value"><i class="bi bi-geo-alt me-1 text-muted"></i><span data-field="wilayah">-</span></div>
        </div>
        <div class="info-item" style="grid-column: 1 / -1;">
            <div class="info-label">Alamat</div>
            <div class="info-value"><i class="bi bi-house me-1 text-muted"></i><span data-field="alamat">-</span></div>
        </div>
        <div class="info-item">
            <div class="info-label">Terdaftar</div>
            <div class="info-value"><i class="bi bi-clock-history me-1 text-muted"></i><span data-field="created_at">-</span></div>
        </div>
        <div class="info-item">
            <div class="info-label">Terakhir Diperbarui</div>
            <div class="info-value"><i class="bi bi-arrow-repeat me-1 text-muted"></i><span data-field="updated_at">-</span></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('kaderDetail');
    if (!container) {
        return;
    }

    const nameField = container.querySelector('[data-field="nama_lengkap"]');
    if (nameField && nameField.textContent.trim() && nameField.textContent.trim() !== '-') {
        return;
    }

    const id = window.location.pathname.split('/').filter(Boolean).pop();
    if (!id || id === 'edit') {
        return;
    }

    const editLink = document.getElementById('kaderEditLink');
    const deleteLink = document.getElementById('kaderDeleteLink');
    const avatar = document.getElementById('kaderAvatar');
    const breadcrumbName = document.getElementById('kaderBreadcrumbName');

    const formatDate = (value) => {
        if (!value) {
            return '-';
        }

        const date = new Date(value);
        if (Number.isNaN(date.getTime())) {
            return value;
        }

        return date.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric',
        });
    };

    apiRequest(`/kaders/${id}`)
        .then((response) => {
            const item = response?.data || response;
            if (!item) {
                return;
            }

            if (editLink) {
                editLink.href = `/admin/kader/${id}/edit`;
            }

            if (deleteLink) {
                deleteLink.addEventListener('click', async (event) => {
                    event.preventDefault();
                    if (!window.confirm('Yakin ingin menghapus data kader ini?')) {
                        return;
                    }

                    const token = localStorage.getItem('jwt_token');
                    try {
                        const deleteResponse = await apiRequest(`/kaders/${id}`, {
                            method: 'DELETE',
                        });

                        window.location.href = '/admin/kader';
                    } catch (error) {
                        window.alert(error?.data?.message || error?.message || 'Gagal menghapus data kader.');
                    }
                });
            }

            document.querySelectorAll('#kaderDetail [data-field], #kaderInfoDetail [data-field]').forEach((el) => {
                const field = el.getAttribute('data-field');
                const value = item[field];

                if (field === 'created_at' || field === 'updated_at') {
                    el.textContent = formatDate(value);
                    return;
                }

                el.textContent = value ?? '-';
            });

            if (avatar) {
                const initials = (item.nama_lengkap || 'User')
                    .trim()
                    .split(/\s+/)
                    .slice(0, 2)
                    .map((part) => part[0] || '')
                    .join('')
                    .toUpperCase();
                avatar.textContent = initials || 'U';
            }

            if (breadcrumbName) {
                breadcrumbName.textContent = item.nama_lengkap || 'Detail';
            }
        })
        .catch((error) => {
            window.alert(error?.data?.message || 'Gagal memuat detail kader.');
        });
});
</script>
@endpush
