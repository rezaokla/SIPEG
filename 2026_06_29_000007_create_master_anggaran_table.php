<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('master_anggaran', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('total')->default(0);
            $table->bigInteger('sisa')->default(0);
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('master_anggaran'); }
};
