<?php

namespace App\Policies;

use App\Models\Pegawai;
use App\Models\User;

class PegawaiPolicy
{
    public function viewAny(User $user): bool { return in_array($user->role, ['admin','pimpinan']); }
    public function view(User $user, Pegawai $pegawai): bool {
        return in_array($user->role, ['admin','pimpinan']) || $pegawai->user_id === $user->id;
    }
    public function create(User $user): bool { return in_array($user->role, ['admin','pimpinan']); }
    public function update(User $user, Pegawai $pegawai): bool { return in_array($user->role, ['admin','pimpinan']); }
    public function delete(User $user, Pegawai $pegawai): bool { return in_array($user->role, ['admin','pimpinan']); }
}
