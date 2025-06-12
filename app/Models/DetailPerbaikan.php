<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPerbaikan extends Model
{
    use HasFactory;

    protected $table = 'detail_perbaikan';

    protected $fillable = [
        'perbaikan_id',
        'nama_device',
        'kategori_device',
        'masalah',
        'tindakan_perbaikan',
        'proses_pengerjaan',
        'harga',
        'garansi'
    ];

    protected $casts = [
        'proses_pengerjaan' => 'array',
        'harga' => 'decimal:2'
    ];

    // Relasi ke Perbaikan
    public function perbaikan()
    {
        return $this->belongsTo(Perbaikan::class, 'perbaikan_id', 'id');
    }

    // Helper method untuk menambah proses pengerjaan
    public function addProsesStep($step)
    {
        $currentProcess = $this->proses_pengerjaan ?? [];
        $currentProcess[] = [
            'step' => $step,
            'timestamp' => now()->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s')
        ];

        $this->update(['proses_pengerjaan' => $currentProcess]);
        return $this;
    }

    // Accessor untuk format harga
    public function getFormattedHargaAttribute()
    {
        return 'Rp. ' . number_format($this->harga, 0, ',', '.');
    }

    // Scope untuk filter berdasarkan kategori device
    public function scopeByKategori($query, $kategori)
    {
        return $query->where('kategori_device', $kategori);
    }

    // Scope untuk filter berdasarkan range harga
    public function scopeByHargaRange($query, $min, $max)
    {
        return $query->whereBetween('harga', [$min, $max]);
    }
}
