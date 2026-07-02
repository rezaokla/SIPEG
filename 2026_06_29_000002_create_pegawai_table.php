<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nip')->nullable();
            $table->string('nama');
            $table->string('jabatan')->nullable();
            $table->string('unit_kerja')->nullable();
            $table->enum('status_kepegawaian', ['PNS','PPPK','Kontrak','Honorer'])->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('pegawai'); }
};
