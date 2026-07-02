<?php

namespace App\Providers;

use App\Models\Cuti;
use App\Models\Pegawai;
use App\Models\Prestasi;
use App\Models\Program;
use App\Models\User;
use App\Policies\CutiPolicy;
use App\Policies\PegawaiPolicy;
use App\Policies\PrestasiPolicy;
use App\Policies\ProgramPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
        Pegawai::class => PegawaiPolicy::class,
        Cuti::class => CutiPolicy::class,
        Program::class => ProgramPolicy::class,
        Prestasi::class => PrestasiPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
