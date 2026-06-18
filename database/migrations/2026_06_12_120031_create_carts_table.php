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
    Schema::create('carts', function (Blueprint $table) {
        $table->id();
        // Menghubungkan ke mahasiswa yang sedang login
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        // Menghubungkan ke menu makanan yang dipilih
        $table->foreignId('menu_id')->constrained('menus')->onDelete('cascade');
        // Jumlah porsi makanan
        $table->integer('quantity')->default(1);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
