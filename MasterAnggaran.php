<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterAnggaran extends Model
{
    protected $table = 'master_anggaran';
    protected $fillable = ['total','sisa','keterangan'];
}
