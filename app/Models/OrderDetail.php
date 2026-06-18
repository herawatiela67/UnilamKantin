<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetail extends Model
{
    protected $fillable = [
        'order_id',
        'menu_id',
        'quantity',
        'price',//harga saat dibeli (untuk catatan jika suatu saat harga menu naik)
        'payment_method', 
        'payment_channel', 
        'payment_status' ,
    ];

    //terhubung ke nota utamanya
    public function order() : BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    //terhubung ke menu makanan yang di beli
    public function menu() : BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }
}
