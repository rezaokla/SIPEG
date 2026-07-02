<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $table = 'program';
    protected $fillable = ['nama','keterangan','tanggal_mulai','tanggal_selesai','tahun','anggaran','realisasi','sisa_anggaran','status_approval','tahap','penanggung_jawab_id','diajukan_oleh','catatan_pimpinan'];

    public function penanggungJawab() { return $this->belongsTo(Pegawai::class, 'penanggung_jawab_id'); }
    public function pengaju() { return $this->belongsTo(Pegawai::class, 'diajukan_oleh'); }
}
