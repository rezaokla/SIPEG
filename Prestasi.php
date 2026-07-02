<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prestasi extends Model
{
    protected $table = 'prestasi';
    protected $fillable = ['pegawai_id','kategori','tanggal','deskripsi','pemberi'];

    public function pegawai() { return $this->belongsTo(Pegawai::class); }
}
