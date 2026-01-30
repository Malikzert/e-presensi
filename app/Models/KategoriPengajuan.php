<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriPengajuan extends Model
{
    use HasFactory;

    protected $table = 'kategori_pengajuans';

    protected $fillable = [
        'nama_pengajuan',
        'slug'
    ];

    /**
     * Relasi balik ke tabel Pengajuan
     */
    public function pengajuans(): HasMany
    {
        return $this->hasMany(Pengajuan::class, 'kategori_pengajuan_id');
    }
}