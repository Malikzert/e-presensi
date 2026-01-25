<?php

namespace App\Http\Controllers;

use App\Models\Kehadiran;
use App\Models\Pengajuan; // Pastikan Anda memiliki model untuk cuti/izin
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Ambil data riwayat Kehadiran (Kehadiran)
        $riwayatKehadiran = Kehadiran::where('user_id', $userId)
            ->orderBy('tanggal', 'desc')
            ->limit(31) // Ambil sebulan terakhir
            ->get();

        // Ambil data pengajuan (Cuti/Izin)
        // Sesuaikan dengan nama model dan kolom di database Anda
        $riwayatPengajuan = Pengajuan::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('riwayat', compact('riwayatKehadiran', 'riwayatPengajuan'));
    }
}