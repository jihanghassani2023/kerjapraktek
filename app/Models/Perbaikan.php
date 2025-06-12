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
        'tanggal_perbaikan',
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

    public $timestamps = true;

    // Relasi ke User (teknisi)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Pelanggan
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    // Relasi ke Detail Perbaikan (One-to-One)
    public function detail()
    {
        return $this->hasOne(DetailPerbaikan::class, 'perbaikan_id', 'id');
    }

    // Accessor untuk data detail (untuk backward compatibility)
    public function getNamaDeviceAttribute()
    {
        return $this->detail ? $this->detail->nama_device : null;
    }

    public function getKategoriDeviceAttribute()
    {
        return $this->detail ? $this->detail->kategori_device : null;
    }

    public function getMasalahAttribute()
    {
        return $this->detail ? $this->detail->masalah : null;
    }

    public function getTindakanPerbaikanAttribute()
    {
        return $this->detail ? $this->detail->tindakan_perbaikan : null;
    }

    public function getHargaAttribute()
    {
        return $this->detail ? $this->detail->harga : 0;
    }

    public function getGaransiAttribute()
    {
        return $this->detail ? $this->detail->garansi : null;
    }

    public function getProsesPengerjaanAttribute()
    {
        return $this->detail ? $this->detail->proses_pengerjaan : [];
    }

    // Method untuk menambah proses step
    public function addProsesStep($step)
    {
        if ($this->detail) {
            $currentProcess = $this->detail->proses_pengerjaan ?? [];
            $currentProcess[] = [
                'step' => $step,
                'timestamp' => now()->format('Y-m-d H:i:s')
            ];

            $this->detail->update(['proses_pengerjaan' => $currentProcess]);
        }

        return $this;
    }
}
