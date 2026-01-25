<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel users (Karyawan)
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade'); // Jika user dihapus, jadwal ikut terhapus
            
            // Relasi ke tabel shifts (Master Shift)
            $table->foreignId('shift_id')
                  ->constrained('shifts')
                  ->onDelete('restrict'); // Shift tidak bisa dihapus jika masih ada jadwal
            
            // Kolom Tanggal untuk plotting bulanan
            $table->date('tanggal'); 
            
            // Kolom tambahan untuk catatan (misal: "Tukar Shift")
            $table->string('keterangan')->nullable();

            $table->timestamps();

            /** * PENTING: Index Unique
             * Mencegah satu karyawan memiliki 2 jadwal berbeda di tanggal yang sama.
             * Ini menjaga integritas data agar sinkronisasi absen tidak error.
             */
            $table->unique(['user_id', 'tanggal']);
            
            // Index untuk mempercepat pencarian laporan bulanan
            $table->index('tanggal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwals');
    }
};