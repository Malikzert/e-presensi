<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PengajuanSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Bersihkan data lama untuk menghindari bentrok ID
        // Matikan foreign key check sementara agar bisa truncate tabel yang berelasi
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('pengajuans')->truncate();
        DB::table('kategori_pengajuans')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Isi Data Kategori (Master)
        $kategoriData = ['Cuti', 'Izin', 'Sakit', 'Tukar Shift'];
        $kategoriIds = [];

        foreach ($kategoriData as $nama) {
            $id = DB::table('kategori_pengajuans')->insertGetId([
                'nama_pengajuan' => $nama,
                'slug'           => Str::slug($nama),
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
            $kategoriIds[$nama] = $id;
        }

        // 3. Isi Data Pengajuan (2 Data Contoh)
        DB::table('pengajuans')->insert([
            [
                'kategori_pengajuan_id' => $kategoriIds['Sakit'],
                'kode_pengajuan'        => 'SICK-' . now()->format('Ymd') . '-001',
                'karyawan_id'           => 3,
                'tgl_mulai'             => '2026-01-11',
                'tgl_selesai'           => '2026-01-12',
                'alasan'                => 'Demam tinggi, butuh istirahat.',
                'bukti'                 => '1768823644_Screenshot (1).png',
                'status'                => 'Pending',
                'created_at'            => '2026-01-10 18:53:54',
                'updated_at'            => '2026-01-19 11:54:04',
            ],
            [
                'kategori_pengajuan_id' => $kategoriIds['Izin'],
                'kode_pengajuan'        => 'IZN-' . now()->format('Ymd') . '-002',
                'karyawan_id'           => 2,
                'tgl_mulai'             => '2026-01-20',
                'tgl_selesai'           => '2026-01-21',
                'alasan'                => 'Ada urusan keluarga penting.',
                'bukti'                 => null,
                'status'                => 'Disetujui',
                'created_at'            => '2026-01-19 11:45:12',
                'updated_at'            => '2026-01-19 11:45:28',
            ]
        ]);
    }
}