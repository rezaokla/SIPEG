<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuti extends Model
{
    protected $table = 'cuti';
    protected $fillable = ['pegawai_id','jenis','tanggal_mulai','tanggal_selesai','alasan','status','catatan_pimpinan'];

    public function pegawai() { return $this->belongsTo(Pegawai::class); }
}
