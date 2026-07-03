<?php

namespace App\Http\Controllers;

use App\Models\MasterAnggaran;
use App\Models\Program;
use Illuminate\Http\Request;

class AnggaranController extends Controller
{
    public function index()
    {
        $master = MasterAnggaran::first();
        $program = Program::with(['penanggungJawab', 'pengaju'])->latest()->paginate(10);

        $totalProgram = Program::sum('anggaran');
        $totalRealisasi = Program::sum('realisasi');
        $totalSisa = Program::sum('sisa_anggaran');

        return view('anggaran.index', compact('master', 'program', 'totalProgram', 'totalRealisasi', 'totalSisa'));
    }

    public function updateMaster(Request $request)
    {
        $data = $request->validate([
            'total' => ['required', 'numeric', 'min:0'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $totalProgram = Program::sum('anggaran');
        $master = MasterAnggaran::first();

        if (!$master) {
            $master = new MasterAnggaran();
        }

        $master->total = $data['total'];
        $master->sisa = max(0, $data['total'] - $totalProgram);
        $master->keterangan = $data['keterangan'] ?? null;
        $master->save();

        return redirect()->route('anggaran.index')->with('success', 'Master anggaran berhasil diperbarui.');
    }
}
