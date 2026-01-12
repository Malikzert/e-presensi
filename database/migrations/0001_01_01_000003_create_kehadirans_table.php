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
        Schema::create('kehadirans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('shift_id')->constrained('shifts');
            $table->date('tanggal');
            $table->time('jam_masuk')->nullable(); // Nullable jika nanti ada record Alpha otomatis
            $table->time('jam_pulang')->nullable();
            
            // Lokasi GPS
            $table->text('lokasi_masuk')->nullable();
            $table->text('lokasi_pulang')->nullable();
            
            // Validasi Network (IP Address Wi-Fi)
            $table->string('ip_address_masuk')->nullable();
            $table->string('ip_address_pulang')->nullable();
            
            // Kolom Tunggal untuk Status Kehadiran
            // Isinya: Hadir / Terlambat / Alpha / Izin / Sakit
            $table->string('status')->default('Alpha'); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kehadirans');
    }
};
