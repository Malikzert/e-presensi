<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    // Menentukan kolom mana saja yang boleh diisi secara massal
    protected $fillable = [
        'user_id',
        'shift_id',
        'tanggal',
        'keterangan'
    ];

    /**
     * Relasi ke Model User (Karyawan)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Model Shift
     */
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
}