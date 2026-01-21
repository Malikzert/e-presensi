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
        'user_id',
        'kode_pengajuan',
        'jenis_pengajuan',
        'tgl_mulai',
        'tgl_selesai',
        'alasan',
        'bukti',
        'status',
    ];
    protected static function booted()
    {
        static::creating(function ($pengajuan) {
            // Membuat kode otomatis sebelum data masuk ke DB
            // Format: REQ-20260120-ABCDE
            $pengajuan->kode_pengajuan = 'AMM-' . date('Ymd') . '-' . strtoupper(Str::random(5));
        });
    }
    /**
     * Otomatis mengubah string tanggal menjadi objek Carbon
     */
    protected $casts = [
        'tgl_mulai' => 'date',
        'tgl_selesai' => 'date',
    ];

    /**
     * Relasi ke User (Karyawan)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}