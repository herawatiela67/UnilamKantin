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
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');     
            $table->string('stand_name');
            $table->string('stand_number');
            $table->string('image')->nullable();
            $table->text('description')->nullable()->comment('Deskripsi singkat mengenai stan jualan');
            $table->string('category', ['makanan', 'cemilan', 'minuman'])->default('makanan');
            $table->boolean('status')->default(true); 
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