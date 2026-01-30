<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Tetap menginduk ke Authenticatable
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Notifications\CustomVerifyEmail;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class Karyawan extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * Nama tabel yang digunakan oleh model ini.
     */
    protected $table = 'karyawans'; 

    /**
     * Atribut yang dapat diisi (Mass Assignable).
     */
    protected $fillable = [
        'name',
        'email',
        'nik',
        'nopeg',
        'gender',
        'password',
        'jabatan_id',
        'foto',
        'is_admin',
        'shift_id',
        'notif_pengingat',
        'delete_requested_at',
        'status',
        'notif_status_pengajuan',
        'track_lokasi',
        'kuota_cuti',
        'google_id', // Pastikan google_id ada jika menggunakan login Google
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'notif_pengingat' => 'boolean',
            'notif_status_pengajuan' => 'boolean',
            'track_lokasi' => 'boolean',
            'delete_requested_at' => 'datetime',
        ];
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail);
    }
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new class($token) extends ResetPassword {
            public function toMail($notifiable)
            {
                return (new MailMessage)
                    ->subject('Pemulihan Kata Sandi - RSU Anna Medika')
                    ->greeting('Halo, ' . $notifiable->name . '!')
                    ->line('Kami menerima permintaan untuk mengatur ulang kata sandi akun Anda di sistem RSU Anna Medika.')
                    ->action('Atur Ulang Kata Sandi', url(config('app.url').route('password.reset', [
                        'token' => $this->token,
                        'email' => $notifiable->getEmailForPasswordReset(),
                    ], false)))
                    ->line('Link pemulihan ini akan kedaluwarsa dalam 60 menit.')
                    ->line('Jika Anda tidak merasa melakukan permintaan ini, abaikan saja email ini.')
                    ->salutation('Salam hangat, IT RSU Anna Medika');
            }
        });
    }

    /**
     * Relasi ke Tabel Jabatan.
     */
    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class);
    }

    /**
     * Relasi ke Tabel Unit melalui tabel pivot 'penempatans'.
     */
    public function units(): BelongsToMany
    {
        return $this->belongsToMany(Unit::class, 'penempatans', 'karyawan_id', 'unit_id');
    }

    /**
     * Relasi ke Tabel Shift.
     */
    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    /**
     * Relasi ke Tabel Jadwal.
     */
    public function jadwals(): HasMany
    {
        // Secara otomatis mencari 'karyawan_id' di tabel jadwals
        return $this->hasMany(Jadwal::class, 'karyawan_id');
    }

    /**
     * Relasi ke Tabel Pengajuan.
     */
    public function pengajuans(): HasMany
    {
        return $this->hasMany(Pengajuan::class, 'karyawan_id');
    }
}