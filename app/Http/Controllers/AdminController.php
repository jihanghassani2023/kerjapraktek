<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perbaikan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Menampilkan dashboard admin dengan daftar transaksi
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Statistik untuk dashboard
        $totalPerbaikan = Perbaikan::count();
        $totalMenunggu = Perbaikan::where('status', 'Menunggu')->count();
        $totalProses = Perbaikan::where('status', 'Proses')->count();
        $totalSelesai = Perbaikan::where('status', 'Selesai')->count();
        
        // Total pendapatan
        $totalPendapatan = Perbaikan::where('status', 'Selesai')->sum('harga');
        
        // Pendapatan hari ini
        $pendapatanHariIni = Perbaikan::where('status', 'Selesai')
            ->whereDate('tanggal_perbaikan', Carbon::today())
            ->sum('harga');
            
        // Pendapatan bulan ini
        $pendapatanBulanIni = Perbaikan::where('status', 'Selesai')
            ->whereMonth('tanggal_perbaikan', Carbon::now()->month)
            ->whereYear('tanggal_perbaikan', Carbon::now()->year)
            ->sum('harga');
            
        // Transaksi terbaru (5 data terakhir)
        $latesTransaksi = Perbaikan::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        return view('admin.dashboard', compact(
            'user',
            'totalPerbaikan',
            'totalMenunggu',
            'totalProses',
            'totalSelesai',
            'totalPendapatan',
            'pendapatanHariIni',
            'pendapatanBulanIni',
            'latesTransaksi'
        ));
    }

    /**
     * Menampilkan daftar transaksi berdasarkan status
     */
    public function transaksi(Request $request)
    {
        $user = Auth::user();
        
        // Filter berdasarkan status
        $status = $request->get('status', 'all');
        $query = Perbaikan::with('user')->orderBy('created_at', 'desc');
        
        if ($status != 'all') {
            $query->where('status', ucfirst($status));
        }
        
        $transaksi = $query->paginate(15);
        
        return view('admin.transaksi', compact('user', 'transaksi', 'status'));
    }

    /**
     * Menampilkan detail transaksi
     */
    public function transaksiDetail($id)
    {
        $user = Auth::user();
        $transaksi = Perbaikan::with('user')->findOrFail($id);
        
        return view('admin.transaksi_detail', compact('user', 'transaksi'));
    }

    /**
     * Mengubah status transaksi
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Menunggu,Proses,Selesai'
        ]);
        
        $transaksi = Perbaikan::findOrFail($id);
        $transaksi->status = $request->status;
        $transaksi->save();
        
        return redirect()->back()->with('success', 'Status transaksi berhasil diubah');
    }

    /**
     * Export data transaksi (contoh implementasi)
     */
    public function exportTransaksi(Request $request)
    {
        // Implementasi export data transaksi bisa ditambahkan di sini
        // Contoh: menggunakan package seperti maatwebsite/excel
        
        return redirect()->back()->with('info', 'Fitur export data akan segera tersedia');
    }
}