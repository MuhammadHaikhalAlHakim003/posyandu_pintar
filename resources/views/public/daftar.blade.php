@extends('layouts.public')

@section('title', 'Pendaftaran Warga')

@section('content')
<section class="py-5" style="background: linear-gradient(135deg, #f1f5f9, #e2e8f0);">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="fw-bold">Pendaftaran Warga Posyandu</h2>
            <p class="text-muted">Isi data berikut untuk pendaftaran mandiri.</p>
        </div>

        <div class="alert alert-info" role="alert">
            <i class="bi bi-info-circle-fill me-2"></i>
            Setelah mendaftar, data Anda akan diverifikasi oleh petugas Posyandu.
            Anda akan menerima konfirmasi melalui WhatsApp atau SMS.
        </div>

        <div class="card shadow-sm border-0 mx-auto" style="max-width: 900px;">
            <div class="card-body p-4">
                <div id="publicWargaAlert" class="alert d-none" role="alert"></div>

                <form id="publicWargaForm" novalidate>
                    <div class="section-divider">Pilih Kategori</div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <x-kategori-card title="Balita" subtitle="Untuk anak usia dini" icon="bi-baby" value="balita" name="kategori" color="primary" checked="true" />
                        </div>
                        <div class="col-md-4">
                            <x-kategori-card title="Ibu Hamil" subtitle="Untuk ibu hamil dan menyusui" icon="bi-person-heart" value="ibu_hamil" name="kategori" color="danger" />
                        </div>
                        <div class="col-md-4">
                            <x-kategori-card title="Lansia" subtitle="Untuk lansia dan dewasa" icon="bi-person-walking" value="lansia" name="kategori" color="success" />
                        </div>
                    </div>

                    <div class="section-divider">Data Pribadi</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">NIK <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nik" placeholder="3201xxxxxxxxxxxx" inputmode="numeric" maxlength="16" autocomplete="off" required>
                            <div class="form-text" id="nikHelpText">16 digit nomor KTP/KK</div>
                            <div class="invalid-feedback d-none" id="nikLengthFeedback">NIK harus terdiri dari 16 digit.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama_lengkap" placeholder="Nama lengkap" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                            <div class="d-flex gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin" id="jkPria" value="L" checked>
                                    <label class="form-check-label" for="jkPria"><i class="bi bi-gender-male text-primary me-1"></i>Laki-laki</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin" id="jkWanita" value="P">
                                    <label class="form-check-label" for="jkWanita"><i class="bi bi-gender-female text-danger me-1"></i>Perempuan</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="tanggal_lahir" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tempat Lahir</label>
                            <input type="text" class="form-control" name="tempat_lahir" placeholder="Kota kelahiran">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No. WhatsApp <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" name="no_hp" placeholder="08xxxxxxxxxx" required>
                            <div class="form-text">Untuk konfirmasi dan reminder jadwal</div>
                        </div>
                    </div>

                    <div class="section-divider">Data Tambahan</div>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea class="form-control" rows="3" name="alamat" placeholder="Alamat lengkap" required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">RT/RW <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="rt_rw" placeholder="RT/RW" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Orang Tua / Wali</label>
                            <input type="text" class="form-control" name="nama_orang_tua" placeholder="Nama orang tua">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No. HP Orang Tua / Wali</label>
                            <input type="tel" class="form-control" name="no_hp_wali" placeholder="08xxxxxxxxxx">
                        </div>
                    </div>

                    <div class="section-divider">Persetujuan</div>
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" id="agree" name="agree" value="1">
                        <label class="form-check-label" for="agree">
                            Saya menyetujui data yang saya isi adalah benar.
                        </label>
                    </div>

                    <div class="d-flex flex-wrap gap-2 justify-content-end">
                        <button class="btn btn-outline-secondary" type="reset"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
                        <button class="btn btn-primary btn-lg" type="submit"><i class="bi bi-send-check me-1"></i>Kirim Pendaftaran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
