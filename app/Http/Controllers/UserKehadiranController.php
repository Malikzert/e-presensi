<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Kehadiran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use App\Notifications\AbsensiNotification; 

class UserKehadiranController extends Controller
{
    // Koordinat RS Anna Medika
    private $rsLat = -7.0494833; 
    private $rsLng = 112.7298311;
    private $maxRadius = 100; // Radius toleransi dalam meter

    /**
     * Helper untuk mengambil IP Publik via ipify.org
     */
    private function getPublicIp()
    {
        try {
            // Timeout 5 detik agar tidak menghambat loading jika API lambat
            $response = Http::timeout(5)->get('https://api.ipify.org?format=json');
            if ($response->successful()) {
                return $response->json()['ip'];
            }
        } catch (\Exception $e) {
            return request()->ip(); // Fallback ke IP request biasa jika API gagal
        }
        return request()->ip();
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $sekarang = Carbon::now('Asia/Jakarta');
        $hariIni = $sekarang->toDateString();
        $kemarin = $sekarang->copy()->subDay()->toDateString();

        // Ambil IP Publik untuk validasi tampilan di index
        $currentIp = $this->getPublicIp();
        
        // --- KONFIGURASI IP ---
        // Ganti '114.125.xx.xx' dengan IP Publik Wifi RS yang Anda dapatkan dari Google
        $allowedIps = ['127.0.0.1', '::1', '192.168.1.1', '114.125.xx.xx']; 
        $isLocalWifi = in_array($currentIp, $allowedIps);

        $semuaJadwal = Jadwal::where('user_id', $user->id)
            ->whereIn('tanggal', [$hariIni, $kemarin])
            ->with('shift')
            ->get();

        $jadwal = $semuaJadwal->filter(function($j) use ($sekarang) {
            if (!$j->shift) return false;
            $dtMasuk = Carbon::parse($j->tanggal . ' ' . $j->shift->jam_masuk, 'Asia/Jakarta');
            $dtPulang = Carbon::parse($j->tanggal . ' ' . $j->shift->jam_pulang, 'Asia/Jakarta');
            if ($dtPulang->lt($dtMasuk)) { $dtPulang->addDay(); }
            
            $startAbsen = $dtMasuk->copy()->subMinutes(30);
            return $sekarang->between($startAbsen, $dtPulang);
        })->first();

        $jadwalInfo = $jadwal ?? $semuaJadwal->where('tanggal', $hariIni)->first();
        $isInShiftTime = $jadwal ? true : false;

        $presensi = null;
        if ($jadwalInfo) {
            $presensi = Kehadiran::where('user_id', $user->id)
                ->where('tanggal', $jadwalInfo->tanggal)
                ->first();
        }

        $sudahAbsenMasuk = $presensi && $presensi->jam_masuk;
        $sudahAbsenPulang = $presensi && $presensi->jam_pulang;
        $sudahWaktunyaPulang = false;

        if ($jadwalInfo && $jadwalInfo->shift) {
            $waktuPulang = Carbon::parse($jadwalInfo->tanggal . ' ' . $jadwalInfo->shift->jam_pulang, 'Asia/Jakarta');
            $waktuMasuk = Carbon::parse($jadwalInfo->tanggal . ' ' . $jadwalInfo->shift->jam_masuk, 'Asia/Jakarta');
            if ($waktuPulang->lt($waktuMasuk)) { $waktuPulang->addDay(); }
            $sudahWaktunyaPulang = $sekarang->greaterThanOrEqualTo($waktuPulang);
        }

        $perluAbsen = false;
        $pesanNotif = null;

        if ($jadwalInfo && $jadwalInfo->shift) {
            if (!$sudahAbsenMasuk) {
                $waktuMulaiAbsen = Carbon::parse($jadwalInfo->tanggal . ' ' . $jadwalInfo->shift->jam_masuk, 'Asia/Jakarta')->subMinutes(30);
                if ($sekarang->greaterThanOrEqualTo($waktuMulaiAbsen) && !$sudahWaktunyaPulang) {
                    $perluAbsen = true;
                    $pesanNotif = "Waktunya Check-in!";
                }
            } 
            elseif ($sudahAbsenMasuk && !$sudahAbsenPulang && $sudahWaktunyaPulang) {
                $perluAbsen = true;
                $pesanNotif = "Waktunya Check-out!";
            }
        }

        if ($perluAbsen && $pesanNotif && $user->notif_pengingat) {
            $notifBelumDibaca = $user->unreadNotifications()
                ->where('type', 'App\Notifications\AbsensiNotification')
                ->where('data', 'like', '%' . $pesanNotif . '%')
                ->exists();

            if (!$notifBelumDibaca) {
                $user->notify(new AbsensiNotification($pesanNotif));
            }
        } else {
            $user->unreadNotifications()
                ->where('type', 'App\Notifications\AbsensiNotification')
                ->get()
                ->markAsRead();
        }

        return view('kehadiran', compact(
            'jadwal', 
            'jadwalInfo', 
            'presensi', 
            'isLocalWifi', 
            'isInShiftTime',
            'sudahAbsenMasuk',
            'sudahAbsenPulang',
            'sudahWaktunyaPulang',
            'perluAbsen'
        ));
    }

