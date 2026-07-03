<?php

namespace App\Policies;

use App\Models\Pegawai;
use App\Models\Prestasi;
use App\Models\User;

class PrestasiPolicy
{
    public function viewAny(User $user): bool { return in_array($user->role, ['admin','pimpinan','pegawai']); }
    public function view(User $user, Prestasi $prestasi): bool {
        if (in_array($user->role, ['admin','pimpinan'])) return true;
        $pegawai = Pegawai::where('user_id', $user->id)->first();
        return $pegawai && $prestasi->pegawai_id === $pegawai->id;
    }
    public function create(User $user): bool { return in_array($user->role, ['admin','pimpinan']); }
    public function update(User $user, Prestasi $prestasi): bool { return in_array($user->role, ['admin','pimpinan']); }
    public function delete(User $user, Prestasi $prestasi): bool { return in_array($user->role, ['admin','pimpinan']); }
}
