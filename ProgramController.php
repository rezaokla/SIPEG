<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        $q = Program::with(['penanggungJawab', 'pengaju']);

        if (auth()->user()->role === 'pegawai') {
            $pegawai = Pegawai::where('user_id', auth()->id())->first();
            $q->where('diajukan_oleh', $pegawai?->id ?? 0);
        }

        if ($request->filled('status_approval')) {
            $q->where('status_approval', $request->status_approval);
        }

        if ($request->filled('tahun')) {
            $q->where('tahun', $request->tahun);
        }

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $q->where(function ($query) use ($keyword) {
                $query->where('nama', 'like', "%{$keyword}%")
                      ->orWhere('keterangan', 'like', "%{$keyword}%");
            });
        }

        $program = $q->latest()->paginate(10)->withQueryString();
        return view('program.index', compact('program'));
    }

    public function create()
    {
        $pegawaiList = Pegawai::orderBy('nama')->get();
        return view('program.create', compact('pegawaiList'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'keterangan' => ['required', 'string'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'tahun' => ['required', 'integer'],
            'anggaran' => ['required', 'numeric', 'min:0'],
            'penanggung_jawab_id' => ['nullable', 'exists:pegawai,id'],
        ]);

        $pegawai = Pegawai::where('user_id', auth()->id())->first();
        $data['diajukan_oleh'] = $pegawai?->id;
        $data['status_approval'] = 'Diajukan';
        $data['tahap'] = 'Perencanaan';
        $data['realisasi'] = 0;
        $data['sisa_anggaran'] = $data['anggaran'];

        Program::create($data);
        return redirect()->route('program.index')->with('success', 'Program berhasil diajukan.');
    }

    public function show(Program $program)
    {
        $this->authorizeProgram($program);
        $program->load(['penanggungJawab', 'pengaju']);
        return view('program.show', compact('program'));
    }

    public function edit(Program $program)
    {
        $this->authorizeEditable($program);
        $pegawaiList = Pegawai::orderBy('nama')->get();
        return view('program.edit', compact('program', 'pegawaiList'));
    }

    public function update(Request $request, Program $program)
    {
        $this->authorizeEditable($program);

        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'keterangan' => ['required', 'string'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'tahun' => ['required', 'integer'],
            'anggaran' => ['required', 'numeric', 'min:0'],
            'penanggung_jawab_id' => ['nullable', 'exists:pegawai,id'],
        ]);

        $data['sisa_anggaran'] = $data['anggaran'] - $program->realisasi;
        $program->update($data);

        return redirect()->route('program.index')->with('success', 'Program berhasil diperbarui.');
    }

    public function destroy(Program $program)
    {
        $this->authorizeEditable($program);
        $program->delete();
        return redirect()->route('program.index')->with('success', 'Program berhasil dihapus.');
    }

    public function approve(Program $program)
    {
        abort_unless(in_array(auth()->user()->role, ['admin', 'pimpinan']), 403);
        $program->update(['status_approval' => 'Disetujui', 'catatan_pimpinan' => null]);
        return redirect()->route('program.index')->with('success', 'Program berhasil disetujui.');
    }

    public function reject(Request $request, Program $program)
    {
        abort_unless(in_array(auth()->user()->role, ['admin', 'pimpinan']), 403);
        $data = $request->validate(['catatan_pimpinan' => ['nullable', 'string']]);
        $program->update(['status_approval' => 'Ditolak', 'catatan_pimpinan' => $data['catatan_pimpinan'] ?? null]);
        return redirect()->route('program.index')->with('success', 'Program berhasil ditolak.');
    }

    public function revisi(Request $request, Program $program)
    {
        abort_unless(in_array(auth()->user()->role, ['admin', 'pimpinan']), 403);
        $data = $request->validate(['catatan_pimpinan' => ['nullable', 'string']]);
        $program->update(['status_approval' => 'Revisi', 'catatan_pimpinan' => $data['catatan_pimpinan'] ?? null]);
        return redirect()->route('program.index')->with('success', 'Program dikembalikan untuk revisi.');
    }

    public function realisasi(Request $request, Program $program)
    {
        abort_unless(in_array(auth()->user()->role, ['admin', 'pimpinan']), 403);
        $data = $request->validate([
            'realisasi' => ['required', 'numeric', 'min:0']
        ]);

        $realisasi = (float) $data['realisasi'];
        if ($realisasi > $program->anggaran) {
            return redirect()->route('program.index')->with('success', 'Realisasi tidak boleh melebihi anggaran.');
        }

        $program->update([
            'realisasi' => $realisasi,
            'sisa_anggaran' => $program->anggaran - $realisasi,
            'tahap' => $realisasi > 0 ? 'Berjalan' : $program->tahap,
        ]);

        return redirect()->route('program.index')->with('success', 'Realisasi program berhasil diperbarui.');
    }

    protected function authorizeProgram(Program $program): void
    {
        if (auth()->user()->role === 'pegawai') {
            $pegawai = Pegawai::where('user_id', auth()->id())->first();
            abort_if(!$pegawai || $program->diajukan_oleh !== $pegawai->id, 403);
        }
    }

    protected function authorizeEditable(Program $program): void
    {
        $this->authorizeProgram($program);
        abort_if(!in_array($program->status_approval, ['Diajukan', 'Revisi']), 403, 'Program tidak dapat diubah pada status saat ini.');
    }
}
