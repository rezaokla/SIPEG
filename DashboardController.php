<?php

namespace App\Http\Controllers;

use App\Models\Anggaran;
use App\Models\Cuti;
use App\Models\Pegawai;
use App\Models\Prestasi;
use App\Models\Program;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'pegawai' => Pegawai::count(),
            'cuti' => Cuti::count(),
            'cuti_pending' => Cuti::where('status', 'Diajukan')->count(),
            'program' => Program::count(),
            'program_disetujui' => Program::where('status_approval', 'Disetujui')->count(),
            'prestasi' => Prestasi::count(),
            'anggaran' => Anggaran::sum('total'),
            'realisasi' => Anggaran::sum('realisasi'),
        ];

        $cutiTerbaru = Cuti::with('pegawai')->latest()->take(5)->get();
        $programTerbaru = Program::with('penanggungJawab')->latest()->take(5)->get();

        return view('dashboard.index', compact('stats', 'cutiTerbaru', 'programTerbaru'));
    }
}
