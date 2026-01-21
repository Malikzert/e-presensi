<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Unit extends Model
{
    /**
     * Atribut yang dapat diisi (Mass Assignable).
     */
    protected $fillable = [
        'kode_unit',
        'nama_unit',
    ];

    /**
     * Relasi ke Tabel User (Many-to-Many).
     * Satu unit bisa dimiliki oleh banyak user (perawat/dokter rangkap).
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'unit_user');
    }
}