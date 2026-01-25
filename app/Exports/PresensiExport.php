<?php

namespace App\Exports;

use App\Models\Kehadiran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\Auth;

class PresensiExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        // Gunakan with('shift') untuk mengambil data dari tabel shifts
        return Kehadiran::with('shift')
            ->where('user_id', Auth::id())
            ->latest('tanggal')
            ->get();
    }

    public function headings(): array
    {
        return [
            ['LAPORAN PRESENSI PEGAWAI - RSU ANNA MEDIKA'],
            ['Dicetak pada: ' . now()->format('d-m-Y')],
            [], 
            [
                'ID',
                'TANGGAL',
                'JAM MASUK',
                'JAM PULANG',
                'STATUS',
                'NAMA SHIFT', // Kolom Shift
            ]
        ];
    }

    public function map($kehadiran): array
    {
        return [
            $kehadiran->id,
            \Carbon\Carbon::parse($kehadiran->tanggal)->format('d/m/Y'),
            $kehadiran->jam_masuk ?? '--:--',
            $kehadiran->jam_pulang ?? '--:--',
            strtoupper($kehadiran->status),
            // Mengambil nama_shift dari relasi tabel shifts
            $kehadiran->shift ? $kehadiran->shift->nama_shift : '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $this->collection()->count() + 4;
        
        return [
            1    => [
                'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '065F46']],
            ],
            
            4    => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '10B981']
                ],
                'alignment' => ['horizontal' => 'center']
            ],

            // Border otomatis mengikuti jumlah baris data
            'A4:F' . $lastRow => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ],
        ];
    }
}