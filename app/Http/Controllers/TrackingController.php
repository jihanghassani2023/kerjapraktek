<?php

namespace App\Http\Controllers;

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

        // Ambil semua perbaikan untuk pelanggan ini
        $allPerbaikan = Perbaikan::where('pelanggan_id', $pelanggan->id)
                            ->with(['user', 'pelanggan'])
                            ->orderBy('tanggal_perbaikan', 'desc')
                            ->get();

        if ($allPerbaikan->isEmpty()) {
            return redirect()->route('tracking.index')
                ->with('error', 'Tidak ada perbaikan yang ditemukan untuk nomor telepon ini.');
        }

        // Pisahkan perbaikan aktif dan riwayat
        $perbaikanAktif = collect();
        $riwayatPerbaikan = collect();

        foreach ($allPerbaikan as $perbaikan) {
            // Jika status selesai dan sudah lebih dari 10 hari, masuk ke riwayat
            if ($perbaikan->status === 'Selesai') {
                $tanggalSelesai = Carbon::parse($perbaikan->updated_at);
                $hariIni = Carbon::now();
                $selisihHari = $tanggalSelesai->diffInDays($hariIni);

                if ($selisihHari > 10) {
                    $riwayatPerbaikan->push($perbaikan);
                } else {
                    $perbaikanAktif->push($perbaikan);
                }
            } else {
                // Perbaikan yang belum selesai tetap di aktif
                $perbaikanAktif->push($perbaikan);
            }
        }

        return view('tracking.index', compact('perbaikanAktif', 'riwayatPerbaikan', 'pelanggan'));
    }
}
