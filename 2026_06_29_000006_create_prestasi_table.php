<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('prestasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawai')->cascadeOnDelete();
            $table->enum('kategori', ['Penghargaan','Apresiasi','Tugas Luar Biasa','Inovasi']);
            $table->date('tanggal');
            $table->text('deskripsi');
            $table->string('pemberi');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('prestasi'); }
};
