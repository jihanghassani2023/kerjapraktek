<?php
// app/Models/Garansi.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Garansi extends Model
{
    use HasFactory;

    protected $table = 'garansi';

    protected $fillable = [
        'perbaikan_id',
        'sparepart',
        'periode'
    ];

    // Relasi ke Perbaikan
    public function perbaikan()
    {
        return $this->belongsTo(Perbaikan::class, 'perbaikan_id', 'id');
    }

    // Accessor untuk format garansi
    public function getFormattedGaransiAttribute()
    {
        return $this->sparepart . ': ' . $this->periode;
    }

    // Scope untuk periode tertentu
    public function scopeByPeriode($query, $periode)
    {
        return $query->where('periode', $periode);
    }
}
