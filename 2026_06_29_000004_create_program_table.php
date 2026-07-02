<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('program', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('keterangan');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->integer('tahun');
            $table->bigInteger('anggaran')->default(0);
            $table->bigInteger('realisasi')->default(0);
            $table->bigInteger('sisa_anggaran')->default(0);
            $table->enum('status_approval', ['Diajukan','Disetujui','Ditolak','Revisi'])->default('Diajukan');
            $table->enum('tahap', ['Perencanaan','Berjalan','Selesai','Dibatalkan'])->default('Perencanaan');
            $table->foreignId('penanggung_jawab_id')->nullable()->constrained('pegawai')->nullOnDelete();
            $table->foreignId('diajukan_oleh')->nullable()->constrained('pegawai')->nullOnDelete();
            $table->text('catatan_pimpinan')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('program'); }
};
