<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Kehadiran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserKehadiranController extends Controller
{
    // Koordinat RS Anna Medika
    private $rsLat = -7.125506; 
    private $rsLng = 112.724977;
    private $maxRadius = 100; // Radius toleransi dalam meter

    public function index(Request $request)
    {
        $user = Auth::user();
        $sekarang = Carbon::now('Asia/Jakarta');
        $hariIni = $sekarang->toDateString();
        $kemarin = $sekarang->copy()->subDay()->toDateString();

        $allowedIps = ['127.0.0.1', '::1', '192.168.1.1'];
        $isLocalWifi = in_array($request->ip(), $allowedIps);

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

        return view('kehadiran', compact('jadwal', 'jadwalInfo', 'presensi', 'isLocalWifi', 'isInShiftTime'));
    }

    public function checkIn(Request $request)
    {
        // 1. Validasi IP/Wifi
        $allowedIps = ['127.0.0.1', '::1', '192.168.1.1']; 
        if (!in_array($request->ip(), $allowedIps)) {
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

        // Ambil Data IP dan Lokasi
        $ipUser = $request->ip();
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

        $dtMasuk = Carbon::parse($jadwal->tanggal . ' ' . $jadwal->shift->jam_masuk, 'Asia/Jakarta');
        $status = $sekarang->gt($dtMasuk) ? 'Hadir (Terlambat)' : 'hadir';

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
        $allowedIps = ['127.0.0.1', '::1', '192.168.1.1']; 
        if (!in_array($request->ip(), $allowedIps)) {
            return response()->json(['message' => 'Gagal! Anda harus terhubung ke Wi-Fi RS.'], 403);
        }

        // Ambil Data IP dan Lokasi
        $ipUser = $request->ip();
        $lokasiUser = ($request->lat && $request->lng) ? $request->lat . ',' . $request->lng : null;

        // Validasi GPS di Check-out
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