<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Departemen;
use App\Models\Shift;
use App\Models\User;
use App\Models\Kehadiran;
use App\Models\Pengajuan;
use Illuminate\Support\Facades\Hash;

class DatabasePresensiSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Isi Data Departemen
        $depts = [
            ['nama_departemen' => 'IGD', 'kode_departemen' => 'IGD01'],
            ['nama_departemen' => 'Rawat Inap', 'kode_departemen' => 'RI01'],
            ['nama_departemen' => 'Farmasi', 'kode_departemen' => 'FAR01'],
            ['nama_departemen' => 'Administrasi', 'kode_departemen' => 'ADM01'],
            ['nama_departemen' => 'IT', 'kode_departemen' => 'IT01'],
        ];
        foreach ($depts as $dept) { Departemen::create($dept); }

        // 2. Isi Data Shift
        $shifts = [
            ['nama_shift' => 'Pagi', 'jam_masuk' => '07:00:00', 'jam_pulang' => '14:00:00'],
            ['nama_shift' => 'Siang', 'jam_masuk' => '14:00:00', 'jam_pulang' => '21:00:00'],
            ['nama_shift' => 'Malam', 'jam_masuk' => '21:00:00', 'jam_pulang' => '07:00:00'],
            ['nama_shift' => 'Non-Shift', 'jam_masuk' => '08:00:00', 'jam_pulang' => '16:00:00'],
        ];
        foreach ($shifts as $shift) { Shift::create($shift); }

        // 3. Isi Akun Admin
        User::create([
            'name' => 'Admin RSU Anna Medika',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'),
            'nik' => '12345678',
            'jabatan' => 'Kepala IT',
            'is_admin' => true,
            'status' => 'active', // Tambahan status eksplisit
            'departemen_id' => 5, // IT
            'foto' => 'default.jpg',
        ]);

        // 4. Isi 2 User Role (Bukan Admin)
        $user1 = User::create([
            'name' => 'Abdul Malik',
            'email' => 'malikdark17@gmail.com',
            'password' => Hash::make('malik12345'),
            'nik' => '11112222',
            'jabatan' => 'Perawat',
            'is_admin' => false,
            'status' => 'active', // Tambahan status eksplisit
            'departemen_id' => 1, // IGD
            'foto' => 'default.jpg',
        ]);

        $user2 = User::create([
            'name' => 'Siti Aminah',
            'email' => 'siti@gmail.com',
            'password' => Hash::make('password123'),
            'nik' => '33334444',
            'jabatan' => 'Apoteker',
            'is_admin' => false,
            'status' => 'active', // Tambahan status eksplisit
            'departemen_id' => 3, // Farmasi
            'foto' => 'default.jpg',
        ]);

        // 5. Isi Dump Data Kehadiran (Abdul Malik Absen)
        Kehadiran::create([
            'user_id' => $user1->id,
            'shift_id' => 1, // Pagi
            'tanggal' => now()->format('Y-m-d'),
            'jam_masuk' => '06:55:00',
            'lokasi_masuk' => '-6.12345, 106.12345',
            'status' => 'Hadir'
        ]);

        // 6. Isi Dump Data Pengajuan (Siti Izin)
        Pengajuan::create([
            'user_id' => $user2->id,
            'jenis_pengajuan' => 'Sakit',
            'tgl_mulai' => now()->addDay()->format('Y-m-d'),
            'tgl_selesai' => now()->addDays(2)->format('Y-m-d'),
            'alasan' => 'Demam tinggi, butuh istirahat.',
            'status' => 'Pending'
        ]);
    }
}