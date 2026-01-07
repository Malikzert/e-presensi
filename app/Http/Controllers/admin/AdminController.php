<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Models\Attendance; <-- Contoh Model
// use App\Models\Submission; <-- Contoh Model

class AdminController extends Controller
{
    /**
     * Menampilkan Ringkasan Statistik
     */
    public function dashboards() 
    {
        // Di sini nantinya Anda mengambil data dari database
        // $totalKaryawan = User::count();
        
        return view('admin.dashboards', [
            'title' => 'Dashboard Admin'
        ]);
    }

    /**
     * Menampilkan Monitoring Seluruh Kehadiran
     */
    public function kehadirans() 
    {
        return view('admin.kehadirans', [
            'title' => 'Monitoring Kehadiran'
        ]);
    }

    /**
     * Menampilkan Daftar Pengajuan Cuti/Izin Semua Karyawan
     */
    public function pengajuans() 
    {
        return view('admin.pengajuans', [
            'title' => 'Kelola Pengajuan'
        ]);
    }

    /**
     * Menampilkan Daftar Manajemen Karyawan
     */
    public function karyawans() 
    {
        return view('admin.karyawans', [
            'title' => 'Data Karyawan'
        ]);
    }
}