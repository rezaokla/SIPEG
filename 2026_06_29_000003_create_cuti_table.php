<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('cuti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawai')->cascadeOnDelete();
            $table->enum('jenis', ['Tahunan','Sakit','Melahirkan','Alasan Penting']);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->text('alasan');
            $table->enum('status', ['Diajukan','Disetujui','Ditolak'])->default('Diajukan');
            $table->text('catatan_pimpinan')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('cuti'); }
};
