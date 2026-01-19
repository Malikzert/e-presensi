<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class ResetKuotaCuti extends Command
{
    // Nama perintah yang akan dipanggil
    protected $signature = 'cuti:reset';

    // Deskripsi perintah
    protected $description = 'Reset kuota cuti tahunan semua karyawan menjadi 15 hari';

    public function handle()
    {
        // Update semua user yang bukan admin
        User::where('is_admin', false)->update(['kuota_cuti' => 15]);

        $this->info('Kuota cuti berhasil direset menjadi 15 hari untuk semua karyawan.');
    }
}