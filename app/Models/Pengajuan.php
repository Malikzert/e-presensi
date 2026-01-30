<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Pengajuan extends Model
{
    use HasFactory;

    protected $table = 'pengajuans';

    protected $fillable = [
        'karyawan_id',
        'kode_pengajuan',
        'kategori_pengajuan_id',
        'tgl_mulai',
        'tgl_selesai',
        'alasan',
        'bukti',
        'status',
    ];

    protected static function booted()
    {
        static::creating(function ($pengajuan) {
            // Format Kode: AMM-20260130-ABCDE
            $pengajuan->kode_pengajuan = 'AMM-' . date('Ymd') . '-' . strtoupper(Str::random(5));
        });
    }

    protected $casts = [
        'tgl_mulai' => 'date',
        'tgl_selesai' => 'date',
    ];

    /**
     * Relasi ke Karyawan (Sebelumnya User)
     */
    public function karyawan(): BelongsTo
    {
        // Pastikan foreign key merujuk ke karyawan_id
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }

    /**
     * Relasi ke Master Kategori Pengajuan
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriPengajuan::class, 'kategori_pengajuan_id');
    }
}