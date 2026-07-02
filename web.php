<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\AnggaranController;
use App\Http\Controllers\PrestasiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfilController;

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class);
    });

    Route::middleware(['role:admin,pimpinan'])->group(function () {
        Route::resource('pegawai', PegawaiController::class);
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/anggaran', [AnggaranController::class, 'index'])->name('anggaran.index');
        Route::put('/anggaran/master', [AnggaranController::class, 'updateMaster'])->name('anggaran.master.update');
    });

    Route::resource('cuti', CutiController::class);
    Route::post('/cuti/{cuti}/approve', [CutiController::class, 'approve'])->name('cuti.approve');
    Route::post('/cuti/{cuti}/reject', [CutiController::class, 'reject'])->name('cuti.reject');
    Route::resource('program', ProgramController::class);
    Route::post('/program/{program}/approve', [ProgramController::class, 'approve'])->name('program.approve');
    Route::post('/program/{program}/reject', [ProgramController::class, 'reject'])->name('program.reject');
    Route::post('/program/{program}/revisi', [ProgramController::class, 'revisi'])->name('program.revisi');
    Route::post('/program/{program}/realisasi', [ProgramController::class, 'realisasi'])->name('program.realisasi');
    Route::resource('prestasi', PrestasiController::class);
    Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
    Route::put('/profil/password', [ProfilController::class, 'updatePassword'])->name('profil.password.update');
});
