<?php

use App\Models\Warga;
use App\Models\Kader;
use App\Models\Penimbangan;
use App\Models\Imunisasi;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
	return view('public.landing', [
		'landingSummary' => [
			'warga' => Warga::count(),
			'kader' => Kader::count(),
			'penimbangan' => Penimbangan::count(),
			'imunisasi' => Imunisasi::count(),
		],
	]);
});
Route::view('/daftar', 'public.daftar');
Route::view('/cek-status', 'public.cek-status');
Route::view('/tentang', 'public.tentang');
Route::view('/profile', 'public.profile')->name('profile');

// Expose the same profile view for admin and kader so they can view their own profile
Route::view('/admin/profile', 'profile.panel');
Route::view('/kader/profile', 'profile.panel');

Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');
Route::view('/docs/api', 'docs.swagger');

Route::view('/admin/dashboard', 'dashboard.admin');
Route::view('/kader/dashboard', 'dashboard.kader');
Route::view('/admin/verifikasi-data', 'verifikasi.index');

Route::view('/admin/warga', 'warga.index');
Route::view('/admin/warga/create', 'warga.create');
Route::view('/admin/warga/{id}/edit', 'warga.edit');
Route::view('/admin/warga/{id}', 'warga.show');

Route::view('/admin/kader', 'kader.index');
Route::view('/admin/kader/create', 'kader.create');
Route::view('/admin/kader/{id}/edit', 'kader.edit');
Route::view('/admin/kader/{id}', 'kader.show');

Route::view('/admin/jadwal-posyandu', 'jadwal-posyandu.index');

// Kader views (reuse same templates so kader can access forms)
Route::view('/kader/jadwal-posyandu', 'jadwal-posyandu.index');
Route::view('/kader/verifikasi-data', 'verifikasi.index');
Route::view('/kader/penimbangan', 'penimbangan.index');
Route::view('/kader/penimbangan/create', 'penimbangan.create');
Route::view('/kader/penimbangan/{id}/edit', 'penimbangan.edit');
Route::view('/kader/penimbangan/{id}', 'penimbangan.show');
Route::view('/kader/imunisasi', 'imunisasi.index');
Route::view('/kader/imunisasi/create', 'imunisasi.create');
Route::view('/kader/imunisasi/{id}/edit', 'imunisasi.edit');
Route::view('/kader/imunisasi/{id}', 'imunisasi.show');
// Allow kader to access warga views under /kader prefix as well
Route::view('/kader/warga', 'warga.index');
Route::view('/kader/warga/create', 'warga.create');
Route::view('/kader/warga/{id}/edit', 'warga.edit');
Route::view('/kader/warga/{id}', 'warga.show');

Route::view('/admin/penimbangan', 'penimbangan.index');
Route::view('/admin/penimbangan/create', 'penimbangan.create');
Route::view('/admin/penimbangan/{id}/edit', 'penimbangan.edit');
Route::view('/admin/penimbangan/{id}', 'penimbangan.show');

Route::view('/admin/imunisasi', 'imunisasi.index');
Route::view('/admin/imunisasi/create', 'imunisasi.create');
Route::view('/admin/imunisasi/{id}/edit', 'imunisasi.edit');
Route::view('/admin/imunisasi/{id}', 'imunisasi.show');

Route::view('/403', 'errors.403');
Route::view('/404', 'errors.404');
