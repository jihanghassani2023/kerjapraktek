<?php
// app/Models/DetailPerbaikan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DetailPerbaikan extends Model
{
    use HasFactory;

    protected $table = 'detail_perbaikan';

    protected $fillable = [
        'perbaikan_id',
        'harga',
        'process_step',
        'garansi_sparepart',
        'garansi_periode'
    ];

    protected $casts = [
        'harga' => 'decimal:2'
    ];

    // Relasi ke Perbaikan
    public function perbaikan()
    {
        return $this->belongsTo(Perbaikan::class, 'perbaikan_id', 'id');
    }

    // Scope untuk mendapatkan data terbaru per garansi item
    public function scopeLatestData($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // Scope untuk mendapatkan proses pengerjaan saja (urut terbaru)
    public function scopeProcessOnly($query)
    {
        return $query->select('process_step', 'created_at')
                    ->orderBy('created_at', 'desc');
    }

    /**
     * Helper method untuk membuat records baru dengan semua garansi items
     * UPDATED: Removed garansi field
     *
     * @param string $perbaikanId
     * @param array $mainData (now only contains harga)
     * @param array $garansiItems
     * @param string $processStep
     * @return array
     */
    public static function createPerbaikanRecords($perbaikanId, $mainData, $garansiItems, $processStep)
    {
        $records = [];

        foreach ($garansiItems as $garansi) {
            $records[] = self::create([
                'perbaikan_id' => $perbaikanId,
                'harga' => $mainData['harga'] ?? 0,
                'process_step' => $processStep,
                'garansi_sparepart' => $garansi['sparepart'],
                'garansi_periode' => $garansi['periode']
            ]);
        }

        return $records;
    }

    /**
     * Helper method untuk update dengan membuat records baru
     * UPDATED: Removed garansi field
     *
     * @param string $perbaikanId
     * @param array $updates (now only harga)
     * @param string|null $newProcessStep
     * @return array
     */
    public static function updatePerbaikanRecords($perbaikanId, $updates = [], $newProcessStep = null)
    {
        // Ambil data garansi terbaru (distinct)
        $latestGaransiItems = self::where('perbaikan_id', $perbaikanId)
            ->select('garansi_sparepart', 'garansi_periode')
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique(function ($item) {
                return $item->garansi_sparepart . '|' . $item->garansi_periode;
            })
            ->take(self::getGaransiItemsCount($perbaikanId));

        // Ambil harga terbaru
        $latestMainData = self::where('perbaikan_id', $perbaikanId)
            ->latest()
            ->first();

        if (!$latestMainData) {
            throw new \Exception("No existing data found for perbaikan_id: {$perbaikanId}");
        }

        $records = [];

        foreach ($latestGaransiItems as $garansi) {
            $records[] = self::create([
                'perbaikan_id' => $perbaikanId,
                'harga' => $updates['harga'] ?? $latestMainData->harga,
                'process_step' => $newProcessStep ?? $latestMainData->process_step,
                'garansi_sparepart' => $garansi->garansi_sparepart,
                'garansi_periode' => $garansi->garansi_periode
            ]);
        }

        return $records;
    }

    /**
     * Helper method untuk mendapatkan jumlah garansi items yang unik
     *
     * @param string $perbaikanId
     * @return int
     */
    private static function getGaransiItemsCount($perbaikanId)
    {
        return self::where('perbaikan_id', $perbaikanId)
            ->select('garansi_sparepart', 'garansi_periode')
            ->distinct()
            ->count();
    }

    /**
     * Get latest records for a perbaikan (one per garansi item)
     *
     * @param string $perbaikanId
     * @return \Illuminate\Database\Eloquent\Collection
     */
   public static function getLatestRecords($perbaikanId)
{
    // Ambil timestamp dari record terbaru
    $latestTimestamp = self::where('perbaikan_id', $perbaikanId)
        ->max('created_at');

    if (!$latestTimestamp) {
        return collect([]);
    }

    // Ambil HANYA record dengan timestamp terbaru
    return self::where('perbaikan_id', $perbaikanId)
        ->where('created_at', $latestTimestamp)
        ->orderBy('id', 'desc')
        ->get();
}

/**
 * Get CURRENT garansi state - untuk display di UI
 *
 * @param string $perbaikanId
 * @return \Illuminate\Database\Eloquent\Collection
 */
public static function getCurrentGaransiItems($perbaikanId)
{
    $latestRecords = self::getLatestRecords($perbaikanId);

    // Filter hanya garansi yang tidak null
    return $latestRecords->filter(function($record) {
        return !is_null($record->garansi_sparepart) && !is_null($record->garansi_periode);
    });
}

/**
 * Get CURRENT main data - untuk display di UI
 * UPDATED: Only return harga, other fields are in perbaikan table
 *
 * @param string $perbaikanId
 * @return DetailPerbaikan|null
 */
public static function getCurrentMainData($perbaikanId)
{
    return self::getLatestRecords($perbaikanId)->first();
}

/**
 * Check if garansi exists in current state
 *
 * @param string $perbaikanId
 * @return bool
 */
public static function hasCurrentGaransi($perbaikanId)
{
    return self::getCurrentGaransiItems($perbaikanId)->count() > 0;
}

    /**
     * Get distinct process steps history
     *
     * @param string $perbaikanId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getProcessHistory($perbaikanId)
    {
        return self::where('perbaikan_id', $perbaikanId)
            ->select('process_step', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique('process_step')
            ->values();
    }

    /**
     * Get distinct garansi items
     *
     * @param string $perbaikanId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getGaransiItems($perbaikanId)
    {
        return self::where('perbaikan_id', $perbaikanId)
            ->select('garansi_sparepart', 'garansi_periode')
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique(function ($item) {
                return $item->garansi_sparepart . '|' . $item->garansi_periode;
            })
            ->values();
    }

    // Accessor untuk format harga
    public function getFormattedHargaAttribute()
    {
        return 'Rp. ' . number_format($this->harga, 0, ',', '.');
    }

    // UPDATED: Accessor untuk format garansi (tidak lagi dari field garansi)
    public function getFormattedGaransiAttribute()
    {
        if ($this->garansi_sparepart && $this->garansi_periode) {
            return $this->garansi_sparepart . ': ' . $this->garansi_periode;
        }
        return 'Tidak ada garansi';
    }

    // Scope untuk filter berdasarkan range harga
    public function scopeByHargaRange($query, $min, $max)
    {
        return $query->whereBetween('harga', [$min, $max]);
    }

    /**
     * FLEXIBLE: Create perbaikan records - handle empty garansi cases
     * UPDATED: Removed garansi field
     *
     * @param string $perbaikanId
     * @param array $mainData (only harga)
     * @param array $garansiItems (bisa kosong atau berisi null values)
     * @param string|null $processStep
     * @return array
     * @throws \Exception
     */
    public static function createPerbaikanRecordsFlexible($perbaikanId, $mainData, $garansiItems, $processStep = null)
    {
        try {
            $createdRecords = [];
            $timestamp = now();

            // Validasi input
            if (empty($perbaikanId)) {
                throw new \InvalidArgumentException('Perbaikan ID tidak boleh kosong');
            }

            // Validasi perbaikan exists
            $perbaikan = \App\Models\Perbaikan::find($perbaikanId);
            if (!$perbaikan) {
                throw new \InvalidArgumentException('Perbaikan dengan ID ' . $perbaikanId . ' tidak ditemukan');
            }

            // Handle case garansi kosong
            if (empty($garansiItems)) {
                $garansiItems = [['sparepart' => null, 'periode' => null]];
            }

            // Gunakan database transaction untuk konsistensi
            DB::transaction(function () use ($perbaikanId, $mainData, $garansiItems, $processStep, $timestamp, &$createdRecords) {

                // Buat record untuk setiap garansi item (termasuk yang null)
                foreach ($garansiItems as $index => $garansiItem) {
                    $recordData = [
                        'perbaikan_id' => $perbaikanId,
                        'created_at' => $timestamp,
                        'updated_at' => $timestamp,
                    ];

                    // Set harga
                    if (isset($mainData['harga']) && !is_null($mainData['harga'])) {
                        $recordData['harga'] = $mainData['harga'];
                    }

                    // Set garansi data (bisa null)
                    if (isset($garansiItem['sparepart']) && !is_null($garansiItem['sparepart'])) {
                        $recordData['garansi_sparepart'] = trim($garansiItem['sparepart']);
                    } else {
                        $recordData['garansi_sparepart'] = null;
                    }

                    if (isset($garansiItem['periode']) && !is_null($garansiItem['periode'])) {
                        $recordData['garansi_periode'] = trim($garansiItem['periode']);
                    } else {
                        $recordData['garansi_periode'] = null;
                    }

                    // Set process step jika ada
                    if ($processStep && !empty(trim($processStep))) {
                        $recordData['process_step'] = trim($processStep);
                    }

                    // Create record using Eloquent
                    $record = self::create($recordData);

                    if (!$record) {
                        $sparepartInfo = $garansiItem['sparepart'] ?? 'no garansi';
                        throw new \Exception("Gagal menyimpan detail perbaikan untuk: {$sparepartInfo}");
                    }

                    $createdRecords[] = $record;
                }
            });

            // Log successful creation
            logger()->info('Successfully created flexible perbaikan records', [
                'perbaikan_id' => $perbaikanId,
                'records_count' => count($createdRecords),
                'garansi_items_count' => count($garansiItems)
            ]);

            return $createdRecords;

        } catch (\Exception $e) {
            // Log error dengan detail
            logger()->error('Error in createPerbaikanRecordsFlexible: ' . $e->getMessage(), [
                'perbaikan_id' => $perbaikanId,
                'main_data' => $mainData,
                'garansi_items' => $garansiItems,
                'process_step' => $processStep,
                'stack_trace' => $e->getTraceAsString()
            ]);

            // Re-throw exception agar bisa di-handle di controller
            throw new \Exception('Gagal membuat detail perbaikan: ' . $e->getMessage());
        }
    }
}
