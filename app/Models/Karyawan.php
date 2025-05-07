<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'karyawan';

    protected $fillable = [
        'id_karyawan',
        'nama_karyawan',
        'ttl',
        'alamat',
        'jabatan',
        'status'
    ];

    // Make sure created_at and updated_at are used
    public $timestamps = true;
}
