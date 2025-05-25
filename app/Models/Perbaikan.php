<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perbaikan extends Model
{
    use HasFactory;

    protected $table = 'perbaikan';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
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
    protected static function boot()
{
    parent::boot();

    static::creating(function ($perbaikan) {
        if (empty($perbaikan->id)) {
            $perbaikan->id = self::generateKodePerbaikan();
        }
    });
}

public static function generateKodePerbaikan()
{
    $lastPerbaikan = self::where('id', 'LIKE', 'MG%')
        ->orderBy('id', 'desc')
        ->first();

    if ($lastPerbaikan) {
        $lastNumber = (int) substr($lastPerbaikan->id, 2);
        $nextNumber = $lastNumber + 1;
    } else {
        $nextNumber = 50001; // Mulai dari 50001
    }

    return 'MG' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
}

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
