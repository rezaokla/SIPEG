<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anggaran extends Model
{
    protected $table = 'anggaran';
    protected $fillable = ['program_id','kode','total','realisasi','sisa','tahun'];

    public function program() { return $this->belongsTo(Program::class); }
}
