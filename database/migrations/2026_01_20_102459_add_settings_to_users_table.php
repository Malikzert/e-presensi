<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Kolom baru untuk pengaturan
            $table->boolean('notif_email')->default(true);
            $table->boolean('notif_pengingat')->default(true);
            $table->boolean('notif_status_pengajuan')->default(true);
            $table->boolean('track_lokasi')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menghapus kolom jika migration di-rollback
            $table->dropColumn(['notif_email', 'notif_pengingat', 'notif_status_pengajuan', 'track_lokasi']);
        });
    }
};