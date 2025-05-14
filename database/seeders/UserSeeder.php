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

        // Tambahkan data untuk kepala toko
        User::firstOrCreate([
            'email' => 'robertchandra@kepalatoko.mgtech',
        ], [
           'name' => 'Robert Chandra',
            'password' => Hash::make('Kepalatoko_mgtech1'),
            'role' => 'kepala_toko',
            'id_karyawan' => '100002',
            'alamat' => 'Jl. Kepala Toko No. 456, Palembang',
            'jabatan' => 'Kepala Toko',
        ]);


    }
}
