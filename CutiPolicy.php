<?php

namespace App\Policies;

use App\Models\Cuti;
use App\Models\Pegawai;
use App\Models\User;

class CutiPolicy
{
    public function viewAny(User $user): bool { return in_array($user->role, ['admin','pimpinan','pegawai']); }
    public function view(User $user, Cuti $cuti): bool {
        if (in_array($user->role, ['admin','pimpinan'])) return true;
        $pegawai = Pegawai::where('user_id', $user->id)->first();
        return $pegawai && $cuti->pegawai_id === $pegawai->id;
    }
    public function create(User $user): bool { return in_array($user->role, ['admin','pimpinan','pegawai']); }
    public function update(User $user, Cuti $cuti): bool {
        $pegawai = Pegawai::where('user_id', $user->id)->first();
        return $user->role === 'pegawai' && $pegawai && $cuti->pegawai_id === $pegawai->id && $cuti->status === 'Diajukan';
    }
    public function delete(User $user, Cuti $cuti): bool {
        $pegawai = Pegawai::where('user_id', $user->id)->first();
        return $user->role === 'pegawai' && $pegawai && $cuti->pegawai_id === $pegawai->id && $cuti->status === 'Diajukan';
    }
    public function approve(User $user, Cuti $cuti): bool { return in_array($user->role, ['admin','pimpinan']); }
}
