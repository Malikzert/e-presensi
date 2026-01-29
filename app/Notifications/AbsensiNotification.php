<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AbsensiNotification extends Notification
{
    use Queueable;

    private $pesan;

    public function __construct($pesan)
    {
        $this->pesan = $pesan;
    }

    public function via($notifiable)
    {
        return ['database']; // Menyimpan ke tabel notifications
    }

    public function toArray($notifiable)
    {
        return [
            'message' => $this->pesan,
            'url' => route('kehadiran'),
        ];
    }
}