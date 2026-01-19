<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kehadiran extends Model
{
    use HasFactory;

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
        'status'
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}