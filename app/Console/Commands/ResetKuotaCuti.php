<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Karyawan;

class ResetKuotaCuti extends Command
{
    protected $signature = 'cuti:reset';

    protected $description = 'Reset kuota cuti tahunan semua karyawan menjadi 15 hari';

    public function handle()
    {
        Karyawan::where('is_admin', false)->update(['kuota_cuti' => 15]);

        $this->info('Kuota cuti berhasil direset menjadi 15 hari untuk semua karyawan.');
    }
}