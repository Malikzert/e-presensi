<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Menambahkan data shift baru
        DB::table('shifts')->insert([
            [
                'nama_shift' => 'Middle',
                'jam_masuk' => '11:00:00',
                'jam_pulang' => '18:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_shift' => 'Libur',
                'jam_masuk' => '00:00:00', // Jam masuk/pulang diisi 00:00 untuk libur
                'jam_pulang' => '00:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Menghapus data jika migration di-rollback
        DB::table('shifts')->whereIn('nama_shift', ['Middle', 'Libur'])->delete();
    }
};