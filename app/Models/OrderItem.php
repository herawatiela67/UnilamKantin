<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'menu_id',
        'quantity',
        'price',
    ];

    // Relasi balik ke Menu Makanan
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    // Relasi balik ke Order Utama
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}