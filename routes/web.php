<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KaryawanController;
use App\Http\Controllers\Admin\KehadiranController;
use App\Http\Controllers\Admin\PengajuanController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\UJController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\UserSettingController;
use App\Http\Controllers\PengajuanUserController;
use App\Http\Controllers\UserKehadiranController; // Pastikan Controller ini dibuat
use App\Http\Controllers\UserJadwalController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\USerDashboardController;

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
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/presensi/pdf', [UserDashboardController::class, 'downloadPdf'])->name('presensi.pdf');
    Route::get('/presensi/csv', [UserDashboardController::class, 'exportCsv'])->name('presensi.csv');
    // Profile Management
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- PEMBARUAN MENU KEHADIRAN (Hanya akses via WiFi Local) ---
   // Di dalam Route::middleware('auth')->group(function () { ...
    Route::get('/kehadiran', [UserKehadiranController::class, 'index'])->name('kehadiran');
    Route::post('/kehadiran/check-in', [UserKehadiranController::class, 'checkIn'])->name('kehadiran.checkin');
    Route::post('/kehadiran/check-out', [UserKehadiranController::class, 'checkOut'])->name('kehadiran.checkout');
    Route::get('/jadwal', [UserJadwalController::class, 'index'])->name('jadwal');
    // Menu Riwayat
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat');

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
    Route::get('/UnitJabatan', [UJController::class, 'index'])->name('UnitJabatan');

    Route::post('/jabatans', [UJController::class, 'storeJabatan'])->name('jabatans.store');
    Route::put('/jabatans/{id}', [UJController::class, 'updateJabatan'])->name('jabatans.update');
    Route::delete('/jabatans/{id}', [UJController::class, 'destroyJabatan'])->name('jabatans.destroy');

    Route::post('/units', [UJController::class, 'storeUnit'])->name('units.store');
    Route::put('/units/{id}', [UJController::class, 'updateUnit'])->name('units.update');
    Route::delete('/units/{id}', [UJController::class, 'destroyUnit'])->name('units.destroy');

    // Kehadiran (Admin Side)
    Route::get('/kehadirans', [KehadiranController::class, 'index'])->name('kehadirans');
    Route::post('/kehadirans', [KehadiranController::class, 'store'])->name('kehadirans.store');
    Route::put('/kehadirans/{id}', [KehadiranController::class, 'update'])->name('kehadirans.update');
    Route::delete('/kehadirans/{id}', [KehadiranController::class, 'destroy'])->name('kehadirans.destroy');

    // Jadwal
    Route::get('/jadwals', [JadwalController::class, 'index'])->name('jadwals');
    Route::post('/jadwals', [JadwalController::class, 'store'])->name('jadwals.store');
    Route::put('/jadwals/{id}', [JadwalController::class, 'update'])->name('jadwals.update');
    Route::delete('/jadwals/{id}', [JadwalController::class, 'destroy'])->name('jadwals.destroy');
    Route::post('/jadwals/autofill', [JadwalController::class, 'autofill'])->name('jadwals.autofill');

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