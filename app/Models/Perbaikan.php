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

    /**
     * GENERATE KODE PERBAIKAN - METHOD UTAMA
     * Format: MG50001, MG50002, dst.
     */
    public static function generateKodePerbaikan()
    {
        try {
            // Gunakan DB transaction untuk menghindari duplicate ID
            return \Illuminate\Support\Facades\DB::transaction(function () {
                $lastPerbaikan = self::where('id', 'LIKE', 'MG%')
                    ->orderBy('id', 'desc')
                    ->lockForUpdate() // Lock untuk mencegah race condition
                    ->first();

                if ($lastPerbaikan) {
                    // Ambil angka dari ID terakhir (contoh: MG50001 -> 50001)
                    $lastNumber = (int) substr($lastPerbaikan->id, 2);
                    $nextNumber = $lastNumber + 1;
                } else {
                    // Jika belum ada data, mulai dari 50001
                    $nextNumber = 50001;
                }

                $newId = 'MG' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

                // Double check untuk memastikan ID belum ada
                $exists = self::where('id', $newId)->exists();
                if ($exists) {
                    // Jika masih ada, increment lagi
                    $nextNumber++;
                    $newId = 'MG' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
                }

                return $newId;
            });
        } catch (\Exception $e) {
            // Fallback dengan timestamp jika ada error
            $timestamp = now()->format('His'); // HourMinuteSecond
            return 'MG' . $timestamp;
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
            ->where('process_step', '!=', 'Data perbaikan diperbarui') // HANYA filter ini saja
            ->orderBy('created_at', 'desc');
    }

    // DEPRECATED: Use getCurrentGaransiItems() instead
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

    // ============ CURRENT DATA METHODS (FIXED) ============

    /**
     * Get CURRENT detail (latest record)
     * FIXED: Return only current data, not historical
     */
    public function getCurrentDetail()
    {
        return DetailPerbaikan::getCurrentMainData($this->id);
    }

    /**
     * Get CURRENT garansi items
     * FIXED: Return only current garansi, hide deleted ones
     */
    public function getCurrentGaransiItems()
    {
        return DetailPerbaikan::getCurrentGaransiItems($this->id);
    }

    /**
     * Check if garansi has changed compared to current state
     * FIXED: Compare with current state only
     */
    public function hasGaransiChanged($newGaransiItems)
    {
        $currentGaransi = $this->getCurrentGaransiItems();

        // Convert current to comparable format
        $currentFormatted = $currentGaransi->map(function($item) {
            return [
                'sparepart' => $item->garansi_sparepart,
                'periode' => $item->garansi_periode
            ];
        })->sortBy('sparepart')->values()->toArray();

        // Convert new to comparable format and sort
        $newFormatted = collect($newGaransiItems)
            ->map(function($item) {
                return [
                    'sparepart' => trim($item['sparepart'] ?? ''),
                    'periode' => trim($item['periode'] ?? '')
                ];
            })
            ->filter(function($item) {
                return !empty($item['sparepart']) && !empty($item['periode']);
            })
            ->sortBy('sparepart')
            ->values()
            ->toArray();

        return $currentFormatted !== $newFormatted;
    }

    // ============ ACCESSOR ATTRIBUTES ============

    /**
     * Accessor untuk detail - ambil data terbaru
     * FIXED: Use current detail instead of all history
     */
    public function getDetailAttribute()
    {
        return $this->getCurrentDetail();
    }

    /**
     * Accessor untuk garansi items - ambil data terbaru
     * FIXED: Use current garansi instead of all history
     */
    public function getGaransiItemsAttribute()
    {
        return $this->getCurrentGaransiItems();
    }

    public function getNamaDeviceAttribute()
    {
        $detail = $this->getCurrentDetail();
        return $detail ? $detail->nama_device : null;
    }

    public function getKategoriDeviceAttribute()
    {
        $detail = $this->getCurrentDetail();
        return $detail ? $detail->kategori_device : null;
    }

    public function getMasalahAttribute()
    {
        $detail = $this->getCurrentDetail();
        return $detail ? $detail->masalah : null;
    }

    public function getTindakanPerbaikanAttribute()
    {
        $detail = $this->getCurrentDetail();
        return $detail ? $detail->tindakan_perbaikan : null;
    }

    public function getHargaAttribute()
    {
        $detail = $this->getCurrentDetail();
        return $detail ? $detail->harga : 0;
    }

    // ============ LEGACY HELPER METHODS (Keep for backward compatibility) ============

    public function getLatestGaransiTimestamp()
    {
        return $this->details()
            ->whereNotNull('garansi_sparepart')
            ->max('created_at');
    }

    public function getLatestGaransiItems()
    {
        // REDIRECT to new method
        return $this->getCurrentGaransiItems();
    }

    public function getDistinctProsesPengerjaan()
    {
        return $this->details()
            ->select('process_step', 'created_at')
            ->whereNotNull('process_step')
            ->where('process_step', '!=', 'Data perbaikan diperbarui') // HANYA filter ini saja
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique('process_step')
            ->values();
    }

    // ============ UTILITY METHODS ============

    public function addProsesStep($step)
    {
        // HANYA tolak "Data perbaikan diperbarui", yang lain boleh
        if ($step !== 'Data perbaikan diperbarui') {
            DetailPerbaikan::updatePerbaikanRecords($this->id, [], $step);
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
            'text' => $items->map(function($item) {
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

    // ============ DEBUG METHODS ============

    public function getAllGaransiHistory()
    {
        return $this->details()
            ->select('garansi_sparepart', 'garansi_periode', 'created_at', 'process_step')
            ->whereNotNull('garansi_sparepart')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('created_at')
            ->map(function ($group, $timestamp) {
                return [
                    'timestamp' => $timestamp,
                    'process_step' => $group->first()->process_step,
                    'items' => $group->map(function ($item) {
                        return $item->garansi_sparepart . ': ' . $item->garansi_periode;
                    })->toArray()
                ];
            })->values();
    }

    public function debugGaransiLogic()
    {
        $allDetails = $this->details()->orderBy('created_at', 'desc')->get();
        $latestOverall = $allDetails->first();
        $latestGaransiTimestamp = $this->getLatestGaransiTimestamp();
        $currentGaransiItems = $this->getCurrentGaransiItems();

        return [
            'total_records' => $allDetails->count(),
            'latest_overall_timestamp' => $latestOverall ? $latestOverall->created_at : null,
            'latest_garansi_timestamp' => $latestGaransiTimestamp,
            'current_garansi_items' => $currentGaransiItems->map(function ($item) {
                return $item->garansi_sparepart . ': ' . $item->garansi_periode;
            })->toArray(),
            'all_garansi_history' => $this->getAllGaransiHistory()
        ];
    }
}
