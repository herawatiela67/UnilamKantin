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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            
            // Menghubungkan ke tabel stands, menu wajib punya stan!
            $table->foreignId('stand_id')->constrained()->onDelete('cascade');
            
            $table->string('name');
            $table->integer('price');
            $table->text('description')->nullable();       
            $table->string('status')->default('available'); 
            $table->integer('stock')->default(0); 
            $table->string('image')->nullable(); 
            $table->string('image_url')->nullable();             
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};