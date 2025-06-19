<?php
// app/Http/Controllers/SearchController.php

namespace App\Http\Controllers;

use App\Helpers\DateHelper;
use App\Models\Perbaikan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    /**
     * Get search suggestions based on input.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function suggestions(Request $request)
    {
        $query = $request->input('query');

        if (empty($query)) {
            return response()->json([]);
        }

        // Search for repairs matching the query
        $perbaikan = Perbaikan::with(['pelanggan'])
            ->where(function($q) use ($query) {
                $q->where('id', 'like', "%{$query}%")
                  ->orWhere('nama_device', 'like', "%{$query}%") // UPDATED: langsung dari perbaikan
                  ->orWhereHas('pelanggan', function($subq) use ($query) {
                      $subq->where('nama_pelanggan', 'like', "%{$query}%")
                          ->orWhere('nomor_telp', 'like', "%{$query}%");
                  });
            })
            ->orderBy('created_at', 'desc')
            ->take(5) // Limit results to 5 for better performance
            ->get();

        $suggestions = [];

        foreach ($perbaikan as $item) {
            $suggestions[] = [
                'id' => $item->id,
                'kode_perbaikan' => $item->id,
                'nama_device' => $item->nama_device, // UPDATED: langsung dari perbaikan
                'nama_pelanggan' => $item->pelanggan->nama_pelanggan ?? 'N/A',
                'tanggal' => DateHelper::formatTanggalIndonesia($item->tanggal_perbaikan),
                'status' => $item->status,
                'url' => route('admin.transaksi.show', $item->id)
            ];
        }

        return response()->json($suggestions);
    }
}
