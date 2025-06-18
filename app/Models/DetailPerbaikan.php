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
        'nama_device',
        'kategori_device',
        'masalah',
        'tindakan_perbaikan',
        'harga',
        'garansi',
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
     *
     * @param string $perbaikanId
     * @param array $mainData
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
                'nama_device' => $mainData['nama_device'],
                'kategori_device' => $mainData['kategori_device'],
                'masalah' => $mainData['masalah'],
                'tindakan_perbaikan' => $mainData['tindakan_perbaikan'],
                'harga' => $mainData['harga'],
                'garansi' => $garansi['sparepart'] . ': ' . $garansi['periode'], // Untuk backward compatibility
                'process_step' => $processStep,
                'garansi_sparepart' => $garansi['sparepart'],
                'garansi_periode' => $garansi['periode']
            ]);
        }

        return $records;
    }

    /**
     * Helper method untuk update dengan membuat records baru
     *
     * @param string $perbaikanId
     * @param array $updates
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

        // Ambil data utama terbaru
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
                'nama_device' => $updates['nama_device'] ?? $latestMainData->nama_device,
                'kategori_device' => $updates['kategori_device'] ?? $latestMainData->kategori_device,
                'masalah' => $updates['masalah'] ?? $latestMainData->masalah,
                'tindakan_perbaikan' => $updates['tindakan_perbaikan'] ?? $latestMainData->tindakan_perbaikan,
                'harga' => $updates['harga'] ?? $latestMainData->harga,
                'garansi' => $garansi->garansi_sparepart . ': ' . $garansi->garansi_periode,
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

    // FIXED: Ambil HANYA record dengan timestamp terbaru
    return self::where('perbaikan_id', $perbaikanId)
        ->where('created_at', $latestTimestamp)
        ->orderBy('id', 'desc')
        ->get();
}

/**
 * Get CURRENT garansi state - untuk display di UI
 * FIXED: Return current garansi items (termasuk yang kosong)
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
 * FIXED: Return only latest main repair data
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
 * FIXED: Check only current records
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

    // Accessor untuk format garansi
    public function getFormattedGaransiAttribute()
    {
        return $this->garansi_sparepart . ': ' . $this->garansi_periode;
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
    /**
     * FLEXIBLE: Create perbaikan records - handle empty garansi cases
     *
     * @param string $perbaikanId
     * @param array $mainData
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

            // FLEXIBLE: Handle case garansi kosong
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

                    // Set main data jika ada
                    if (isset($mainData['nama_device']) && !empty($mainData['nama_device'])) {
                        $recordData['nama_device'] = $mainData['nama_device'];
                    }
                    if (isset($mainData['kategori_device']) && !empty($mainData['kategori_device'])) {
                        $recordData['kategori_device'] = $mainData['kategori_device'];
                    }
                    if (isset($mainData['masalah']) && !empty($mainData['masalah'])) {
                        $recordData['masalah'] = $mainData['masalah'];
                    }
                    if (isset($mainData['tindakan_perbaikan']) && !empty($mainData['tindakan_perbaikan'])) {
                        $recordData['tindakan_perbaikan'] = $mainData['tindakan_perbaikan'];
                    }
                    if (isset($mainData['harga']) && !is_null($mainData['harga'])) {
                        $recordData['harga'] = $mainData['harga'];
                    }

                    // FLEXIBLE: Set garansi data (bisa null)
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
