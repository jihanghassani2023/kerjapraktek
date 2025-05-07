<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Perbaikan;
use App\Models\User;

class PerbaikanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get teknisi user
        $teknisi = User::where('role', 'teknisi')->first();
        
        if (!$teknisi) {
            // Create teknisi user if doesn't exist
            return;
        }
        
        // Sample perbaikan data
        $perbaikan = [
            [
                'kode_perbaikan' => 'PB00012',
                'nama_barang' => 'iPhone 16 Plus',
                'tanggal_perbaikan' => '2025-04-15',
                'masalah' => 'Speaker',
                'nama_pelanggan' => 'Andi Wijaya',
                'nomor_telp' => '081234567890',
                'email' => 'andi@example.com',
                'harga' => 350000,
                'garansi' => '1 bulan',
                'status' => 'Selesai',
                'user_id' => $teknisi->id,
            ],
            [
                'kode_perbaikan' => 'PB00018',
                'nama_barang' => 'iPhone 13 Mini',
                'tanggal_perbaikan' => '2025-05-07',
                'masalah' => 'LCD',
                'nama_pelanggan' => 'Budi Santoso',
                'nomor_telp' => '08987654321',
                'email' => 'budi@example.com',
                'harga' => 750000,
                'garansi' => '3 bulan',
                'status' => 'Selesai',
                'user_id' => $teknisi->id,
            ],
            [
                'kode_perbaikan' => 'PB00023',
                'nama_barang' => 'MacBook',
                'tanggal_perbaikan' => '2025-05-14',
                'masalah' => 'Keyboard',
                'nama_pelanggan' => 'Cindy Paramita',
                'nomor_telp' => '087712345678',
                'email' => 'cindy@example.com',
                'harga' => 500000,
                'garansi' => '2 bulan',
                'status' => 'Selesai',
                'user_id' => $teknisi->id,
            ],
            [
                'kode_perbaikan' => 'PB00024',
                'nama_barang' => 'iPhone 15 Pro',
                'tanggal_perbaikan' => '2025-05-01',
                'masalah' => 'GreenScreen LCD',
                'nama_pelanggan' => 'Denny Sumargo',
                'nomor_telp' => '082145678901',
                'email' => 'denny@example.com',
                'harga' => 1200000,
                'garansi' => '3 bulan',
                'status' => 'Proses',
                'user_id' => $teknisi->id,
            ],
        ];
        
        // Insert data
        foreach ($perbaikan as $p) {
            Perbaikan::create($p);
        }
    }
}