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
        'nama_pelanggan',
        'nomor_telp',
        'email',
        'harga',
        'garansi',
        'status',
        'user_id'
    ];

    // Make sure created_at and updated_at are used
    public $timestamps = true;

    // Relationship to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}