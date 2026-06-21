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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            
            // 👤 Menghubungkan ke tabel user (Mahasiswa yang membeli)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // 🏪 Menghubungkan ke tabel stand (Stan pedagang pemilik menu)
            // Menggunakan nullable() atau langsung constrained tergantung relasi tabel stand kamu
            $table->foreignId('stand_id')->nullable()->constrained('stands')->onDelete('set null');
            
            $table->integer('total_price');
            
            // 🕒 Status alur makanan: pending, cooking, ready, completed, cancelled
            $table->string('status')->default('pending');
            
            // 💳 Opsi metode pembayaran utama (Tunai atau Digital)
            $table->enum('payment_method', ['cash', 'cashless'])->default('cash');
            
            // 📱 Nama dompet digital / bank yang dipilih (dana, ovo, bca, mandiri)
            $table->string('payment_channel')->nullable();
            
            // 💰 Status kelunasan uang: unpaid, paid, expired, failed
            $table->enum('payment_status', ['unpaid', 'paid', 'expired', 'failed'])->default('unpaid');
            
            // 🔑 Token transaksi Midtrans untuk fitur pembayaran cashless
            $table->string('snap_token')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};