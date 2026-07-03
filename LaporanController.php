<?php

namespace App\Http\Controllers;

use App\Models\Cuti;
use App\Models\Pegawai;
use App\Models\Prestasi;
use App\Models\Program;

class LaporanController extends Controller
{
    public function index()
    {
        $stats = [
            'pegawai' => Pegawai::count(),
            'cuti' => Cuti::count(),
            'cuti_disetujui' => Cuti::where('status', 'Disetujui')->count(),
            'program' => Program::count(),
            'program_disetujui' => Program::where('status_approval', 'Disetujui')->count(),
            'prestasi' => Prestasi::count(),
            'anggaran' => Program::sum('anggaran'),
            'realisasi' => Program::sum('realisasi'),
        ];

        $rekapCuti = Cuti::selectRaw('status, COUNT(*) as total')->groupBy('status')->get();
        $rekapProgram = Program::selectRaw('status_approval, COUNT(*) as total')->groupBy('status_approval')->get();
        $topPrestasi = Prestasi::selectRaw('pegawai_id, COUNT(*) as total')->with('pegawai')->groupBy('pegawai_id')->orderByDesc('total')->take(5)->get();

        return view('laporan.index', compact('stats', 'rekapCuti', 'rekapProgram', 'topPrestasi'));
    }
}
