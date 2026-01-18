<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KaryawanController;
use App\Http\Controllers\Admin\KehadiranController;
use App\Http\Controllers\Admin\PengajuanController;
use App\Http\Controllers\AdminProfileController;


// Halaman Depan
Route::get('/', function () {
    return view('welcome');
});

// Route untuk Google Auth
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// Grup Route yang Memerlukan Login (Auth)
Route::middleware('auth')->group(function () {
    
    // Profile Management
    // Contoh: User harus konfirmasi password dulu sebelum bisa buka halaman Edit Profil
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Menu Kehadiran (Folder: views/kehadiran/index.blade.php)
    Route::get('/kehadiran', function () {
        return view('kehadiran');
    })->name('kehadiran');

    // Menu Riwayat (Folder: views/riwayat/index.blade.php)
    Route::get('/riwayat', function () {
        return view('riwayat');
    })->name('riwayat');

    // Menu Pengajuan (Folder: views/pengajuan/index.blade.php)
    Route::get('/pengajuan', function () {
        return view('pengajuan');
    })->name('pengajuan');

    // Menu Pengaturan (Folder: views/pengaturan/index.blade.php)
    Route::get('/pengaturan', function () {
        return view('pengaturan');
    })->name('pengaturan');
});
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboards', [DashboardController::class, 'index'])->name('dashboards');
    Route::get('/export-kehadiran', [DashboardController::class, 'exportExcel'])->name('export.kehadiran');

    // Karyawan
    Route::get('/karyawans', [KaryawanController::class, 'index'])->name('karyawans');
    Route::post('/karyawans', [KaryawanController::class, 'store'])->name('karyawans.store');
    Route::put('/karyawans/{user}', [KaryawanController::class, 'update'])->name('karyawans.update'); 
    Route::delete('/karyawans/{user}', [KaryawanController::class, 'destroy'])->name('karyawans.destroy');

    // Kehadiran
    Route::get('/kehadirans', [KehadiranController::class, 'index'])->name('kehadirans');

    // Pengajuan (PASTIKAN BAGIAN INI ADA)
    Route::get('/pengajuans', [PengajuanController::class, 'index'])->name('pengajuans');
    Route::patch('/pengajuans/{id}/status', [PengajuanController::class, 'updateStatus'])->name('pengajuans.status');
    
    Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile/update', [AdminProfileController::class, 'update'])->name('profile.update');

});
require __DIR__.'/auth.php';