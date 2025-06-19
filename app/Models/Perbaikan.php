<?php
// app/Models/Perbaikan.php

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
        'harga' // BARU: Field harga ditambahkan ke fillable
    ];

    protected $casts = [
        'harga' => 'decimal:2' // BARU: Cast harga sebagai decimal
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
                // Format tanggal: DDMMYY
                $datePrefix = now()->format('dmy'); // 190625 untuk 19 Juni 2025

                // Cari perbaikan terakhir untuk hari ini
                $todayPrefix = 'MG' . $datePrefix;

                $lastPerbaikan = self::where('id', 'LIKE', $todayPrefix . '%')
                    ->orderBy('id', 'desc')
                    ->lockForUpdate() // Lock untuk mencegah race condition
                    ->first();

                if ($lastPerbaikan) {
                    // Ambil 3 digit terakhir dan increment
                    $lastNumber = (int) substr($lastPerbaikan->id, -3);
                    $nextNumber = $lastNumber + 1;

                    // Pastikan tidak lebih dari 999 per hari
                    if ($nextNumber > 999) {
                        throw new \Exception('Maksimal 999 perbaikan per hari tercapai');
                    }
                } else {
                    // Perbaikan pertama hari ini
                    $nextNumber = 1;
                }

                // Format: MG + DDMMYY + XXX
                $newId = $todayPrefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

                // Double check untuk memastikan ID belum ada
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
            // Fallback dengan timestamp jika ada error
            $timestamp = now()->format('dmy') . now()->format('His');
            return 'MG' . substr($timestamp, 0, 9); // Ambil 9 karakter pertama
        }
    }

    public $timestamps = true;

    // ============ RELATIONSHIPS ============

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function details()
    {
        return $this->hasMany(DetailPerbaikan::class, 'perbaikan_id', 'id');
    }

    // DEPRECATED: Use getCurrentDetail() instead
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
        $latestGaransiTimestamp = $this->getLatestGaransiTimestamp();

        if (!$latestGaransiTimestamp) {
            return $this->hasMany(DetailPerbaikan::class, 'perbaikan_id', 'id')
                ->whereRaw('1 = 0');
        }

        return $this->hasMany(DetailPerbaikan::class, 'perbaikan_id', 'id')
            ->select(['perbaikan_id', 'garansi_sparepart', 'garansi_periode', 'created_at'])
            ->whereNotNull('garansi_sparepart')
            ->where('created_at', $latestGaransiTimestamp)
            ->orderBy('garansi_sparepart', 'asc');
    }

    // ============ CURRENT DATA METHODS (SIMPLIFIED) ============

    /**
     * Get CURRENT detail (latest record) - now from main table
     */
    public function getCurrentDetail()
    {
        // Return self since main data is now in perbaikan table
        return $this;
    }

    /**
     * Get CURRENT garansi items
     */
    public function getCurrentGaransiItems()
    {
        return DetailPerbaikan::getCurrentGaransiItems($this->id);
    }

    /**
     * Check if garansi has changed compared to current state
     */
    public function hasGaransiChanged($newGaransiItems)
    {
        $currentGaransi = $this->getCurrentGaransiItems();

        // Convert current to comparable format
        $currentFormatted = $currentGaransi->map(function ($item) {
            return [
                'sparepart' => $item->garansi_sparepart,
                'periode' => $item->garansi_periode
            ];
        })->sortBy('sparepart')->values()->toArray();

        // Convert new to comparable format and sort
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

    // ============ ACCESSOR ATTRIBUTES ============

    /**
     * Accessor untuk detail - return self since data is in main table
     */
    public function getDetailAttribute()
    {
        return $this;
    }

    /**
     * Accessor untuk garansi items
     */
    public function getGaransiItemsAttribute()
    {
        return $this->getCurrentGaransiItems();
    }

    // Direct access to fields
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

    // UPDATED: Harga accessor - now directly from perbaikan table
    public function getHargaAttribute($value)
    {
        return $value; // Langsung return value dari tabel perbaikan
    }

    // BARU: Formatted harga accessor
    public function getFormattedHargaAttribute()
    {
        return 'Rp. ' . number_format($this->harga, 0, ',', '.');
    }

    // ============ LEGACY HELPER METHODS ============

    public function getLatestGaransiTimestamp()
    {
        return $this->details()
            ->whereNotNull('garansi_sparepart')
            ->max('created_at');
    }

    public function getLatestGaransiItems()
    {
        return $this->getCurrentGaransiItems();
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

    // ============ UTILITY METHODS ============

    public function addProsesStep($step)
    {
        if ($step !== 'Data perbaikan diperbarui') {
            // Create new detail record for process step only (without harga)
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
                return $item->garansi_sparepart . ': ' . $item->garansi_periode;
            })->implode('; '),
            'items' => $items->map(function ($item) {
                return [
                    'sparepart' => $item->garansi_sparepart,
                    'periode' => $item->garansi_periode
                ];
            })->toArray()
        ];
    }
}
