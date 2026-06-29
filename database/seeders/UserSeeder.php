<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Stand;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Testing\Fluent\Concerns\Has;
use PharIo\Manifest\Email;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        //Akun pedagang
        $merchant = User::create([
            'name' => 'Mamah - Ayam Bakar',
            'email' => 'mamah@kantin.com',
            'password' => Hash::make('password123'),
            'role' => 'merchant',
        ]);

        //otomatis buatkan stan buat si mamah karena relasinya udah kita kunci tadi
        Stand::create([
            'user_id' => $merchant->id,
            'stand_name' => 'Ayam Bakar Si Mamah',
            'stand_number' => 'Lapak No. 1',
            'category'     => 'Makanan',
        ]);

        //Mas Geprek (User ID: 3, Stand ID: 2)
        $merchant2 = User::create([
            'name' => 'Mas Geprek',
            'email' => 'geprek@kantin.com',
            'password' => Hash::make('password123'),
            'role' => 'merchant',
        ]);

        Stand::create([
            'user_id' => $merchant2->id,
            'stand_name' => 'Ayam Geprek',
            'stand_number' => 'Lapak No. 2',
            'category'     => 'Minuman',
        ]);

        //Akun Customer (Mahasiswa)
        User::create([
            'name' => 'Ela Herawati',
            'email' => 'ela@mahasiswa.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        {
        // Jika ada seeder User, biarkan saja tetap di atas
        $this->call([
        MenuSeeder::class, // 👈 Daftarkan menumu di sini
        ]);
        }
    }
}
