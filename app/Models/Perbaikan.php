<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
        'pelanggan_id',
        'nama_device',
        'kategori_device',
        'masalah',
        'tindakan_perbaikan',
        'harga'
    ];

    protected $casts = [
        'harga' => 'decimal:2'
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
        try {
            return \Illuminate\Support\Facades\DB::transaction(function () {
                $datePrefix = now()->format('dmy');
                $todayPrefix = 'MG' . $datePrefix;

                $lastPerbaikan = self::where('id', 'LIKE', $todayPrefix . '%')
                    ->orderBy('id', 'desc')
                    ->lockForUpdate()
                    ->first();

                if ($lastPerbaikan) {
                    $lastNumber = (int) substr($lastPerbaikan->id, -3);
                    $nextNumber = $lastNumber + 1;

                    if ($nextNumber > 999) {
                        throw new \Exception('Maksimal 999 perbaikan per hari tercapai');
                    }
                } else {
                    $nextNumber = 1;
                }

                $newId = $todayPrefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

                $exists = self::where('id', $newId)->exists();
                if ($exists) {
                    $nextNumber++;
                    if ($nextNumber > 999) {
                        throw new \Exception('Maksimal 999 perbaikan per hari tercapai');
                    }
                    $newId = $todayPrefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
                }

                return $newId;
            });
        } catch (\Exception $e) {
            $timestamp = now()->format('dmy') . now()->format('His');
            return 'MG' . substr($timestamp, 0, 9);
        }
    }

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function garansi()
    {
        return $this->hasMany(Garansi::class, 'perbaikan_id', 'id');
    }

    public function details()
    {
        return $this->hasMany(DetailPerbaikan::class, 'perbaikan_id', 'id');
    }

    public function detail()
    {
        return $this->hasOne(DetailPerbaikan::class, 'perbaikan_id', 'id')->latest();
    }

    public function prosesPengerjaan()
    {
        return $this->hasMany(DetailPerbaikan::class, 'perbaikan_id', 'id')
            ->select(['perbaikan_id', 'process_step', 'created_at'])
            ->whereNotNull('process_step')
            ->where('process_step', '!=', 'Data perbaikan diperbarui')
            ->orderBy('created_at', 'desc');
    }

    public function garansiItems()
    {
        return $this->garansi()->orderBy('sparepart', 'asc');
    }


    public function getCurrentDetail()
    {
        return $this;
    }

    public function getCurrentGaransiItems()
    {
        return $this->garansi;
    }

    public function hasGaransiChanged($newGaransiItems)
    {
        $currentGaransi = $this->getCurrentGaransiItems();

        $currentFormatted = $currentGaransi->map(function ($item) {
            return [
                'sparepart' => $item->sparepart,
                'periode' => $item->periode
            ];
        })->sortBy('sparepart')->values()->toArray();

        $newFormatted = collect($newGaransiItems)
            ->map(function ($item) {
                return [
                    'sparepart' => trim($item['sparepart'] ?? ''),
                    'periode' => trim($item['periode'] ?? '')
                ];
            })
            ->filter(function ($item) {
                return !empty($item['sparepart']) && !empty($item['periode']);
            })
            ->sortBy('sparepart')
            ->values()
            ->toArray();

        return $currentFormatted !== $newFormatted;
    }


    public function getDetailAttribute()
    {
        return $this;
    }

    public function getGaransiItemsAttribute()
    {
        return $this->getCurrentGaransiItems();
    }

    public function getNamaDeviceAttribute($value)
    {
        return $value;
    }

    public function getKategoriDeviceAttribute($value)
    {
        return $value;
    }

    public function getMasalahAttribute($value)
    {
        return $value;
    }

    public function getTindakanPerbaikanAttribute($value)
    {
        return $value;
    }

    public function getHargaAttribute($value)
    {
        return $value;
    }

    public function getFormattedHargaAttribute()
    {
        return 'Rp. ' . number_format($this->harga, 0, ',', '.');
    }

    public function getDistinctProsesPengerjaan()
    {
        return $this->details()
            ->select('process_step', 'created_at')
            ->whereNotNull('process_step')
            ->where('process_step', '!=', 'Data perbaikan diperbarui')
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique('process_step')
            ->values();
    }

    public function addProsesStep($step)
    {
        if ($step !== 'Data perbaikan diperbarui') {
            DetailPerbaikan::create([
                'perbaikan_id' => $this->id,
                'process_step' => $step,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        return $this;
    }

    public function getActiveGaransiCount()
    {
        return $this->getCurrentGaransiItems()->count();
    }

    public function getGaransiSummary()
    {
        $items = $this->getCurrentGaransiItems();

        if ($items->count() === 0) {
            return [
                'count' => 0,
                'text' => 'Tidak ada garansi',
                'items' => []
            ];
        }

        return [
            'count' => $items->count(),
            'text' => $items->map(function ($item) {
                return $item->sparepart . ': ' . $item->periode;
            })->implode('; '),
            'items' => $items->map(function ($item) {
                return [
                    'sparepart' => $item->sparepart,
                    'periode' => $item->periode
                ];
            })->toArray()
        ];
    }
    public function syncGaransiItems($garansiItems)
    {

        $this->garansi()->delete();

        foreach ($garansiItems as $item) {
            if (!empty($item['sparepart']) && !empty($item['periode'])) {
                $this->garansi()->create([
                    'sparepart' => trim($item['sparepart']),
                    'periode' => trim($item['periode'])
                ]);
            }
        }
    }
}
