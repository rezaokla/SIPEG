<?php

namespace App\Http\Controllers;

use App\Models\Cuti;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class CutiController extends Controller
{
    public function index(Request $request)
    {
        $q = Cuti::with('pegawai');

        if (auth()->user()->role === 'pegawai') {
            $pegawai = Pegawai::where('user_id', auth()->id())->first();
            $q->where('pegawai_id', $pegawai?->id ?? 0);
        }

        if ($request->filled('status')) {
            $q->where('status', $request->status);
        }

        if ($request->filled('jenis')) {
            $q->where('jenis', $request->jenis);
        }

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $q->whereHas('pegawai', function ($query) use ($keyword) {
                $query->where('nama', 'like', "%{$keyword}%")
                      ->orWhere('nip', 'like', "%{$keyword}%");
            });
        }

        $cuti = $q->latest()->paginate(10)->withQueryString();
        return view('cuti.index', compact('cuti'));
    }

    public function create()
    {
        $pegawaiList = auth()->user()->role === 'pegawai'
            ? Pegawai::where('user_id', auth()->id())->get()
            : Pegawai::orderBy('nama')->get();

        return view('cuti.create', compact('pegawaiList'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'pegawai_id' => ['required', 'exists:pegawai,id'],
            'jenis' => ['required', 'in:Tahunan,Sakit,Melahirkan,Alasan Penting'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'alasan' => ['required', 'string'],
        ]);

        if (auth()->user()->role === 'pegawai') {
            $pegawai = Pegawai::where('user_id', auth()->id())->first();
            abort_if(!$pegawai || $pegawai->id != $data['pegawai_id'], 403);
        }

        $data['status'] = 'Diajukan';
        Cuti::create($data);

        return redirect()->route('cuti.index')->with('success', 'Pengajuan cuti berhasil disimpan.');
    }

    public function show(Cuti $cuti)
    {
        $this->authorizeCuti($cuti);
        $cuti->load('pegawai');
        return view('cuti.show', compact('cuti'));
    }

    public function edit(Cuti $cuti)
    {
        $this->authorizeEditable($cuti);
        $pegawaiList = auth()->user()->role === 'pegawai'
            ? Pegawai::where('user_id', auth()->id())->get()
            : Pegawai::orderBy('nama')->get();

        return view('cuti.edit', compact('cuti', 'pegawaiList'));
    }

    public function update(Request $request, Cuti $cuti)
    {
        $this->authorizeEditable($cuti);

        $data = $request->validate([
            'pegawai_id' => ['required', 'exists:pegawai,id'],
            'jenis' => ['required', 'in:Tahunan,Sakit,Melahirkan,Alasan Penting'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'alasan' => ['required', 'string'],
        ]);

        $cuti->update($data);
        return redirect()->route('cuti.index')->with('success', 'Pengajuan cuti berhasil diperbarui.');
    }

    public function destroy(Cuti $cuti)
    {
        $this->authorizeEditable($cuti);
        $cuti->delete();
        return redirect()->route('cuti.index')->with('success', 'Pengajuan cuti berhasil dihapus.');
    }

    public function approve(Cuti $cuti)
    {
        abort_unless(in_array(auth()->user()->role, ['admin', 'pimpinan']), 403);
        $cuti->update(['status' => 'Disetujui', 'catatan_pimpinan' => null]);
        return redirect()->route('cuti.index')->with('success', 'Cuti berhasil disetujui.');
    }

    public function reject(Request $request, Cuti $cuti)
    {
        abort_unless(in_array(auth()->user()->role, ['admin', 'pimpinan']), 403);

        $data = $request->validate([
            'catatan_pimpinan' => ['nullable', 'string']
        ]);

        $cuti->update([
            'status' => 'Ditolak',
            'catatan_pimpinan' => $data['catatan_pimpinan'] ?? null,
        ]);

        return redirect()->route('cuti.index')->with('success', 'Cuti berhasil ditolak.');
    }

    protected function authorizeCuti(Cuti $cuti): void
    {
        if (auth()->user()->role === 'pegawai') {
            $pegawai = Pegawai::where('user_id', auth()->id())->first();
            abort_if(!$pegawai || $cuti->pegawai_id !== $pegawai->id, 403);
        }
    }

    protected function authorizeEditable(Cuti $cuti): void
    {
        $this->authorizeCuti($cuti);
        abort_if($cuti->status !== 'Diajukan', 403, 'Hanya cuti berstatus Diajukan yang bisa diubah.');
    }
}
