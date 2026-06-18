<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    // 🚨 DAFTARKAN SEMUA KOLOM YANG BOLEH DIISI OLEH FLUTTER DI SINI!
   protected $fillable = [
        'stand_id',
        'name',
        'price',
        'description', 
        'status',
        'image',       
        'image_url', // 👈 Resmi masuk ke sini!
    ];

    /**
     * Relasi balik ke Stand Kantin
     */
    public function stand()
    {
        return $this->belongsTo(Stand::class, 'stand_id');
    }
}