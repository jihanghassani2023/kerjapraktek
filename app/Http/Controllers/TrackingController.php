<?php

namespace App\Http\Controllers;

use App\Models\Perbaikan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrackingController extends Controller
{
    /**
     * Tampilkan halaman pelacakan
     */
    public function index()
    {
        // Jika sudah login, redirect ke dashboard sesuai role
        if (Auth::check()) {
            $role = Auth::user()->role;
            
            if ($role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($role === 'kepala_toko') {
                return redirect()->route('kepala-toko.dashboard');
            } elseif ($role === 'teknisi') {
                return redirect()->route('teknisi.dashboard');
            }
        }
        
        // Jika belum login, tampilkan halaman tracking
        // Pastikan view yang benar digunakan
        return view('tracking.index');
    }
    
    /**
     * Mencari data perbaikan berdasarkan kode
     */
    public function search($kode)
    {
        // Cari perbaikan berdasarkan kode
        $perbaikan = Perbaikan::where('kode_perbaikan', $kode)->first();
        
        if (!$perbaikan) {
            return response()->json(['error' => 'Kode perbaikan tidak ditemukan'], 404);
        }
        
        // Ambil nama teknisi
        $teknisi = $perbaikan->user ? $perbaikan->user->name : 'Belum ditugaskan';
        
        // Format tanggal ke format Indonesia
        $tanggal = \Carbon\Carbon::parse($perbaikan->tanggal_perbaikan)->format('d F Y');
        
        return response()->json([
            'kode_perbaikan' => $perbaikan->kode_perbaikan,
            'nama_barang' => $perbaikan->nama_barang,
            'tanggal_perbaikan' => $perbaikan->tanggal_perbaikan,
            'masalah' => $perbaikan->masalah,
            'nama_pelanggan' => $perbaikan->nama_pelanggan,
            'status' => $perbaikan->status,
            'teknisi' => $teknisi,
            'tanggal_formatted' => $tanggal
        ]);
    }
}