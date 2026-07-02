<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('anggaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('program')->cascadeOnDelete();
            $table->string('kode')->nullable();
            $table->bigInteger('total')->default(0);
            $table->bigInteger('realisasi')->default(0);
            $table->bigInteger('sisa')->default(0);
            $table->integer('tahun')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('anggaran'); }
};
