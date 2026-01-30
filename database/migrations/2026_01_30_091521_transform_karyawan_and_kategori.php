<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration {
    public function up(): void
{
    // 1. Ganti nama tabel User menjadi Karyawan (Hanya jika 'users' masih ada)
    if (Schema::hasTable('users')) {
        Schema::rename('users', 'karyawans');
    }

    // 2. Buat tabel Master Kategori Pengajuan (Hanya jika belum ada)
    if (!Schema::hasTable('kategori_pengajuans')) {
        Schema::create('kategori_pengajuans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pengajuan');
            $table->string('slug')->nullable();
            $table->timestamps();
        });

        // Isi Data Awal
        $kategori = ['Cuti', 'Sakit', 'Izin', 'Lembur', 'Tugas Luar'];
        foreach ($kategori as $item) {
            DB::table('kategori_pengajuans')->insert([
                'nama_pengajuan' => $item,
                'slug' => \Illuminate\Support\Str::slug($item),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    // 3. Modifikasi tabel Pengajuans
    Schema::table('pengajuans', function (Blueprint $table) {
        // Cek jika kolom kategori_pengajuan_id belum ada
        if (!Schema::hasColumn('pengajuans', 'kategori_pengajuan_id')) {
            $table->foreignId('kategori_pengajuan_id')
                  ->after('id')
                  ->nullable()
                  ->constrained('kategori_pengajuans')
                  ->onDelete('cascade');
        }

        // Cek jika kolom jenis_pengajuan masih ada sebelum dihapus
        if (Schema::hasColumn('pengajuans', 'jenis_pengajuan')) {
            $table->dropColumn('jenis_pengajuan');
        }
        
        // Cek jika masih menggunakan user_id sebelum diganti
        if (Schema::hasColumn('pengajuans', 'user_id')) {
            $table->renameColumn('user_id', 'karyawan_id');
        }
    });

    // 4. Update tabel relasi lainnya (Lakukan hal yang sama: cek column)
    if (Schema::hasColumn('kehadirans', 'user_id')) {
        Schema::table('kehadirans', function (Blueprint $table) {
            $table->renameColumn('user_id', 'karyawan_id');
        });
    }

    if (Schema::hasColumn('jadwals', 'user_id')) {
        Schema::table('jadwals', function (Blueprint $table) {
            $table->renameColumn('user_id', 'karyawan_id');
        });
    }

    // 5. Tabel Pivot
    if (Schema::hasTable('unit_user')) {
        Schema::rename('unit_user', 'karyawan_unit');
    }
    
    if (Schema::hasTable('karyawan_unit') && Schema::hasColumn('karyawan_unit', 'user_id')) {
        Schema::table('karyawan_unit', function (Blueprint $table) {
            $table->renameColumn('user_id', 'karyawan_id');
        });
    }
}

    public function down(): void
    {
        // Kembalikan ke struktur semula jika di-rollback
        Schema::table('karyawan_unit', function (Blueprint $table) {
            $table->renameColumn('karyawan_id', 'user_id');
        });
        Schema::rename('karyawan_unit', 'unit_user');

        Schema::table('jadwals', function (Blueprint $table) {
            $table->renameColumn('karyawan_id', 'user_id');
        });

        Schema::table('kehadirans', function (Blueprint $table) {
            $table->renameColumn('karyawan_id', 'user_id');
        });

        Schema::table('pengajuans', function (Blueprint $table) {
            $table->string('jenis_pengajuan')->after('kategori_pengajuan_id')->nullable();
            $table->dropConstrainedForeignId('kategori_pengajuan_id');
            $table->renameColumn('karyawan_id', 'user_id');
        });

        Schema::dropIfExists('kategori_pengajuans');
        Schema::rename('karyawans', 'users');
    }
};