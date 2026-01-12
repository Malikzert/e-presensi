<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail; // 1. Bagian ini dibuka comment-nya
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// 2. Tambahkan "implements MustVerifyEmail" di akhir baris class
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Atribut yang dapat diisi (Mass Assignable).
     */
    protected $fillable = [
        'name',
        'email',
        'nik',
        'password',
        'departemen_id',
        'jabatan',
        'is_admin',
        'shift_id',
    ];

    /**
     * Atribut yang disembunyikan saat data diubah ke JSON.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting tipe data kolom.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Relasi ke Tabel Departemen.
     */
    public function departemen(): BelongsTo
    {
        return $this->belongsTo(Departemen::class);
    }

    /**
     * Relasi ke Tabel Shift.
     */
    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }
}