<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // 👈 Jangan lupa import ini

class Stand extends Model
{
    // Daftarkan kolom yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'name', 
        'owner_name', 
        'status' // misal: 'open' atau 'close'
    ];

    /**
     * Hubungan relasi: Satu Stand memiliki banyak Menu
     */
    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class);
    }
}