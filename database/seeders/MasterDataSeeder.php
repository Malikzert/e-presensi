<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;
use App\Models\Jabatan;

class MasterDataSeeder extends Seeder
{
    public function run()
    {
        // --- SEED DATA JABATAN (Berdasarkan Gambar) ---
        $jabatans = [
            // Manajemen & Struktural
            'Direktur',
            'Kepala Instalasi Rawat Jalan, VK, & OK',
            'Manajer Pelayanan Medis',
            'Kepala Instalasi Rawat Inap & IGD',
            'Kepala Bidang Keperawatan',
            'Manajer Umum & SDM',
            'Manajer Penunjang Medis',
            'Manajer Keuangan',
            
            // Dokter Spesialis & Umum
            'Staff Dokter Umum',
            'Dokter Casemix',
            'Dokter Sp. Anestesi',
            'Dokter Sp. OG',
            'Dokter Sp. Penyakit Dalam',
            'Dokter Sp. Saraf',
            'Dokter Sp. Paru',
            'Dokter Sp. Jantung',
            'Dokter Sp. Orthopedi',
            'Dokter Sp. Anak',
            'Dokter Sp. Kulit Kelamin',
            'Dokter Sp. Urologi',
            'Dokter Sp. THT-KL',
            'Dokter Sp. Mata',
            'Dokter Sp. Bedah Umum',
            
            // Koordinator & Penanggung Jawab
            'Koordinator IGD',
            'Koordinator HCU',
            'Koordinator Rawat Jalan',
            'Koordinator Kamar Bersalin',
            'Koordinator Kamar Operasi',
            'Koordinator Laboratorium',
            'Koordinator Radiologi',
            'Koordinator Rekam Medis',
            'Koordinator Farmasi',
            'Koordinator Gizi',
            'Koordinator Kasir',
            'Penanggung Jawab IT',
            'Penanggung Jawab CSSD',
            
            // Staff Pelaksana & Penunjang
            'Staff Perawat',
            'Staff Bidan',
            'Staff Analis',
            'Staff Radiologi',
            'Staff Rakam Medis',
            'Apoteker Pendamping',
            'Asisten Apoteker / TTK',
            'Staff Teknik Elektromedik',
            'Staff Marketing',
            'Staff Driver Ambulance',
            'Staff Casemix',
            'Staff Transporter',
            'Staff Loundry',
            'Staff Pramusaji',
            'Staff Teknisi Umum',
            'Staff Admin',
        ];

        foreach ($jabatans as $j) {
            Jabatan::updateOrCreate(['nama_jabatan' => $j]);
        }

        // --- SEED DATA UNIT (Tetap Menggunakan Data Sebelumnya) ---
        $units = [
            ['kode_unit' => 'DIR', 'nama_unit' => 'Direktur'],
            ['kode_unit' => 'RWN', 'nama_unit' => 'Instalasi Rawat Inap'],
            ['kode_unit' => 'RWJ', 'nama_unit' => 'Instalasi Rawat Jalan'],
            ['kode_unit' => 'IGD', 'nama_unit' => 'Instalasi Gawat Darurat'],
            ['kode_unit' => 'MPM', 'nama_unit' => 'Manajemen Pelayanan Medis'],
            ['kode_unit' => 'VK',  'nama_unit' => 'Kamar Bersalin'],
            ['kode_unit' => 'OK',  'nama_unit' => 'Kamar Operasi'],
            ['kode_unit' => 'ICU', 'nama_unit' => 'Intensive Care Unit'],
            ['kode_unit' => 'NICU', 'nama_unit' => 'Neonatus Intensive Care Unit'],
            ['kode_unit' => 'LAB', 'nama_unit' => 'Laboratorium'],
            ['kode_unit' => 'RAD', 'nama_unit' => 'Radiologi'],
            ['kode_unit' => 'RM',  'nama_unit' => 'Rekam Medis'],
            ['kode_unit' => 'FAR', 'nama_unit' => 'Instalasi Farmasi'],
            ['kode_unit' => 'GZ',  'nama_unit' => 'Gizi'],
            ['kode_unit' => 'IT',  'nama_unit' => 'Information Technology'],
            ['kode_unit' => 'KSR', 'nama_unit' => 'Kasir'],
            ['kode_unit' => 'CSSD', 'nama_unit' => 'CSSD'],
            ['kode_unit' => 'LND', 'nama_unit' => 'Laundry'],
        ];

        foreach ($units as $u) {
            Unit::updateOrCreate(['kode_unit' => $u['kode_unit']], $u);
        }
    }
}