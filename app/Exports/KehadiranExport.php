<?php

namespace App\Exports;

use App\Models\Kehadiran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class KehadiranExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithEvents
{
    /**
    * Ambil data kehadiran bulan ini
    */
    public function collection()
    {
        return Kehadiran::with('user')
            ->whereMonth('tanggal', now()->month)
            ->orderBy('tanggal', 'asc') // Tambahkan urutan agar rapi
            ->get();
    }

    /**
    * Header Tabel Excel
    */
    public function headings(): array
    {
        return [
            'NAMA KARYAWAN',
            'NIK',
            'TANGGAL',
            'JAM MASUK',
            'JAM PULANG',
            'STATUS',
            'KOORDINAT LOKASI',
        ];
    }

    /**
    * Memetakan data ke kolom Excel
    */
    public function map($kehadiran): array
    {
        return [
            $kehadiran->user->name ?? 'N/A',
            $kehadiran->user->nopeg ?? '-', // Sesuaikan jika kolomnya nopeg atau nik
            \Carbon\Carbon::parse($kehadiran->tanggal)->format('d-m-Y'),
            $kehadiran->jam_masuk ?? '--:--',
            $kehadiran->jam_pulang ?? '--:--',
            $kehadiran->status,
            $kehadiran->lokasi_masuk ?? '-',
        ];
    }

    /**
    * Styling Header (Bold & Background Color)
    */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style untuk baris nomor 1 (Header)
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '059669'] // Warna Emerald-600 (sesuai tema UI Anda)
                ],
            ],
        ];
    }

    /**
    * Custom Event untuk mempercantik sheet (Border & Alignment)
    */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $cellRange = 'A1:G' . ($event->sheet->getHighestRow()); // Range kolom A sampai G
                
                // Set alignment center untuk semua data kecuali Nama
                $event->sheet->getDelegate()->getStyle('B1:G1000')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                // Tambahkan border ke seluruh tabel
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);
            },
        ];
    }
}