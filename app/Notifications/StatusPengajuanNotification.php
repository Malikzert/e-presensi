<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StatusPengajuanNotification extends Notification
{
    use Queueable;

    protected $pengajuan;

    /**
     * Kita masukkan data pengajuan ke dalam constructor
     */
    public function __construct($pengajuan)
    {
        $this->pengajuan = $pengajuan;
    }

    /**
     * Ubah channel menjadi 'database'. 
     * Anda bisa menambah 'mail' jika ingin kirim email juga.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Data yang akan disimpan ke tabel 'notifications' kolom 'data'
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'pengajuan_id' => $this->pengajuan->id,
            'jenis' => $this->pengajuan->jenis_pengajuan,
            'status' => $this->pengajuan->status,
            'pesan' => 'Pengajuan ' . $this->pengajuan->jenis_pengajuan . ' Anda telah ' . $this->pengajuan->status,
        ];
    }

    /**
     * Jika suatu saat Anda butuh format array umum (biasanya untuk broadcast/database)
     */
    public function toArray(object $notifiable): array
    {
        return [
            'pengajuan_id' => $this->pengajuan->id,
            'jenis' => $this->pengajuan->jenis_pengajuan,
            'status' => $this->pengajuan->status,
            'pesan' => 'Pengajuan ' . $this->pengajuan->jenis_pengajuan . ' Anda telah ' . $this->pengajuan->status,
        ];
    }
}