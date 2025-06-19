<?php
// app/Models/DetailPerbaikan.php (UPDATED VERSION)

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
        'process_step'
        // REMOVED: garansi fields - now in separate table
    ];

    // Relasi ke Perbaikan
    public function perbaikan()
    {
        return $this->belongsTo(Perbaikan::class, 'perbaikan_id', 'id');
    }

    // Scope untuk mendapatkan data terbaru
    public function scopeLatestData($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // Scope untuk mendapatkan proses pengerjaan saja
    public function scopeProcessOnly($query)
    {
        return $query->select('process_step', 'created_at')
                    ->orderBy('created_at', 'desc');
    }

    /**
     * SIMPLIFIED: Create process step records only
     * Garansi is now handled separately in Garansi model
     */
    public static function createProcessStep($perbaikanId, $processStep)
    {
        return self::create([
            'perbaikan_id' => $perbaikanId,
            'process_step' => $processStep
        ]);
    }

    /**
     * Get latest process step for a perbaikan
     */
    public static function getLatestProcessStep($perbaikanId)
    {
        return self::where('perbaikan_id', $perbaikanId)
            ->whereNotNull('process_step')
            ->orderBy('created_at', 'desc')
            ->first();
    }

    /**
     * Get distinct process steps history
     */
    public static function getProcessHistory($perbaikanId)
    {
        return self::where('perbaikan_id', $perbaikanId)
            ->select('process_step', 'created_at')
            ->whereNotNull('process_step')
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique('process_step')
            ->values();
    }

    /**
     * DEPRECATED: These methods are no longer needed since garansi is in separate table
     * Kept for backward compatibility during transition
     */
    public static function getCurrentGaransiItems($perbaikanId)
    {
        // Redirect to Perbaikan model
        $perbaikan = \App\Models\Perbaikan::find($perbaikanId);
        return $perbaikan ? $perbaikan->getCurrentGaransiItems() : collect([]);
    }

    public static function getCurrentMainData($perbaikanId)
    {
        return self::where('perbaikan_id', $perbaikanId)
            ->orderBy('created_at', 'desc')
            ->first();
    }

    public static function hasCurrentGaransi($perbaikanId)
    {
        $perbaikan = \App\Models\Perbaikan::find($perbaikanId);
        return $perbaikan ? $perbaikan->getCurrentGaransiItems()->count() > 0 : false;
    }

    /**
     * UPDATED: Simplified method for creating process records
     * No longer handles garansi - that's done in Garansi model
     */
    public static function createPerbaikanRecordsFlexible($perbaikanId, $garansiItems = null, $processStep = null)
    {
        try {
            $createdRecords = [];

            // Validate perbaikan exists
            $perbaikan = \App\Models\Perbaikan::find($perbaikanId);
            if (!$perbaikan) {
                throw new \InvalidArgumentException('Perbaikan dengan ID ' . $perbaikanId . ' tidak ditemukan');
            }

            DB::transaction(function () use ($perbaikanId, $garansiItems, $processStep, &$createdRecords, $perbaikan) {

                // 1. Handle garansi items if provided (update garansi table)
                if ($garansiItems !== null && !empty($garansiItems)) {
                    // Filter valid garansi items
                    $validGaransiItems = collect($garansiItems)->filter(function($item) {
                        return isset($item['sparepart']) && isset($item['periode']) &&
                               !empty(trim($item['sparepart'])) && !empty(trim($item['periode']));
                    })->toArray();

                    if (!empty($validGaransiItems)) {
                        $perbaikan->syncGaransiItems($validGaransiItems);
                    }
                }

                // 2. Create process step record if provided
                if ($processStep && !empty(trim($processStep))) {
                    $record = self::create([
                        'perbaikan_id' => $perbaikanId,
                        'process_step' => trim($processStep),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    if (!$record) {
                        throw new \Exception("Gagal menyimpan detail perbaikan untuk: {$processStep}");
                    }

                    $createdRecords[] = $record;
                }
            });

            logger()->info('Successfully created flexible perbaikan records', [
                'perbaikan_id' => $perbaikanId,
                'records_count' => count($createdRecords),
                'garansi_items_count' => $garansiItems ? count($garansiItems) : 0
            ]);

            return $createdRecords;

        } catch (\Exception $e) {
            logger()->error('Error in createPerbaikanRecordsFlexible: ' . $e->getMessage(), [
                'perbaikan_id' => $perbaikanId,
                'garansi_items' => $garansiItems,
                'process_step' => $processStep,
                'stack_trace' => $e->getTraceAsString()
            ]);

            throw new \Exception('Gagal membuat detail perbaikan: ' . $e->getMessage());
        }
    }
}
