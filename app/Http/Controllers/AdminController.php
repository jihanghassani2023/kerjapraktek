<?php

namespace App\Http\Controllers;

use App\Models\Perbaikan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Menampilkan daftar transaksi.
     */
    public function transaksi(Request $request)
    {
        $user = Auth::user();

        // Filter berdasarkan bulan dan tahun
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        // Ambil semua data perbaikan
        $query = Perbaikan::query();

        // Terapkan filter tanggal jika disediakan
        if ($month && $year) {
            $query->whereMonth('tanggal_perbaikan', $month)
                  ->whereYear('tanggal_perbaikan', $year);
        }

        // Ambil transaksi dengan data teknisi
        $transaksi = $query->with('user')
                         ->orderBy('tanggal_perbaikan', 'desc')
                         ->get();

        // Hitung ringkasan statistik
        $totalTransaksi = $transaksi->sum('harga');
        $totalTransaksiHariIni = Perbaikan::where('status', 'Selesai')
                                        ->whereDate('tanggal_perbaikan', date('Y-m-d'))
                                        ->sum('harga');
        $totalTransaksiBulanIni = Perbaikan::where('status', 'Selesai')
                                         ->whereMonth('tanggal_perbaikan', date('m'))
                                         ->whereYear('tanggal_perbaikan', date('Y'))
                                         ->sum('harga');

        // Data teknisi dengan jumlah perbaikan
        $teknisi = User::where('role', 'teknisi')->get();
        $teknisiStats = [];

        foreach ($teknisi as $t) {
            $repairCount = Perbaikan::where('user_id', $t->id)
                                  ->whereMonth('tanggal_perbaikan', $month)
                                  ->whereYear('tanggal_perbaikan', $year)
                                  ->count();
                                  
            $teknisiStats[] = [
                'name' => $t->name,
                'repair_count' => $repairCount,
                'income' => Perbaikan::where('user_id', $t->id)
                                  ->whereMonth('tanggal_perbaikan', $month)
                                  ->whereYear('tanggal_perbaikan', $year)
                                  ->sum('harga')
            ];
        }

        return view('admin.transaksi', compact(
            'user', 
            'transaksi', 
            'totalTransaksi', 
            'totalTransaksiHariIni', 
            'totalTransaksiBulanIni',
            'teknisiStats',
            'month',
            'year'
        ));
    }

    /**
     * Menampilkan detail transaksi tertentu.
     */
    public function showTransaksi($id)
    {
        $user = Auth::user();
        $transaksi = Perbaikan::with('user')->findOrFail($id);
        
        return view('admin.detail_transaksi', compact('user', 'transaksi'));
    }
}