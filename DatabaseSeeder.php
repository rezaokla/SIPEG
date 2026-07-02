<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Pegawai;
use App\Models\Program;
use App\Models\Cuti;
use App\Models\Prestasi;
use App\Models\MasterAnggaran;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@pegawaiku.local'],
            ['nama' => 'Administrator', 'password' => Hash::make('password123'), 'role' => 'admin']
        );

        $pimpinan = User::firstOrCreate(
            ['email' => 'pimpinan@pegawaiku.local'],
            ['nama' => 'Pimpinan', 'password' => Hash::make('password123'), 'role' => 'pimpinan']
        );

        $pegawaiUser = User::firstOrCreate(
            ['email' => 'pegawai@pegawaiku.local'],
            ['nama' => 'Pegawai Contoh', 'password' => Hash::make('password123'), 'role' => 'pegawai']
        );

        $pegawaiAdmin = Pegawai::firstOrCreate(
            ['user_id' => $admin->id],
            ['nip' => 'ADM001', 'nama' => 'Administrator', 'jabatan' => 'Admin Sistem', 'unit_kerja' => 'Sekretariat', 'status_kepegawaian' => 'PNS']
        );

        $pegawaiPimpinan = Pegawai::firstOrCreate(
            ['user_id' => $pimpinan->id],
            ['nip' => 'PIM001', 'nama' => 'Pimpinan', 'jabatan' => 'Kepala Dinas', 'unit_kerja' => 'Pimpinan', 'status_kepegawaian' => 'PNS']
        );

        $pegawai = Pegawai::firstOrCreate(
            ['user_id' => $pegawaiUser->id],
            ['nip' => 'PGW001', 'nama' => 'Pegawai Contoh', 'jabatan' => 'Staf Administrasi', 'unit_kerja' => 'Umum', 'status_kepegawaian' => 'PPPK']
        );

        MasterAnggaran::firstOrCreate([], [
            'total' => 500000000,
            'sisa' => 350000000,
            'keterangan' => 'Seed master anggaran awal'
        ]);

        $program = Program::firstOrCreate(
            ['nama' => 'Program Digitalisasi Arsip'],
            [
                'keterangan' => 'Digitalisasi arsip dan dokumen internal.',
                'tanggal_mulai' => now()->toDateString(),
                'tanggal_selesai' => now()->addMonths(3)->toDateString(),
                'tahun' => (int) now()->format('Y'),
                'anggaran' => 150000000,
                'realisasi' => 25000000,
                'sisa_anggaran' => 125000000,
                'status_approval' => 'Disetujui',
                'tahap' => 'Berjalan',
                'penanggung_jawab_id' => $pegawaiPimpinan->id,
                'diajukan_oleh' => $pegawai->id,
            ]
        );

        Cuti::firstOrCreate(
            ['pegawai_id' => $pegawai->id, 'jenis' => 'Tahunan', 'tanggal_mulai' => now()->addDays(7)->toDateString()],
            [
                'tanggal_selesai' => now()->addDays(9)->toDateString(),
                'alasan' => 'Keperluan keluarga.',
                'status' => 'Diajukan'
            ]
        );

        Prestasi::firstOrCreate(
            ['pegawai_id' => $pegawai->id, 'kategori' => 'Apresiasi', 'tanggal' => now()->subDays(14)->toDateString()],
            [
                'deskripsi' => 'Apresiasi atas ketepatan pelaporan bulanan.',
                'pemberi' => 'Pimpinan'
            ]
        );
    }
}
