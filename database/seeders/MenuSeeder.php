<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Stand;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ambil data stand yang SUDAH DIBUAT di UserSeeder berdasarkan namanya
        $standMamah = Stand::where('stand_name', 'Ayam Bakar Si Mamah')->first();
        $standGeprek = Stand::where('stand_name', 'Ayam Geprek Mantap')->first();

        // Jaga-jaga jika seeder dijalankan terbalik atau stand tidak ditemukan
        if (!$standMamah || !$standGeprek) {
            return;
        }

        // 2. Masukkan data Menu lengkap dengan mengikat stand_id yang benar

        // === MENU UNTUK STAND SI MAMAH (Stand ID 1) ===
        Menu::create([
            'stand_id' => $standMamah->id, // 👈 Otomatis ikut ID Si Mamah (User ID: 2)
            'name'     => 'Ayam Bakar Madu',
            'price'    => 18000,
            'status'   => 'available',
            'image'    => 'https://images.unsplash.com/photo-1598515214211-89d3c73ae83b?w=500',
        ]);

        Menu::create([
            'stand_id' => $standMamah->id,
            'name'     => 'Es Teh Manis Jumbo',
            'price'    => 4000,
            'status'   => 'available',
            'image'    => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=500',
        ]);


        // === MENU UNTUK STAND MAS GEPREK (Stand ID 2) ===
        Menu::create([
            'stand_id' => $standGeprek->id, // 👈 Otomatis ikut ID Mas Geprek (User ID: 3)
            'name'     => 'Ayam Geprek Sambal Korek',
            'price'    => 15000,
            'status'   => 'available',
            'image'    => 'https://images.unsplash.com/photo-1626082927389-6cd097cdc6ec?w=500',
        ]);

        Menu::create([
            'stand_id' => $standGeprek->id,
            'name'     => 'Nasi Goreng Kampus',
            'price'    => 13000,
            'status'   => 'available',
            'image'    => 'https://images.unsplash.com/photo-1512058564366-18510be2db19?w=500',
        ]);

        Menu::create([
            'stand_id' => $standGeprek->id,
            'name'     => 'Mie Instan Telur Kornet',
            'price'    => 10000,
            'status'   => 'empty',
            'image'    => 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=500',
        ]);
    }
}