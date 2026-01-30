<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\Kehadiran; // Gunakan ini
use App\Models\Pengajuan; // Gunakan ini
use Carbon\Carbon;
use App\Exports\KehadiranExport;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $totalKaryawan = Karyawan::where('is_admin', false)->count();
        
        // Sesuaikan pemanggilan model menjadi Kehadiran
        $hadirHariIni = Kehadiran::whereDate('tanggal', $today)
                        ->whereIn('status', ['hadir', 'Hadir (Terlambat)'])
                        ->count();

        $pendingIzin = Pengajuan::where('status', 'pending')->count();

        $terlambat = Kehadiran::whereDate('tanggal', $today)
                        ->where('status', 'Hadir (Terlambat)')
                        ->count();

        $latestActivities = Kehadiran::with('Karyawan')
                            ->whereDate('tanggal', $today)
                            ->latest()
                            ->take(5)
                            ->get();

        return view('admin.dashboards', compact(
            'totalKaryawan', 'hadirHariIni', 'pendingIzin', 'terlambat', 'latestActivities'
        ));
    }
    public function exportExcel()
    {
        return Excel::download(new KehadiranExport, 'Laporan_Kehadiran_'.now()->format('M_Y').'.xlsx');
    }
}