<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {

        User::firstOrCreate([
            'email' => 'robertchandra@kepalatoko.mgtech',
        ], [
           'name' => 'Robert Chandra',
            'password' => Hash::make('Kepalatoko_mgtech1'),
            'role' => 'kepala_toko',
            'alamat' => 'Jl. Sukabangun',
            'jabatan' => 'Kepala Toko',
        ]);


    }
}
