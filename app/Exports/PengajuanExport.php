<?php

namespace App\Exports;

use App\Models\Pengajuan;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PengajuanExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize, WithStyles, WithEvents
{
    protected $bulan;
    protected $tahun;

    public function __construct($bulan, $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function query()
    {
        return Pengajuan::with(['karyawan', 'kategori'])
            ->whereMonth('tgl_mulai', $this->bulan)
            ->whereYear('tgl_mulai', $this->tahun);
    }

    public function headings(): array
    {
        return [
            ['LAPORAN PENGAJUAN KARYAWAN - AMM'], // Judul Atas
            ['Bulan: ' . $this->bulan . ' | Tahun: ' . $this->tahun], // Sub Judul
            [], // Baris Kosong
            [
                'ID',
                'KODE REQ',
                'NAMA KARYAWAN',
                'JENIS',
                'TGL MULAI',
                'TGL SELESAI',
                'DURASI',
                'ALASAN',
                'STATUS',
            ]
        ];
    }

    public function map($p): array
    {
        $durasi = $p->tgl_mulai->diffInDays($p->tgl_selesai) + 1;

        return [
            $p->id,
            $p->kode_pengajuan,
            $p->karyawan->name ?? 'N/A',
            $p->kategori->nama_pengajuan ?? 'N/A',
            $p->tgl_mulai->format('d/m/Y'),
            $p->tgl_selesai->format('d/m/Y'),
            $durasi . ' Hari',
            $p->alasan,
            strtoupper($p->status),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style untuk Header Tabel (Baris ke-4)
            4 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F81BD']
                ],
            ],
            // Style untuk Judul Besar (Baris ke-1)
            1 => ['font' => ['bold' => true, 'size' => 14]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Merge cell untuk judul
                $event->sheet->mergeCells('A1:I1');
                $event->sheet->mergeCells('A2:I2');
                
                // Rata tengah semua kolom tertentu
                $event->sheet->getStyle('A4:I100')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $event->sheet->getStyle('A4:B100')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('D4:G100')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('I4:I100')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Border untuk seluruh tabel
                $lastRow = $event->sheet->getHighestRow();
                $event->sheet->getStyle('A4:I' . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ]);
            },
        ];
    }
}