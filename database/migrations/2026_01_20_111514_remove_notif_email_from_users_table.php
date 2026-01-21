<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $blueprint) {
            // Menghapus kolom notif_email
            $blueprint->dropColumn('notif_email');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $blueprint) {
            // Jika suatu saat ingin dikembalikan (rollback)
            $blueprint->boolean('notif_email')->default(false)->after('shift_id');
        });
    }
};