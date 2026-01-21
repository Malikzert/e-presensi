<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('users', function (Blueprint $table) {
        // 1. Putus hubungan foreign key terlebih dahulu
        // Biasanya formatnya: nama_tabel_nama_kolom_foreign
        if (Schema::hasColumn('users', 'departemen_id')) {
            $table->dropForeign(['departemen_id']); // Cara simple
            $table->dropColumn('departemen_id');    // Baru hapus kolomnya
        }

        // 2. Hapus kolom jabatan lama (string)
        if (Schema::hasColumn('users', 'jabatan')) {
            $table->dropColumn('jabatan');
        }

        // 3. Tambahkan relasi Jabatan (One-to-Many)
        $table->foreignId('jabatan_id')->after('email')->nullable()->constrained('jabatans')->onDelete('set null');
    });

    // 4. Buat Tabel Pivot untuk Unit (Many-to-Many / Rangkap)
    Schema::create('unit_user', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('unit_id')->constrained()->onDelete('cascade');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
