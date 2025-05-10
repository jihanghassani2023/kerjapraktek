<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggan';

    protected $fillable = [
        'nama_pelanggan',
        'nomor_telp',
        'email'
    ];

    // Relasi one-to-many dengan Perbaikan
    public function perbaikan()
    {
        return $this->hasMany(Perbaikan::class);
    }
}
