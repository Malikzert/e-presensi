<?php

namespace App\Http\Controllers;

use App\Models\Kehadiran;
use App\Exports\PresensiExport;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $now = Carbon::now();

        // 1. Data Status Hari Ini
        $presensiHariIni = Kehadiran::where('user_id', $user->id)
            ->whereDate('tanggal', Carbon::today())
            ->first();

        // 2. Hitung Rata-rata Bulanan
        $totalJadwalBulanIni = Jadwal::where('user_id', $user->id)
            ->whereMonth('tanggal', $now->month)
            ->whereYear('tanggal', $now->year)
            ->count() ?: 1;

        $hadirBulanIni = Kehadiran::where('user_id', $user->id)
            ->whereMonth('tanggal', $now->month)
            ->whereYear('tanggal', $now->year)
            ->whereIn('status', ['Hadir', 'Alpha', 'Hadir (Terlambat)', 'Izin', 'Sakit'])
            ->count();

        $monthlyRate = round(($hadirBulanIni / $totalJadwalBulanIni) * 100);

        // 3. Performa Tahunan
        $hadirTahunIni = Kehadiran::where('user_id', $user->id)
            ->whereYear('tanggal', $now->year)
            ->count();
        $totalJadwalTahunIni = Jadwal::where('user_id', $user->id)
            ->whereYear('tanggal', $now->year)
            ->count() ?: 1;
        $yearlyRate = round(($hadirTahunIni / $totalJadwalTahunIni) * 100);

        // 4. Log Terakhir (5 Aktivitas Terbaru)
        $logs = Kehadiran::where('user_id', $user->id)
            ->latest('tanggal')
            ->latest('created_at')
            ->take(5)
            ->get();
        $totalIzinSakit = Kehadiran::where('user_id', $user->id)
            ->whereYear('tanggal', $now->year)
            ->whereIn('status', ['Izin', 'Sakit'])
            ->count();
        // 5. Data untuk Grafik (Struktur baru untuk multi-filter)
        $chartData = $this->getChartData($user->id);

        return view('dashboard', compact(
            'presensiHariIni', 
            'monthlyRate', 
            'yearlyRate', 
            'logs', 
            'chartData',
            'totalIzinSakit'
        ));
    }

    private function getChartData($userId)
    {
        // Data Mingguan (7 Hari Terakhir)
        $weeklyLabels = [];
        $weeklyData = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::now()->subDays($i);
            $weeklyLabels[] = $day->isoFormat('ddd'); 
            $weeklyData[] = Kehadiran::where('user_id', $userId)
                ->whereDate('tanggal', $day->toDateString())
                ->count();
        }

        // Data Bulanan (6 Bulan Terakhir)
        $monthlyLabels = [];
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthlyLabels[] = $month->format('M');
            $monthlyData[] = Kehadiran::where('user_id', $userId)
                ->whereMonth('tanggal', $month->month)
                ->whereYear('tanggal', $month->year)
                ->count();
        }

        // Data Tahunan (3 Tahun Terakhir)
        $yearlyLabels = [];
        $yearlyData = [];
        for ($i = 2; $i >= 0; $i--) {
            $year = Carbon::now()->subYears($i);
            $yearlyLabels[] = $year->format('Y');
            $yearlyData[] = Kehadiran::where('user_id', $userId)
                ->whereYear('tanggal', $year->year)
                ->count();
        }

        return [
            'weekly' => ['labels' => $weeklyLabels, 'data' => $weeklyData],
            'monthly' => ['labels' => $monthlyLabels, 'data' => $monthlyData],
            'yearly' => ['labels' => $yearlyLabels, 'data' => $yearlyData],
        ];
    }

    public function downloadPdf()
    {
        $user = Auth::user();
        $data = Kehadiran::where('user_id', $user->id)
            ->latest('tanggal')
            ->get();

        $pdf = Pdf::loadView('reports.presensi_pdf', [
            'data' => $data,
            'user' => $user,
            'title' => 'Laporan Presensi Karyawan'
        ]);

        return $pdf->download("Laporan_Presensi_{$user->name}.pdf");
    }

    public function exportCsv()
    {
        $fileName = 'Presensi_' . Auth::user()->name . '_' . date('Y-m-d') . '.xlsx';
        return Excel::download(new PresensiExport, $fileName, \Maatwebsite\Excel\Excel::XLSX);
    }
}