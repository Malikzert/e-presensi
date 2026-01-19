<?php

namespace App\Exports;

use App\Models\Pengajuan;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PengajuanExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize
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
        return Pengajuan::with('user')
            ->whereMonth('tgl_mulai', $this->bulan)
            ->whereYear('tgl_mulai', $this->tahun);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Karyawan',
            'Jenis Pengajuan',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Durasi (Hari)',
            'Alasan',
            'Status',
        ];
    }

    public function map($p): array
    {
        $durasi = $p->tgl_mulai->diffInDays($p->tgl_selesai) + 1;
        return [
            $p->id,
            $p->user->name,
            $p->jenis_pengajuan,
            $p->tgl_mulai->format('d-m-Y'),
            $p->tgl_selesai->format('d-m-Y'),
            $durasi,
            $p->alasan,
            $p->status,
        ];
    }
}