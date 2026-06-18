<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    // Daftarkan kolom yang boleh diisi massal
    protected $fillable = ['user_id', 'menu_id', 'quantity'];

    /**
     * Relasi balik ke Model Menu (Satu item keranjang memiliki satu Menu)
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    /**
     * Relasi balik ke Model User (Keranjang ini milik siapa)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}