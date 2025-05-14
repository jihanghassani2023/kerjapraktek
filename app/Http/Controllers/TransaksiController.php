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
    public function index(Request $request)
    {
        $user = Auth::user();

        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        $query = Perbaikan::query();

        if ($month && $year) {
            $query->whereMonth('tanggal_perbaikan', $month)
                  ->whereYear('tanggal_perbaikan', $year);
        }

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


    public function show($id)
    {
        $user = Auth::user();
        // Always get fresh data with findOrFail
       $transaksi = Perbaikan::with(['user', 'pelanggan'])->findOrFail($id);


        return view('kepala_toko.detail_transaksi', compact('user', 'transaksi'));
    }


    public function export(Request $request)
    {

        return redirect()->back()->with('info', 'Fitur export data akan segera tersedia');
    }


    public function dashboard()
    {
        $user = Auth::user();

        $karyawan = Karyawan::orderBy('created_at', 'desc')
            ->take(3)
            ->get();

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

        $teknisiCount = Karyawan::whereIn('jabatan', ['Teknisi', 'Kepala Teknisi'])->count();

        $latestTransaksi = Perbaikan::with('user')
            ->where('status', 'Selesai')
            ->orderBy('tanggal_perbaikan', 'desc')
            ->take(3)
            ->get();

        $totalTransaksiHariIni = Perbaikan::where('status', 'Selesai')
            ->whereDate('tanggal_perbaikan', date('Y-m-d'))
            ->sum('harga');

        $totalTransaksiBulanIni = Perbaikan::where('status', 'Selesai')
            ->whereMonth('tanggal_perbaikan', date('m'))
            ->whereYear('tanggal_perbaikan', date('Y'))
            ->sum('harga');

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
