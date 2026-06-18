<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke ID Pesanan Utama
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            // Menghubungkan ke ID Menu Makanan/Minuman
            $table->foreignId('menu_id')->constrained('menus')->onDelete('cascade');
            $table->integer('quantity'); // Jumlah yang dibeli
            $table->integer('price');    // Harga saat dibeli (untuk history jika harga menu berubah)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};