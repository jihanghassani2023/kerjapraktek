<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perbaikan extends Model
{
    use HasFactory;

    protected $table = 'perbaikan';

    protected $fillable = [
        'kode_perbaikan',
        'nama_barang',
        'tanggal_perbaikan',
        'masalah',
        'tindakan_perbaikan',
        'harga',
        'garansi',
        'status',
        'user_id',
        'pelanggan_id'
    ];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }
}
