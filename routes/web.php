<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;


// Halaman Depan
Route::get('/', function () {
    return view('welcome');
});

// Route untuk Google Auth
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// Dashboard - Menggunakan Middleware Auth & Verified
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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
Route::middleware(['auth'])->prefix('admin')->group(function () {
    
    // URL: /admin/dashboards
    Route::get('/dashboards', [AdminController::class, 'dashboards'])->name('admin.dashboards');
    
    // URL: /admin/kehadirans
    Route::get('/kehadirans', [AdminController::class, 'kehadirans'])->name('admin.kehadirans');
    
    // URL: /admin/pengajuans
    Route::get('/pengajuans', [AdminController::class, 'pengajuans'])->name('admin.pengajuans');
    
    // URL: /admin/karyawans
    Route::get('/karyawans', [AdminController::class, 'karyawans'])->name('admin.karyawans');

});
require __DIR__.'/auth.php';