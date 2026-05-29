<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\WargaController;
use App\Http\Controllers\Api\KaderController;
use App\Http\Controllers\Api\PenimbanganController;
use App\Http\Controllers\Api\ImunisasiController;
use App\Http\Controllers\Api\JadwalPosyanduController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);
});

Route::post('/public/pendaftaran-warga', [WargaController::class, 'storePublic']);
Route::get('/public/pendaftaran-warga/status', [WargaController::class, 'status']);

// JWT Protected routes
Route::middleware('auth:api')->group(function () {
    Route::post('/auth/logout',           [AuthController::class, 'logout']);
    Route::get('/auth/me',                [AuthController::class, 'me']);
    Route::post('/auth/generate-api-key', [AuthController::class, 'generateApiKey']);

    Route::apiResource('wargas',       WargaController::class);
    Route::post('/wargas/{warga}/approve', [WargaController::class, 'approve']);
    Route::post('/wargas/{warga}/assign-jadwal', [WargaController::class, 'assignJadwal']);
    Route::post('/wargas/{warga}/reject',  [WargaController::class, 'reject']);
    Route::post('/kaders/account',     [KaderController::class, 'storeAccount']);
    Route::apiResource('kaders',       KaderController::class);
    Route::apiResource('jadwal-posyandus', JadwalPosyanduController::class);
    Route::apiResource('penimbangans', PenimbanganController::class);
    Route::apiResource('imunisasis',   ImunisasiController::class);
});

// API Key Protected routes (akses publik)
Route::middleware('api.key')->prefix('public')->group(function () {
    Route::get('/wargas',       [WargaController::class, 'index']);
    Route::get('/penimbangans', [PenimbanganController::class, 'index']);
});