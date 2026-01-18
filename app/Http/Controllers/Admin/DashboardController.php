<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
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

        $totalKaryawan = User::where('is_admin', false)->count();
        
        // Sesuaikan pemanggilan model menjadi Kehadiran
        $hadirHariIni = Kehadiran::whereDate('tanggal', $today)
                        ->whereIn('status', ['hadir', 'terlambat'])
                        ->count();

        $pendingIzin = Pengajuan::where('status', 'pending')->count();

        $terlambat = Kehadiran::whereDate('tanggal', $today)
                        ->where('status', 'terlambat')
                        ->count();

        $latestActivities = Kehadiran::with('user')
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