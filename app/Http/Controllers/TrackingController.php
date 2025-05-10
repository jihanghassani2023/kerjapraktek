<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perbaikan;

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
     * Memeriksa kode perbaikan dan menampilkan hasilnya
     */
    public function check(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
        ]);

        // Cari data perbaikan berdasarkan kode
        $perbaikan = Perbaikan::where('kode_perbaikan', $request->key)
            ->with(['user', 'pelanggan'])
            ->first();

        if (!$perbaikan) {
            return redirect()->route('tracking.index')
                ->with('error', 'Kode perbaikan tidak ditemukan. Mohon periksa kembali kode Anda.');
        }

        return view('tracking.index', compact('perbaikan'));
    }
}
