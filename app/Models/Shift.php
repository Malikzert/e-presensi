<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Tambahkan import ini

class Shift extends Model
{
    use HasFactory;

    protected $guarded = []; // Sudah ada

    /**
     * Relasi ke Tabel Jadwal (One-to-Many).
     * Satu shift bisa digunakan di banyak baris jadwal karyawan.
     */
    public function jadwals(): HasMany
    {
        return $this->hasMany(Jadwal::class);
    }
}