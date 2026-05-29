<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('imunisasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warga_id')->constrained()->onDelete('cascade');
            $table->foreignId('kader_id')->constrained()->onDelete('cascade');
            $table->string('jenis_imunisasi'); // BCG, Polio, DPT, dll
            $table->date('tanggal_pemberian');
            $table->date('tanggal_berikutnya')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('imunisasis');
    }
};
