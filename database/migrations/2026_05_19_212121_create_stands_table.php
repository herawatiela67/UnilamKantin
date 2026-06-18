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
        Schema::create('stands', function (Blueprint $table) {
            $table->id();
            //menghubungkan ke tabel users(pedagang)
            //jika user dihapus maka data stan otomatis terhapus(onDelete cascade)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('stand_name');
            $table->string('stand_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stands');
    }
};
