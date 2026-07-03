<?php

namespace App\Policies;

use App\Models\Pegawai;
use App\Models\Program;
use App\Models\User;

class ProgramPolicy
{
    public function viewAny(User $user): bool { return in_array($user->role, ['admin','pimpinan','pegawai']); }
    public function view(User $user, Program $program): bool {
        if (in_array($user->role, ['admin','pimpinan'])) return true;
        $pegawai = Pegawai::where('user_id', $user->id)->first();
        return $pegawai && $program->diajukan_oleh === $pegawai->id;
    }
    public function create(User $user): bool { return in_array($user->role, ['admin','pimpinan','pegawai']); }
    public function update(User $user, Program $program): bool {
        $pegawai = Pegawai::where('user_id', $user->id)->first();
        return $user->role === 'pegawai' && $pegawai && $program->diajukan_oleh === $pegawai->id && in_array($program->status_approval, ['Diajukan','Revisi']);
    }
    public function delete(User $user, Program $program): bool {
        $pegawai = Pegawai::where('user_id', $user->id)->first();
        return $user->role === 'pegawai' && $pegawai && $program->diajukan_oleh === $pegawai->id && in_array($program->status_approval, ['Diajukan','Revisi']);
    }
    public function approve(User $user, Program $program): bool { return in_array($user->role, ['admin','pimpinan']); }
}
