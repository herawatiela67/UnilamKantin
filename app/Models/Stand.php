<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; 
use Illuminate\Database\Eloquent\Relations\BelongsTo; 
class Stand extends Model
{
    // Daftarkan kolom yang boleh diisi (Mass Assignment)
    protected $fillable = [
     'user_id',
     'stand_name', 
     'stand_number',
     'image',
     'description', 
     'category',
     'status'
    
     ];

    /**
     * Hubungan relasi: Satu Stand memiliki banyak Menu
     */
    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class);
    }
}