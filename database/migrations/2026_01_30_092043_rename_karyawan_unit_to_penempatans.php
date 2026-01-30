<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Cek jika tabel lama 'karyawan_unit' ada, maka rename ke 'penempatans'
        if (Schema::hasTable('karyawan_unit')) {
            Schema::rename('karyawan_unit', 'penempatans');
        } 
        // Jika ternyata migrasi sebelumnya gagal dan masih bernama 'unit_user'
        elseif (Schema::hasTable('unit_user')) {
            Schema::rename('unit_user', 'penempatans');
        }

        // Memastikan kolom di dalam penempatans sudah menggunakan karyawan_id
        if (Schema::hasTable('penempatans')) {
            Schema::table('penempatans', function (Blueprint $table) {
                if (Schema::hasColumn('penempatans', 'user_id')) {
                    $table->renameColumn('user_id', 'karyawan_id');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('penempatans')) {
            Schema::rename('penempatans', 'karyawan_unit');
        }
    }
};