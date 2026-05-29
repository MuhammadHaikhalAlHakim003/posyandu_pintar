<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('wargas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('nik')->unique();
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->enum('kategori', ['balita', 'ibu_hamil', 'lansia']);
            $table->string('nama_orang_tua')->nullable(); // untuk balita
            $table->string('alamat');
            $table->string('rt_rw');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('wargas');
    }
};
