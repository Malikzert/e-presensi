<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengajuan extends Model
{
    use HasFactory;

    protected $table = 'pengajuans';

    protected $fillable = [
        'user_id',
        'jenis_pengajuan',
        'tgl_mulai',
        'tgl_selesai',
        'alasan',
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