<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kehadiran extends Model
{
    use HasFactory;

    // Nama tabel secara manual karena kita tidak menggunakan jamak bahasa Inggris
    protected $table = 'kehadirans';

    protected $fillable = [
        'user_id',
        'shift_id',
        'tanggal',
        'jam_masuk',
        'jam_pulang',
        'lokasi_masuk',
        'lokasi_pulang',
        'ip_address_masuk',
        'ip_address_pulang',
        'status',
    ];

    /**
     * Relasi ke User (Karyawan)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}