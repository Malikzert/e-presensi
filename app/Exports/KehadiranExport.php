<?php

namespace App\Exports;

use App\Models\Kehadiran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class KehadiranExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * Ambil data kehadiran bulan ini
    */
    public function collection()
    {
        return Kehadiran::with('user')
            ->whereMonth('tanggal', now()->month)
            ->get();
    }

    /**
    * Header Tabel Excel
    */
    public function headings(): array
    {
        return [
            'Nama Karyawan',
            'NIK',
            'Tanggal',
            'Jam Masuk',
            'Jam Pulang',
            'Status',
            'Lokasi Masuk',
        ];
    }

    /**
    * Memetakan data ke kolom Excel
    */
    public function map($kehadiran): array
    {
        return [
            $kehadiran->user->name,
            $kehadiran->user->nik,
            $kehadiran->tanggal,
            $kehadiran->jam_masuk,
            $kehadiran->jam_pulang,
            $kehadiran->status,
            $kehadiran->lokasi_masuk,
        ];
    }
}
