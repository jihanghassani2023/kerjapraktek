<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perbaikan;
use App\Models\Pelanggan;

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

        // Ambil semua perbaikan yang sedang berjalan untuk pelanggan ini
        $perbaikanList = Perbaikan::where('pelanggan_id', $pelanggan->id)
                            ->whereIn('status', ['Menunggu', 'Proses'])
                            ->with(['user', 'pelanggan'])
                            ->orderBy('created_at', 'desc')
                            ->get();

        if ($perbaikanList->isEmpty()) {
            return redirect()->route('tracking.index')
                ->with('error', 'Tidak ada perbaikan aktif yang ditemukan untuk nomor telepon ini.');
        }

        return view('tracking.index', compact('perbaikanList', 'pelanggan'));
    }
}
