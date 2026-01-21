<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KaryawanController;
use App\Http\Controllers\Admin\KehadiranController;
use App\Http\Controllers\Admin\PengajuanController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\UserSettingController;
use App\Http\Controllers\PengajuanUserController; // <--- 1. Import Controller Baru

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
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Menu Kehadiran
    Route::get('/kehadiran', function () {
        return view('kehadiran');
    })->name('kehadiran');

    // Menu Riwayat
    Route::get('/riwayat', function () {
        return view('riwayat');
    })->name('riwayat');

    // Menu Pengajuan
    Route::get('/pengajuan', function () {
        return view('pengajuan');
    })->name('pengajuan');
    Route::post('/pengajuan/user-store', [PengajuanUserController::class, 'store'])->name('pengajuan.user.store');

    // Menu Pengaturan (Folder: views/pengaturan/index.blade.php)
    Route::get('/pengaturan', function () {
        return view('pengaturan');
    })->name('pengaturan');

    // 2. Route Backend Simpan Pengaturan
    Route::post('/pengaturan/update', [UserSettingController::class, 'update'])->name('settings.update');
    
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
    Route::post('/jabatans', [KaryawanController::class, 'storeJabatan'])->name('jabatans.store');
    Route::post('/units', [KaryawanController::class, 'storeUnit'])->name('units.store');

    // Kehadiran
    Route::get('/kehadirans', [KehadiranController::class, 'index'])->name('kehadirans');
    Route::post('/kehadirans', [KehadiranController::class, 'store'])->name('kehadirans.store');
    Route::put('/kehadirans/{id}', [KehadiranController::class, 'update'])->name('kehadirans.update');
    Route::delete('/kehadirans/{id}', [KehadiranController::class, 'destroy'])->name('kehadirans.destroy');

    // Pengajuan
    Route::get('/pengajuans', [PengajuanController::class, 'index'])->name('pengajuans');
    Route::post('/pengajuans', [PengajuanController::class, 'store'])->name('pengajuans.store');
    Route::put('/pengajuans/{id}', [PengajuanController::class, 'update'])->name('pengajuans.update');
    Route::patch('/pengajuans/{id}/status', [PengajuanController::class, 'updateStatus'])->name('pengajuans.status');
    Route::delete('/pengajuans/{id}', [PengajuanController::class, 'destroy'])->name('pengajuans.destroy');
    
    // Export Pengajuan
    Route::get('/pengajuans/export', [PengajuanController::class, 'export'])->name('pengajuans.export');
    
    // Profile Admin
    Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile/update', [AdminProfileController::class, 'update'])->name('profile.update');

});

require __DIR__.'/auth.php';