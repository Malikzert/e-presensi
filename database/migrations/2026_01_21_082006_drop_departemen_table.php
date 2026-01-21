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
    // 1. Matikan pengecekan foreign key sementara agar tidak error saat hapus
    Schema::disableForeignKeyConstraints();

    // 2. Hapus tabelnya
    Schema::dropIfExists('departemens');

    // 3. Hidupkan kembali pengecekan foreign key
    Schema::enableForeignKeyConstraints();
}

public function down()
{
    // Opsional: Jika ingin rollback, buat kembali tabelnya
    Schema::create('departemens', function (Blueprint $table) {
        $table->id();
        $table->string('nama_departemen');
        $table->timestamps();
    });
}
};
