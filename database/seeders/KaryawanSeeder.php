<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Karyawan;

class KaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data karyawan awal
        $karyawan = [
            [
                'id_karyawan' => '100016',
                'nama_karyawan' => 'Bagan Sudarsono',
                'ttl' => 'Palembang, 21 Februari 1989',
                'alamat' => 'Jl. Pengadilan, 15 Ilir, Kec. Ilir Timur, Kota Palembang',
                'jabatan' => 'Kepala Teknisi',
                'status' => 'Kontrak',
            ],
            [
                'id_karyawan' => '100017',
                'nama_karyawan' => 'Tengkuh',
                'ttl' => 'Serang, 15 September 1995',
                'alamat' => 'Jl. Kolonel Atmo No.53A, 17 Ilir, Kec. Ilir Tim. I, Kota Palembang',
                'jabatan' => 'Teknisi',
                'status' => 'Kontrak',
            ],
            [
                'id_karyawan' => '100018',
                'nama_karyawan' => 'Andrean Prasongko',
                'ttl' => 'Pekanbaru, 3 April 2000',
                'alamat' => 'Jl. Putri 7-24, Duku, Kec. Ilir Tim. II, Kota Palembang',
                'jabatan' => 'Admin',
                'status' => 'Kontrak',
            ],
        ];

        // Insert data karyawan
        foreach ($karyawan as $k) {
            Karyawan::create($k);
        }
    }
}