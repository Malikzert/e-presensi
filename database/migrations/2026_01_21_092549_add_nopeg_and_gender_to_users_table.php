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
        Schema::table('users', function (Blueprint $table) {
            // Nopeg biasanya unik dan tidak boleh kosong
            $table->string('nopeg')->unique()->after('nik')->nullable();
            // Gender menggunakan enum untuk validasi data yang konsisten
            $table->enum('gender', ['Laki-laki', 'Perempuan'])->after('nopeg')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nopeg', 'gender']);
        });
    }
};
