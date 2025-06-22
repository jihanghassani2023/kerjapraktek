<?php
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

    public function perbaikan()
    {
        return $this->belongsTo(Perbaikan::class, 'perbaikan_id', 'id');
    }

    public function getFormattedGaransiAttribute()
    {
        return $this->sparepart . ': ' . $this->periode;
    }

    public function scopeByPeriode($query, $periode)
    {
        return $query->where('periode', $periode);
    }
}
