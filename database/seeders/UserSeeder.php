<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Tambahkan data untuk admin
        User::firstOrCreate([
            'email' => 'andreasprasongko@admin.mgtech',
        ], [
            'name' => 'Admin MGTech',
            'password' => Hash::make('Admin_mgtech1'), // Pastikan password ter-hash
            'role' => 'admin',
        ]);

        // Tambahkan data untuk kepala toko
        User::firstOrCreate([
            'email' => 'robertchandra@kepalatoko.mgtech',
        ], [
            'name' => 'Robert Chandra',
            'password' => Hash::make('Kepalatoko_mgtech1'),
            'role' => 'kepala_toko',
        ]);

        // Tambahkan data untuk teknisi
        User::firstOrCreate([
            'email' => 'tengkuh@teknisi.mgtech',
        ], [
            'name' => 'Tengkuh',
            'password' => Hash::make('Teknisi_mgtech1'),
            'role' => 'teknisi',
        ]);
    }
}