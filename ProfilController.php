<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Prestasi;
use App\Models\Cuti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $pegawai = Pegawai::with(['cuti', 'prestasi'])->where('user_id', $user->id)->first();

        $stats = [
            'cuti' => $pegawai ? Cuti::where('pegawai_id', $pegawai->id)->count() : 0,
            'prestasi' => $pegawai ? Prestasi::where('pegawai_id', $pegawai->id)->count() : 0,
        ];

        return view('profil.index', compact('user', 'pegawai', 'stats'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $pegawai = Pegawai::where('user_id', $user->id)->first();

        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'nip' => ['nullable', 'string', 'max:100'],
            'jabatan' => ['nullable', 'string', 'max:255'],
            'unit_kerja' => ['nullable', 'string', 'max:255'],
        ]);

        $user->update(['nama' => $data['nama']]);

        if ($pegawai) {
            $pegawai->update([
                'nama' => $data['nama'],
                'nip' => $data['nip'] ?? null,
                'jabatan' => $data['jabatan'] ?? null,
                'unit_kerja' => $data['unit_kerja'] ?? null,
            ]);
        }

        return redirect()->route('profil.index')->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $data = $request->validate([
            'password_lama' => ['required'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = auth()->user();
        if (!Hash::check($data['password_lama'], $user->password)) {
            return redirect()->route('profil.index')->with('success', 'Password lama tidak sesuai.');
        }

        $user->update(['password' => Hash::make($data['password'])]);
        return redirect()->route('profil.index')->with('success', 'Password berhasil diubah.');
    }
}
