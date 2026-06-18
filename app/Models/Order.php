<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'stand_id',
        'total_price',
        'payment_method',
        'payment_channel',
        'status',
    ];

    // 🟢 1. DEFINISIKAN RELASI KE USER (PEMBELI)
    public function user() {
    return $this->belongsTo(User::class, 'user_id'); 
}

    // 🟢 2. SEKALIGUS DEFINISIKAN RELASI KE DETAIL ORDER (BIAR TIDAK EROR LAGI)
    public function orderDetails()
    {
        // Hubungan: 1 Order memiliki banyak item di tabel order_details
        return $this->hasMany(OrderDetail::class, 'order_id');
    }

    // Tambahkan ini di dalam class Order di file app/Models/Order.php
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function stand(): BelongsTo
    {
        // Parameter kedua adalah nama kolom foreign key di tabel orders kamu (misal: stand_id)
        return $this->belongsTo(Stand::class, 'stand_id');
    }
}