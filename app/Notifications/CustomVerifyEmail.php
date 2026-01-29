<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class CustomVerifyEmail extends Notification
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Mengirim email menggunakan template custom di resources/views/emails/verify.blade.php
     */
    public function toMail($notifiable)
    {
        // 1. Generate URL verifikasi yang aman
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60), // Link berlaku 60 menit
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );

        // 2. Kirim data ke view emails.verify
        return (new MailMessage)
            ->subject('Aktivasi Akun e-Presensi - RSU Anna Medika')
            ->view('emails.verify', [
                'url'  => $verificationUrl,
                'name' => $notifiable->name,
                'nik'  => $notifiable->nik,
            ]);
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}