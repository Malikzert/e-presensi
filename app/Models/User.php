<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany; // Tambahkan ini
use App\Notifications\CustomVerifyEmail;

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
        'nopeg',   // Tambahkan ini
        'gender',  // Tambahkan ini
        'password',
        'jabatan_id', // Menggunakan ID Jabatan
        'foto',
        'is_admin',
        'shift_id',
        'notif_pengingat',
        'delete_requested_at',
        'status',
        'notif_status_pengajuan',
        'track_lokasi',
        'kuota_cuti',
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
            'notif_pengingat' => 'boolean',
            'notif_status_pengajuan' => 'boolean',
            'track_lokasi' => 'boolean',
        ];
    }
    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail);
    }
    /**
     * Relasi ke Tabel Jabatan (One-to-Many).
     */
    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class);
    }

    /**
     * Relasi ke Tabel Unit (Many-to-Many / Rangkap).
     */
    public function units(): BelongsToMany
    {
        return $this->belongsToMany(Unit::class, 'unit_user');
    }

    /**
     * Relasi ke Tabel Shift.
     */
    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    /**
     * Relasi ke Tabel Jadwal (One-to-Many).
     * Tambahkan ini untuk plotting jadwal bulanan
     */
    public function jadwals(): HasMany
    {
        return $this->hasMany(Jadwal::class);
    }
}