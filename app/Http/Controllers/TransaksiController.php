<?php

namespace App\Http\Controllers;

use App\Models\Perbaikan;
use App\Models\User;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the transactions (completed repairs).
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Filter parameters
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        // Get all completed repairs
        $query = Perbaikan::where('status', 'Selesai');

        // Apply date filters if provided
        if ($month && $year) {
            $query->whereMonth('tanggal_perbaikan', $month)
                  ->whereYear('tanggal_perbaikan', $year);
        }

        // Get transactions with their users
        $transaksi = $query->with('user')
                         ->orderBy('tanggal_perbaikan', 'desc')
                         ->get();

        // Calculate summary statistics
        $totalTransaksi = $transaksi->sum('harga');
        $totalTransaksiHariIni = Perbaikan::where('status', 'Selesai')
                                        ->whereDate('tanggal_perbaikan', date('Y-m-d'))
                                        ->sum('harga');
        $totalTransaksiBulanIni = Perbaikan::where('status', 'Selesai')
                                         ->whereMonth('tanggal_perbaikan', date('m'))
                                         ->whereYear('tanggal_perbaikan', date('Y'))
                                         ->sum('harga');

        // Get technicians with their repair count
        $teknisi = User::where('role', 'teknisi')->get();
        $teknisiStats = [];

        foreach ($teknisi as $t) {
            $repairCount = Perbaikan::where('user_id', $t->id)
                                  ->where('status', 'Selesai')
                                  ->whereMonth('tanggal_perbaikan', $month)
                                  ->whereYear('tanggal_perbaikan', $year)
                                  ->count();
                                  
            $teknisiStats[] = [
                'name' => $t->name,
                'repair_count' => $repairCount,
                'income' => Perbaikan::where('user_id', $t->id)
                                  ->where('status', 'Selesai')
                                  ->whereMonth('tanggal_perbaikan', $month)
                                  ->whereYear('tanggal_perbaikan', $year)
                                  ->sum('harga')
            ];
        }

        return view('kepala_toko.transaksi', compact(
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
     * Display details of a specific transaction.
     */
    public function show($id)
    {
        $user = Auth::user();
        $transaksi = Perbaikan::with('user')->findOrFail($id);
        
        return view('kepala_toko.detail_transaksi', compact('user', 'transaksi'));
    }

    /**
     * Export transactions data as CSV (placeholder function).
     */
    public function export(Request $request)
    {
        // This would be implemented with actual CSV export functionality
        // For now, we'll redirect back with a message
        return redirect()->back()->with('info', 'Fitur export data akan segera tersedia');
    }
    
    /**
     * Dashboard statistics for Kepala Toko.
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get all employees for the dashboard display
        $karyawan = Karyawan::orderBy('created_at', 'desc')
            ->take(3)
            ->get();
        
        // Calculate repair counts per month
        $currentMonth = date('m');
        $currentYear = date('Y');
        
        $monthlyRepairCounts = [];
        for ($i = 1; $i <= 12; $i++) {
            $count = Perbaikan::where('status', 'Selesai')
                ->whereMonth('tanggal_perbaikan', $i)
                ->whereYear('tanggal_perbaikan', $currentYear)
                ->count();
                
            $monthlyRepairCounts[] = [
                'month' => date('F', mktime(0, 0, 0, $i, 10)),
                'count' => $count
            ];
        }
        
        // Count of technicians
        $teknisiCount = Karyawan::whereIn('jabatan', ['Teknisi', 'Kepala Teknisi'])->count();
        
        // Recent transactions
        $latestTransaksi = Perbaikan::with('user')
            ->where('status', 'Selesai')
            ->orderBy('tanggal_perbaikan', 'desc')
            ->take(3)
            ->get();
            
        // Total income statistics
        $totalTransaksiHariIni = Perbaikan::where('status', 'Selesai')
            ->whereDate('tanggal_perbaikan', date('Y-m-d'))
            ->sum('harga');
            
        $totalTransaksiBulanIni = Perbaikan::where('status', 'Selesai')
            ->whereMonth('tanggal_perbaikan', date('m'))
            ->whereYear('tanggal_perbaikan', date('Y'))
            ->sum('harga');
            
        // Count of repairs by technicians this month
        $repairsByTeknisi = Perbaikan::selectRaw('user_id, count(*) as count')
            ->where('status', 'Selesai')
            ->whereMonth('tanggal_perbaikan', date('m'))
            ->whereYear('tanggal_perbaikan', date('Y'))
            ->groupBy('user_id')
            ->get();
            
        return view('kepala_toko.dashboard', compact(
            'user',
            'karyawan',
            'teknisiCount',
            'monthlyRepairCounts',
            'latestTransaksi',
            'totalTransaksiHariIni',
            'totalTransaksiBulanIni',
            'repairsByTeknisi'
        ));
    }
}