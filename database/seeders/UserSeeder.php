<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@mgtech.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Create Kepala Toko User
        User::create([
            'name' => 'Kepala Toko',
            'email' => 'kepala@mgtech.com',
            'password' => Hash::make('kepala123'),
            'role' => 'kepala_toko',
        ]);

        // Create Teknisi User
        User::create([
            'name' => 'Teknisi',
            'email' => 'teknisi@mgtech.com',
            'password' => Hash::make('teknisi123'),
            'role' => 'teknisi',
        ]);

        // Create Regular User (will not have access)
        User::create([
            'name' => 'Regular User',
            'email' => 'user@mgtech.com',
            'password' => Hash::make('user123'),
            'role' => 'user',
        ]);
    }
}
