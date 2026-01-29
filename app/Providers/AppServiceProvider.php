<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Jadwal;
use App\Models\Kehadiran;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void { }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                // Gunakan timezone Jakarta secara eksplisit
                $sekarang = Carbon::now('Asia/Jakarta');
                $tanggalHariIni = $sekarang->toDateString();

                // 1. Ambil jadwal berdasarkan tanggal hari ini
                $jadwal = Jadwal::with('shift')
                                ->where('user_id', $user->id)
                                ->where('tanggal', $tanggalHariIni)
                                ->first();

                // 2. Cek data kehadiran
                $presensi = Kehadiran::where('user_id', $user->id)
                                    ->whereDate('tanggal', $tanggalHariIni)
                                    ->first();

                $perluAbsen = false;

                if ($jadwal && $jadwal->shift) {
                    $sudahAbsenMasuk = $presensi && $presensi->jam_masuk;
                    $sudahAbsenPulang = $presensi && $presensi->jam_pulang;

                    // Buat objek Carbon lengkap (Tanggal + Jam Shift) untuk perbandingan akurat
                    $waktuMasuk = Carbon::parse($jadwal->tanggal . ' ' . $jadwal->shift->jam_masuk, 'Asia/Jakarta');
                    $waktuPulang = Carbon::parse($jadwal->tanggal . ' ' . $jadwal->shift->jam_pulang, 'Asia/Jakarta');

                    // Jika shift malam (jam pulang lebih kecil dari jam masuk, misal 21:00 ke 07:00)
                    if ($waktuPulang->lt($waktuMasuk)) {
                        $waktuPulang->addDay();
                    }

                    // Toleransi: Notif muncul 30 menit sebelum jam masuk
                    $mulaiNotifMasuk = $waktuMasuk->copy()->subMinutes(30);

                    // KONDISI 1: Belum Check-in & sudah masuk waktu absen
                    if (!$sudahAbsenMasuk && $sekarang->greaterThanOrEqualTo($mulaiNotifMasuk)) {
                        $perluAbsen = true;
                    } 
                    // KONDISI 2: Sudah Check-in tapi belum Check-out (dan sudah lewat jam pulang)
                    elseif ($sudahAbsenMasuk && !$sudahAbsenPulang && $sekarang->greaterThanOrEqualTo($waktuPulang)) {
                        $perluAbsen = true;
                    }
                }

                $view->with('perluAbsen', $perluAbsen);
            }
        });
    }
}