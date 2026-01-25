<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserJadwalController extends Controller
{
    /**
     * Menampilkan daftar jadwal kerja user.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Ambil filter bulan dan tahun dari request, default ke bulan ini
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));

        // Ambil data jadwal berdasarkan user, bulan, dan tahun
        $jadwalUser = Jadwal::with(['shift'])
            ->where('user_id', $user->id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal', 'asc')
            ->get();

        // Data untuk dropdown filter di view
        $daftarBulan = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', 
            '04' => 'April', '05' => 'Mei', '06' => 'Juni',
            '07' => 'Juli', '08' => 'Agustus', '09' => 'September', 
            '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];

        return view('jadwal', compact('jadwalUser', 'daftarBulan', 'bulan', 'tahun'));
    }
}