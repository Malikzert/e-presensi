<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Ganti nama tabel User menjadi Karyawan
        Schema::rename('users', 'karyawans');

        // 2. Buat tabel Master Kategori Pengajuan
        Schema::create('kategori_pengajuans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pengajuan'); // Contoh: Cuti, Sakit, Izin, Lembur
            $table->string('slug')->nullable();
            $table->timestamps();
        });

        // 3. Modifikasi tabel Pengajuans
        Schema::table('pengajuans', function (Blueprint $table) {
            // Tambah relasi ke kategori_pengajuan
            $table->foreignId('kategori_pengajuan_id')
                  ->after('id')
                  ->nullable()
                  ->constrained('kategori_pengajuans')
                  ->onDelete('cascade');

            // --- PENGHAPUSAN KOLOM LAMA ---
            $table->dropColumn('jenis_pengajuan'); 
            
            // Ganti nama user_id menjadi karyawan_id
            $table->renameColumn('user_id', 'karyawan_id');
        });

        // 4. Update tabel relasi lainnya (karyawan_id)
        Schema::table('kehadirans', function (Blueprint $table) {
            $table->renameColumn('user_id', 'karyawan_id');
        });

        Schema::table('jadwals', function (Blueprint $table) {
            $table->renameColumn('user_id', 'karyawan_id');
        });

        // 5. Tabel Pivot
        Schema::rename('unit_user', 'karyawan_unit');
        Schema::table('karyawan_unit', function (Blueprint $table) {
            $table->renameColumn('user_id', 'karyawan_id');
        });
    }

    public function down(): void
    {
        // Kembalikan kolom jenis_pengajuan jika rollback
        Schema::table('pengajuans', function (Blueprint $table) {
            $table->string('jenis_pengajuan')->after('kategori_pengajuan_id');
            $table->dropConstrainedForeignId('kategori_pengajuan_id');
            $table->renameColumn('karyawan_id', 'user_id');
        });

        Schema::table('kehadirans', function (Blueprint $table) {
            $table->renameColumn('karyawan_id', 'user_id');
        });

        Schema::table('jadwals', function (Blueprint $table) {
            $table->renameColumn('karyawan_id', 'user_id');
        });

        Schema::table('karyawan_unit', function (Blueprint $table) {
            $table->renameColumn('karyawan_id', 'user_id');
        });
        Schema::rename('karyawan_unit', 'unit_user');

        Schema::dropIfExists('kategori_pengajuans');
        Schema::rename('karyawans', 'users');
    }
};