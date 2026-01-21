<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up()
{
    // Cek apakah kolom sudah ada di tabel pengajuans
    if (!Schema::hasColumn('pengajuans', 'kode_pengajuan')) {
        Schema::table('pengajuans', function (Blueprint $table) {
            $table->string('kode_pengajuan')->after('id');
        });
    }
}

    public function down(): void
    {
        Schema::table('pengajuans', function (Blueprint $table) {
            $table->dropColumn('kode_pengajuan');
        });
    }
};