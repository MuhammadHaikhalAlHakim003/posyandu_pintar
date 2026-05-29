<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('penimbangans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warga_id')->constrained()->onDelete('cascade');
            $table->foreignId('kader_id')->constrained()->onDelete('cascade');
            $table->date('tanggal');
            $table->decimal('berat_badan', 5, 2); // kg
            $table->decimal('tinggi_badan', 5, 2); // cm
            $table->decimal('lingkar_kepala', 5, 2)->nullable();
            $table->string('status_gizi')->nullable(); // normal, kurang, buruk
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('penimbangans');
    }
};
