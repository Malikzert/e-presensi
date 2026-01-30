<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    // Menentukan kolom mana saja yang boleh diisi secara massal
    protected $fillable = [
        'karyawan_id',
        'shift_id',
        'tanggal',
        'keterangan'
    ];

    /**
     * Relasi ke Model Karyawan (Karyawan)
     */
    public function Karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    /**
     * Relasi ke Model Shift
     */
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
}