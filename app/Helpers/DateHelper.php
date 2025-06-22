<?php

namespace App\Helpers;

class DateHelper
{
    public static function formatTanggalIndonesia($date)
    {
        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $timestamp = strtotime($date);
        $tanggal = date('j', $timestamp);
        $bulan_nama = $bulan[date('n', $timestamp)];
        $tahun = date('Y', $timestamp);

        return $tanggal . ' ' . $bulan_nama . ' ' . $tahun;
    }
    public static function namaBulan($index)
    {
        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return $bulan[(int)$index] ?? 'Bulan Tidak Diketahui';
    }
}
