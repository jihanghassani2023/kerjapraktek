<?php

namespace App\Http\Controllers;

use App\Models\Perbaikan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function transaksi(Request $request)
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

        $totalTransaksi = $transaksi->sum('harga');
        $totalTransaksiHariIni = Perbaikan::where('status', 'Selesai')
                                        ->whereDate('tanggal_perbaikan', date('Y-m-d'))
                                        ->sum('harga');
        $totalTransaksiBulanIni = Perbaikan::where('status', 'Selesai')
                                         ->whereMonth('tanggal_perbaikan', date('m'))
                                         ->whereYear('tanggal_perbaikan', date('Y'))
                                         ->sum('harga');

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

    public function showTransaksi($id)
    {
        $user = Auth::user();
        $transaksi = Perbaikan::with('user')->findOrFail($id);

        return view('admin.detail_transaksi', compact('user', 'transaksi'));
    }

    public function updateStatus(Request $request, $id)
    {
        $transaksi = Perbaikan::findOrFail($id);

        $request->validate([
            'status' => 'required|in:Menunggu,Proses,Selesai',
        ]);

        $transaksi->status = $request->status;
        $transaksi->save();

        return redirect()->route('admin.transaksi.show', $id)
            ->with('success', 'Status berhasil diperbarui');
    }
}
