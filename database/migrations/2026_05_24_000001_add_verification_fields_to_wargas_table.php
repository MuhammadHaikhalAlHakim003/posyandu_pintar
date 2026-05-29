<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wargas', function (Blueprint $table) {
            $table->string('tempat_lahir')->nullable()->after('nik');
            $table->string('no_hp')->nullable()->after('nama_orang_tua');
            $table->string('no_hp_wali')->nullable()->after('no_hp');
            $table->enum('status_verifikasi', ['pending', 'disetujui', 'ditolak'])
                ->default('disetujui')
                ->after('rt_rw');
            $table->foreignId('verified_by')->nullable()->after('status_verifikasi')->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable()->after('verified_by');
        });
    }

    public function down(): void
    {
        Schema::table('wargas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('verified_by');
            $table->dropColumn([
                'tempat_lahir',
                'no_hp',
                'no_hp_wali',
                'status_verifikasi',
                'verified_at',
            ]);
        });
    }
};