<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Prestasi;
use Illuminate\Http\Request;

class PrestasiController extends Controller
{
    public function index(Request $request)
    {
        $q = Prestasi::with('pegawai');

        if (auth()->user()->role === 'pegawai') {
            $pegawai = Pegawai::where('user_id', auth()->id())->first();
            $q->where('pegawai_id', $pegawai?->id ?? 0);
        }

        if ($request->filled('kategori')) {
            $q->where('kategori', $request->kategori);
        }

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $q->where(function ($query) use ($keyword) {
                $query->where('deskripsi', 'like', "%{$keyword}%")
                      ->orWhere('pemberi', 'like', "%{$keyword}%")
                      ->orWhereHas('pegawai', function ($sub) use ($keyword) {
                          $sub->where('nama', 'like', "%{$keyword}%");
                      });
            });
        }

        $prestasi = $q->latest()->paginate(10)->withQueryString();
        return view('prestasi.index', compact('prestasi'));
    }

    public function create()
    {
        abort_unless(in_array(auth()->user()->role, ['admin', 'pimpinan']), 403);
        $pegawaiList = Pegawai::orderBy('nama')->get();
        return view('prestasi.create', compact('pegawaiList'));
    }

    public function store(Request $request)
    {
        abort_unless(in_array(auth()->user()->role, ['admin', 'pimpinan']), 403);

        $data = $request->validate([
            'pegawai_id' => ['required', 'exists:pegawai,id'],
            'kategori' => ['required', 'in:Penghargaan,Apresiasi,Tugas Luar Biasa,Inovasi'],
            'tanggal' => ['required', 'date'],
            'deskripsi' => ['required', 'string'],
            'pemberi' => ['required', 'string', 'max:255'],
        ]);

        Prestasi::create($data);
        return redirect()->route('prestasi.index')->with('success', 'Prestasi berhasil ditambahkan.');
    }

    public function show(Prestasi $prestasi)
    {
        $this->authorizePrestasi($prestasi);
        $prestasi->load('pegawai');
        return view('prestasi.show', compact('prestasi'));
    }

    public function edit(Prestasi $prestasi)
    {
        abort_unless(in_array(auth()->user()->role, ['admin', 'pimpinan']), 403);
        $pegawaiList = Pegawai::orderBy('nama')->get();
        return view('prestasi.edit', compact('prestasi', 'pegawaiList'));
    }

    public function update(Request $request, Prestasi $prestasi)
    {
        abort_unless(in_array(auth()->user()->role, ['admin', 'pimpinan']), 403);

        $data = $request->validate([
            'pegawai_id' => ['required', 'exists:pegawai,id'],
            'kategori' => ['required', 'in:Penghargaan,Apresiasi,Tugas Luar Biasa,Inovasi'],
            'tanggal' => ['required', 'date'],
            'deskripsi' => ['required', 'string'],
            'pemberi' => ['required', 'string', 'max:255'],
        ]);

        $prestasi->update($data);
        return redirect()->route('prestasi.index')->with('success', 'Prestasi berhasil diperbarui.');
    }

    public function destroy(Prestasi $prestasi)
    {
        abort_unless(in_array(auth()->user()->role, ['admin', 'pimpinan']), 403);
        $prestasi->delete();
        return redirect()->route('prestasi.index')->with('success', 'Prestasi berhasil dihapus.');
    }

    protected function authorizePrestasi(Prestasi $prestasi): void
    {
        if (auth()->user()->role === 'pegawai') {
            $pegawai = Pegawai::where('user_id', auth()->id())->first();
            abort_if(!$pegawai || $prestasi->pegawai_id !== $pegawai->id, 403);
        }
    }
}
