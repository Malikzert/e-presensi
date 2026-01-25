<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kehadiran;
use App\Models\Jadwal;
use Carbon\Carbon;

class UserTwoSeeder extends Seeder
{
    public function run()
    {
        $userId = 2; // ID Abdul Malik A
        // Pola Jadwal Anda (31 Hari)
        $roster = str_split('PMSSPMPLSMLMPSLSPPSMSLMPSPPLPLM'); 
        
        $shiftMap = [
            'P' => 1, // Pagi
            'S' => 2, // Siang
            'M' => 3, // Malam
        ];

        // Buat data untuk Januari dan Februari 2026
        $months = [1, 2];

        foreach ($months as $month) {
            $daysInMonth = Carbon::create(2026, $month)->daysInMonth;

            for ($i = 0; $i < $daysInMonth; $i++) {
                $tanggal = Carbon::create(2026, $month, $i + 1);
                $kodeShift = $roster[$i] ?? 'P'; 

                // Jika Libur (L), lewati (Tidak ada jadwal, tidak ada kehadiran)
                if ($kodeShift === 'L') continue;

                $shiftId = $shiftMap[$kodeShift];

                // 1. DAFTARKAN JADWAL (Selalu dibuat jika bukan 'L')
                Jadwal::updateOrCreate(
                    ['user_id' => $userId, 'tanggal' => $tanggal->toDateString()],
                    ['shift_id' => $shiftId]
                );

                // 2. LOGIKA KEHADIRAN (Dibuat bervariasi)
                
                // Variasi Alpha: Misal tgl 12 Januari dan 20 Februari sengaja tidak dibuat kehadirannya
                if (($month == 1 && $i + 1 == 12) || ($month == 2 && $i + 1 == 20)) {
                    continue; // Skip Insert Kehadiran (Status jadi Alpha/Bolos)
                }

                // Variasi Izin/Sakit: Misal tgl 5 setiap bulan
                if ($i + 1 == 5) {
                    Kehadiran::updateOrCreate(
                        ['user_id' => $userId, 'tanggal' => $tanggal->toDateString()],
                        [
                            'shift_id' => $shiftId,
                            'status' => (rand(1, 2) == 1) ? 'Izin' : 'Sakit',
                            'jam_masuk' => null,
                            'jam_pulang' => null,
                            'created_at' => $tanggal,
                            'updated_at' => $tanggal,
                        ]
                    );
                    continue;
                }

                // Sisanya masuk normal atau terlambat
                $isTerlambat = rand(1, 10) > 8; // 20% kemungkinan terlambat
                $jamMasuk = $this->getJamMasuk($shiftId, $isTerlambat);

                Kehadiran::updateOrCreate(
                    ['user_id' => $userId, 'tanggal' => $tanggal->toDateString()],
                    [
                        'shift_id' => $shiftId,
                        'jam_masuk' => $jamMasuk,
                        'jam_pulang' => $this->getJamPulang($shiftId),
                        'status' => $isTerlambat ? 'Hadir (Terlambat)' : 'Hadir',
                        'lokasi_masuk' => '-7.0345,112.7436',
                        'lokasi_pulang' => '-7.0345,112.7436',
                        'ip_address_masuk' => '127.0.0.1',
                        'ip_address_pulang' => '127.0.0.1',
                        'created_at' => $tanggal->copy()->setTime(8, 0, 0),
                        'updated_at' => $tanggal->copy()->setTime(16, 0, 0),
                    ]
                );
            }
        }
    }

    private function getJamMasuk($shiftId, $late) {
        if ($shiftId == 1) return $late ? '07:15:00' : '06:55:00';
        if ($shiftId == 2) return $late ? '14:12:00' : '13:50:00';
        return $late ? '22:10:00' : '21:45:00';
    }

    private function getJamPulang($shiftId) {
        if ($shiftId == 1) return '14:00:00';
        if ($shiftId == 2) return '21:00:00';
        return '07:00:00';
    }
}