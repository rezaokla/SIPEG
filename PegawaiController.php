<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        $q = Pegawai::with('user');

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $q->where(function ($query) use ($keyword) {
                $query->where('nama', 'like', "%{$keyword}%")
                      ->orWhere('nip', 'like', "%{$keyword}%")
                      ->orWhere('jabatan', 'like', "%{$keyword}%")
                      ->orWhere('unit_kerja', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('status_kepegawaian')) {
            $q->where('status_kepegawaian', $request->status_kepegawaian);
        }

        if ($request->filled('unit_kerja')) {
            $q->where('unit_kerja', $request->unit_kerja);
        }

        $pegawai = $q->latest()->paginate(10)->withQueryString();
        $units = Pegawai::whereNotNull('unit_kerja')->distinct()->orderBy('unit_kerja')->pluck('unit_kerja');

        return view('pegawai.index', compact('pegawai', 'units'));
    }

    public function create()
    {
        $users = User::orderBy('nama')->get();
        return view('pegawai.create', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'nip' => ['nullable', 'string', 'max:100'],
            'nama' => ['required', 'string', 'max:255'],
            'jabatan' => ['nullable', 'string', 'max:255'],
            'unit_kerja' => ['nullable', 'string', 'max:255'],
            'status_kepegawaian' => ['nullable', 'in:PNS,PPPK,Kontrak,Honorer'],
            'tanggal_mulai' => ['nullable', 'date'],
        ]);

        Pegawai::create($data);
        return redirect()->route('pegawai.index')->with('success', 'Data pegawai berhasil ditambahkan.');
    }

    public function show(Pegawai $pegawai)
    {
        $pegawai->load(['user', 'cuti', 'prestasi']);
        return view('pegawai.show', compact('pegawai'));
    }

    public function edit(Pegawai $pegawai)
    {
        $users = User::orderBy('nama')->get();
        return view('pegawai.edit', compact('pegawai', 'users'));
    }

    public function update(Request $request, Pegawai $pegawai)
    {
        $data = $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'nip' => ['nullable', 'string', 'max:100'],
            'nama' => ['required', 'string', 'max:255'],
            'jabatan' => ['nullable', 'string', 'max:255'],
            'unit_kerja' => ['nullable', 'string', 'max:255'],
            'status_kepegawaian' => ['nullable', 'in:PNS,PPPK,Kontrak,Honorer'],
            'tanggal_mulai' => ['nullable', 'date'],
        ]);

        $pegawai->update($data);
        return redirect()->route('pegawai.index')->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy(Pegawai $pegawai)
    {
        $pegawai->delete();
        return redirect()->route('pegawai.index')->with('success', 'Data pegawai berhasil dihapus.');
    }
}
