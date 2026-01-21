<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jabatan extends Model
{
    /**
     * Atribut yang dapat diisi (Mass Assignable).
     */
    protected $fillable = [
        'nama_jabatan',
    ];

    /**
     * Relasi ke Tabel User (One-to-Many).
     * Satu jabatan (misal: "Staff Perawat") bisa dimiliki oleh banyak user.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}