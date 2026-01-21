<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menghapus index unique. 
            // Nama index default Laravel biasanya adalah 'users_nama_kolom_unique'
            $table->dropUnique(['email']);
            $table->dropUnique(['nik']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Mengembalikan status unique jika di-rollback
            $table->unique('email');
            $table->unique('nik');
        });
    }
};