    public function checkIn(Request $request)
    {
        $ipUser = $this->getPublicIp(); 

        // 1. Validasi IP/Wifi (Hardcode IP RS Anda di sini)
        $allowedIps = ['127.0.0.1', '::1', '192.168.1.1', '114.125.xx.xx']; 
        if (!in_array($ipUser, $allowedIps)) {
            return response()->json(['message' => 'Gagal! Anda harus terhubung ke Wi-Fi RS.'], 403);
        }

        // 2. Validasi Jarak GPS
        if (!$request->lat || !$request->lng) {
            return response()->json(['message' => 'Gagal! Lokasi GPS tidak terdeteksi.'], 422);
        }

        $distance = $this->calculateDistance($request->lat, $request->lng);
        if ($distance > $this->maxRadius) {
            return response()->json(['message' => 'Gagal! Anda berada di luar area RS Anna Medika (' . round($distance) . 'm).'], 403);
        }

        $user = Auth::user();
        $sekarang = Carbon::now('Asia/Jakarta');
        $hariIni = $sekarang->toDateString();
        $kemarin = $sekarang->copy()->subDay()->toDateString();

        $lokasiUser = $request->lat . ',' . $request->lng;

        $jadwal = Jadwal::where('user_id', $user->id)
            ->whereIn('tanggal', [$hariIni, $kemarin])
            ->with('shift')
            ->get()
            ->filter(function($j) use ($sekarang) {
                if (!$j->shift) return false;
                $dtMasuk = Carbon::parse($j->tanggal . ' ' . $j->shift->jam_masuk, 'Asia/Jakarta');
                $dtPulang = Carbon::parse($j->tanggal . ' ' . $j->shift->jam_pulang, 'Asia/Jakarta');
                if ($dtPulang->lt($dtMasuk)) { $dtPulang->addDay(); }
                return $sekarang->between($dtMasuk->copy()->subMinutes(30), $dtPulang);
            })->first();

        if (!$jadwal) {
            return response()->json(['message' => 'Bukan waktu shift Anda.'], 403);
        }

        $existing = Kehadiran::where('user_id', $user->id)
            ->where('tanggal', $jadwal->tanggal)
            ->first();

        if ($existing) {
            return response()->json(['message' => 'Anda sudah melakukan check-in.'], 422);
        }

        // --- LOGIKA TERLAMBAT (Toleransi 15 Menit) ---
        $dtMasuk = Carbon::parse($jadwal->tanggal . ' ' . $jadwal->shift->jam_masuk, 'Asia/Jakarta');
        $batasToleransi = $dtMasuk->copy()->addMinutes(15);

        $status = $sekarang->gt($batasToleransi) ? 'Hadir (Terlambat)' : 'hadir';

        Kehadiran::create([
            'user_id'           => $user->id,
            'shift_id'          => $jadwal->shift_id,
            'tanggal'           => $jadwal->tanggal, 
            'jam_masuk'         => $sekarang->format('H:i:s'),
            'status'            => $status,
            'lokasi_masuk'      => $lokasiUser,
            'ip_address_masuk'  => $ipUser, 
        ]);

        return response()->json(['message' => 'Check-in berhasil! Selamat bertugas.']);
    }

    public function checkOut(Request $request)
    {
        $ipUser = $this->getPublicIp(); 

        $allowedIps = ['127.0.0.1', '::1', '192.168.1.1', '114.125.xx.xx']; 
        if (!in_array($ipUser, $allowedIps)) {
            return response()->json(['message' => 'Gagal! Anda harus terhubung ke Wi-Fi RS.'], 403);
        }

        $lokasiUser = ($request->lat && $request->lng) ? $request->lat . ',' . $request->lng : null;

        if ($request->lat && $request->lng) {
            $distance = $this->calculateDistance($request->lat, $request->lng);
            if ($distance > $this->maxRadius) {
                return response()->json(['message' => 'Gagal! Check-out harus dilakukan di area RS.'], 403);
            }
        }

        $user = Auth::user();
        $kehadiran = Kehadiran::where('user_id', $user->id)
                             ->whereNull('jam_pulang')
                             ->orderBy('tanggal', 'desc')
                             ->orderBy('jam_masuk', 'desc')
                             ->first();

        if (!$kehadiran) {
            return response()->json(['message' => 'Data check-in aktif tidak ditemukan.'], 422);
        }

        try {
            $kehadiran->update([
                'jam_pulang'         => Carbon::now('Asia/Jakarta')->format('H:i:s'),
                'lokasi_pulang'      => $lokasiUser,
                'ip_address_pulang'  => $ipUser,
            ]);
            return response()->json(['message' => 'Check-out berhasil! Terima kasih.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menyimpan data pulang.'], 500);
        }
    }

    private function calculateDistance($userLat, $userLng)
    {
        $earthRadius = 6371000; // Meter
        $latDiff = deg2rad($userLat - $this->rsLat);
        $lngDiff = deg2rad($userLng - $this->rsLng);

        $a = sin($latDiff / 2) * sin($latDiff / 2) +
            cos(deg2rad($this->rsLat)) * cos(deg2rad($userLat)) *
            sin($lngDiff / 2) * sin($lngDiff / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}