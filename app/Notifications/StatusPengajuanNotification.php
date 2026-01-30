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
     * Masukkan instance pengajuan ke dalam constructor.
     */
    public function __construct($pengajuan)
    {
        $this->pengajuan = $pengajuan;
    }

    /**
     * Tentukan channel pengiriman.
     * Menggunakan 'database' agar muncul di tabel notifications.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Data yang akan disimpan ke kolom 'data' di tabel notifications.
     */
    public function toDatabase(object $notifiable): array
    {
        // PERBAIKAN: Menggunakan relasi 'kategori' dan kolom 'nama_kategori' sesuai Model
        $namaKategori = $this->pengajuan->kategori->nama_kategori 
                        ?? $this->pengajuan->jenis_pengajuan 
                        ?? 'Pengajuan';

        return [
            'pengajuan_id' => $this->pengajuan->id,
            'jenis'        => $namaKategori,
            'status'       => $this->pengajuan->status,
            'title'        => 'Pembaruan Status Pengajuan',
            'pesan'        => 'Pengajuan ' . $namaKategori . ' Anda telah ' . strtoupper($this->pengajuan->status) . ' oleh HRD.',
            'url'          => route('pengajuan'), 
        ];
    }

    /**
     * Digunakan jika Anda menggunakan broadcast (Real-time) atau channel lainnya.
     */
    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}