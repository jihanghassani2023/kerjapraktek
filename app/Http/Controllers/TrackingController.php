<?php
// app/Http/Controllers/TrackingController.php

namespace App\Http\Controllers;

use App\Helpers\DateHelper;
use Illuminate\Http\Request;
use App\Models\Perbaikan;
use App\Models\Pelanggan;
use Carbon\Carbon;

class TrackingController extends Controller
{
    /**
     * Menampilkan halaman tracking
     */
    public function index()
    {
        return view('tracking.index');
    }

    /**
     * Memeriksa nomor telepon pelanggan dan menampilkan hasilnya
     */
    public function check(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
        ]);

        // Cari data pelanggan berdasarkan nomor telepon
        $pelanggan = Pelanggan::where('nomor_telp', $request->key)->first();

        if (!$pelanggan) {
            return redirect()->route('tracking.index')
                ->with('error', 'Nomor telepon tidak ditemukan. Mohon periksa kembali nomor telepon Anda.');
        }

        // UPDATED: Ambil data langsung dari perbaikan table
        $allPerbaikan = Perbaikan::where('pelanggan_id', $pelanggan->id)
                            ->with(['user', 'pelanggan'])
                            ->orderBy('tanggal_perbaikan', 'desc')
                            ->get()
                            ->map(function($item) {
                                $item->tanggal_formatted = DateHelper::formatTanggalIndonesia($item->tanggal_perbaikan);
                                return $item;
                            });

        if ($allPerbaikan->isEmpty()) {
            return redirect()->route('tracking.index')
                ->with('error', 'Tidak ada perbaikan yang ditemukan untuk nomor telepon ini.');
        }

        // Filter perbaikan yang akan ditampilkan
        $perbaikanAktif = collect();

        foreach ($allPerbaikan as $perbaikan) {
            // Jika status selesai, cek apakah masih dalam rentang 7 hari
            if ($perbaikan->status === 'Selesai') {
                $tanggalSelesai = Carbon::parse($perbaikan->updated_at);
                $hariIni = Carbon::now();
                $selisihHari = $tanggalSelesai->diffInDays($hariIni);

                // Tampilkan hanya jika masih dalam 7 hari
                if ($selisihHari <= 7) {
                    $perbaikanAktif->push($perbaikan);
                }
            } else {
                // Perbaikan yang belum selesai tetap ditampilkan
                $perbaikanAktif->push($perbaikan);
            }
        }

        return view('tracking.index', compact('perbaikanAktif', 'pelanggan'));
    }
}
