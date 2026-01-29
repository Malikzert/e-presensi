<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Jadwal;
use App\Models\Kehadiran;
use App\Notifications\AbsensiNotification;
use Carbon\Carbon;

class UserSettingController extends Controller
{
    public function update(Request $request)
    {
        $user = auth()->user();
        $isNotifOn = $request->has('notif_pengingat');

        $user->update([
            'notif_pengingat'         => $isNotifOn,
            'notif_status_pengajuan' => $request->has('notif_status_pengajuan'),
            'track_lokasi'           => $request->has('track_lokasi'),
        ]);

        // --- LOGIKA INSTANT NOTIFIKASI ---
        if ($isNotifOn) {
            $sekarang = Carbon::now('Asia/Jakarta');
            $hariIni = $sekarang->toDateString();

            // 1. Ambil jadwal terdekat (hari ini)
            $jadwal = Jadwal::where('user_id', $user->id)
                ->where('tanggal', $hariIni)
                ->with('shift')
                ->first();

            if ($jadwal && $jadwal->shift) {
                $presensi = Kehadiran::where('user_id', $user->id)
                    ->where('tanggal', $hariIni)
                    ->first();

                $sudahAbsenMasuk = $presensi && $presensi->jam_masuk;
                $sudahAbsenPulang = $presensi && $presensi->jam_pulang;

                // Hitung waktu pulang (handle shift malam)
                $waktuPulang = Carbon::parse($jadwal->tanggal . ' ' . $jadwal->shift->jam_pulang, 'Asia/Jakarta');
                $waktuMasuk = Carbon::parse($jadwal->tanggal . ' ' . $jadwal->shift->jam_masuk, 'Asia/Jakarta');
                if ($waktuPulang->lt($waktuMasuk)) { $waktuPulang->addDay(); }

                $sudahWaktunyaPulang = $sekarang->greaterThanOrEqualTo($waktuPulang);
                $pesanNotif = null;

                // Tentukan apakah saat ini user harusnya dapat notif
                if (!$sudahAbsenMasuk && $sekarang->greaterThanOrEqualTo($waktuMasuk->copy()->subMinutes(30)) && !$sudahWaktunyaPulang) {
                    $pesanNotif = "Waktunya Check-in!";
                } elseif ($sudahAbsenMasuk && !$sudahAbsenPulang && $sudahWaktunyaPulang) {
                    $pesanNotif = "Waktunya Check-out!";
                }

                if ($pesanNotif) {
                    // Cek duplikat sebelum kirim
                    $exists = $user->unreadNotifications()
                        ->where('data', 'like', '%' . $pesanNotif . '%')
                        ->exists();
                    
                    if (!$exists) {
                        $user->notify(new AbsensiNotification($pesanNotif));
                    }
                }
            }
        } else {
            // Jika dimatikan, hapus/tandai dibaca semua notif absensi agar dot navbar hilang
            $user->unreadNotifications()
                ->where('type', 'App\Notifications\AbsensiNotification')
                ->get()
                ->markAsRead();
        }

        return back()->with('success', 'Pengaturan diperbarui');
    }
}