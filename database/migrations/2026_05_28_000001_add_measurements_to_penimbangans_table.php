<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penimbangans', function (Blueprint $table) {
            $table->string('tekanan_darah')->nullable();
            $table->decimal('lingkar_lengan_atas', 5, 2)->nullable();
            $table->decimal('lingkar_perut', 5, 2)->nullable();
            $table->decimal('kolesterol', 8, 2)->nullable();
            $table->decimal('asam_urat', 8, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('penimbangans', function (Blueprint $table) {
            $table->dropColumn([
                'tekanan_darah',
                'lingkar_lengan_atas',
                'lingkar_perut',
                'kolesterol',
                'asam_urat',
            ]);
        });
    }
};
