<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // 🟢 Menambahkan kolom stand_id setelah kolom user_id
            $table->foreignId('stand_id')
                  ->nullable()
                  ->after('user_id')
                  ->constrained('stands')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Menghapus foreign key dan kolomnya jika di-rollback
            $table->dropForeign(['stand_id']);
            $table->dropColumn('stand_id');
        });
    }
};