<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perbaikan extends Model
{
    use HasFactory;

    protected $table = 'perbaikan';

    protected $fillable = [
        'nama_device',
        'kategori_device',
        'tanggal_perbaikan',
        'masalah',
        'tindakan_perbaikan',
        'proses_pengerjaan',
        'harga',
        'garansi',
        'status',
        'user_id',
        'pelanggan_id'
    ];

    protected $casts = [
        'proses_pengerjaan' => 'array',
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

    public function addProsesStep($step)
    {
        $currentProcess = $this->proses_pengerjaan ?? [];
        $currentProcess[] = [
            'step' => $step,
            'timestamp' => now()->format('Y-m-d H:i:s')
        ];

        $this->proses_pengerjaan = $currentProcess;
        $this->save();

        return $this;
    }
}
