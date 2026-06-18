<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Kolom untuk menentukan Cash atau Cashless
            $table->enum('payment_method', ['cash', 'cashless'])->default('cash')->after('status');
            
            // Kolom untuk nama Bank / E-Wallet (Bisa kosong jika pilih Cash)
            $table->string('payment_channel')->nullable()->after('payment_method');
            
            // Kolom untuk status pembayaran (Belum bayar atau Sudah Lunas)
            $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid')->after('payment_channel');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'payment_channel', 'payment_status']);
        });
    }
};