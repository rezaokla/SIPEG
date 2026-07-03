<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $fillable = ['user_id','nip','nama','jabatan','unit_kerja','status_kepegawaian','tanggal_mulai'];

    public function user() { return $this->belongsTo(User::class); }
    public function cuti() { return $this->hasMany(Cuti::class); }
    public function prestasi() { return $this->hasMany(Prestasi::class); }
}
