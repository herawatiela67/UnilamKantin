<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Stand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Akun Pedagang 1 (Pakai firstOrCreate)
        $merchant = User::firstOrCreate(
            ['email' => 'mamah@kantin.com'],
            [
                'name' => 'Mamah - Ayam Bakar',
                'password' => Hash::make('password123'),
                'role' => 'merchant',
            ]
        );

        // Otomatis buatkan stan jika stannya belum ada
        Stand::firstOrCreate(
            ['user_id' => $merchant->id],
            [
                'stand_name' => 'Ayam Bakar Si Mamah',
                'stand_number' => 'Lapak No. 1',
                'category'     => 'Makanan',
            ]
        );

        // 2. Akun Pedagang 2 (Pakai firstOrCreate)
        $merchant2 = User::firstOrCreate(
            ['email' => 'geprek@kantin.com'],
            [
                'name' => 'Mas Geprek',
                'password' => Hash::make('password123'),
                'role' => 'merchant',
            ]
        );

        Stand::firstOrCreate(
            ['user_id' => $merchant2->id],
            [
                'stand_name' => 'Ayam Geprek',
                'stand_number' => 'Lapak No. 2',
                'category'     => 'Minuman',
            ]
        );

        // 3. Akun Admin Ela Herawati (Pakai firstOrCreate)
        User::firstOrCreate(
            ['email' => 'herawtiela67@gmail.com'],
            [
                'name' => 'Ela Herawati',
                'password' => Hash::make('Elahera67@'),
                'role' => 'admin',
            ]
        );  

        // 4. Memanggil Seeder Lain (MenuSeeder)
        $this->call([
            MenuSeeder::class,
        ]);
    }
}