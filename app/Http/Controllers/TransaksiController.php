<?php

namespace App\Http\Controllers;

use App\Helpers\DateHelper;
use App\Models\Perbaikan;
use App\Models\User;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class TransaksiController extends Controller
{
    // Fixed export method
    public function export(Request $request)
    {
        // Get filter parameters
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        // Query data based on filters
        $query = Perbaikan::query();

        if ($month && $year) {
            $query->whereMonth('tanggal_perbaikan', $month)
                ->whereYear('tanggal_perbaikan', $year);
        }

        $transaksi = $query->with('user')
    ->orderBy('tanggal_perbaikan', 'desc')
    ->get()
    ->map(function($item) {
        $item->tanggal_formatted = DateHelper::formatTanggalIndonesia($item->tanggal_perbaikan);
        return $item;
    });
        // Create a new spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Add headers
        $sheet->setCellValue('A1', 'No.');
        $sheet->setCellValue('B1', 'Kode Perbaikan');
        $sheet->setCellValue('C1', 'Tanggal');
        $sheet->setCellValue('D1', 'Device');
        $sheet->setCellValue('E1', 'Pelanggan');
        $sheet->setCellValue('F1', 'Teknisi');
        $sheet->setCellValue('G1', 'Harga');
        $sheet->setCellValue('H1', 'Status');

        // Add data
        $row = 2;
        foreach ($transaksi as $index => $t) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $t->id); // Fix: Use $t->id instead of $t->kode_perbaikan
            $sheet->setCellValue('C' . $row, \Carbon\Carbon::parse($t->tanggal_perbaikan)->format('d M Y'));
            $sheet->setCellValue('D' . $row, $t->nama_device); // Fix: Use $t->nama_device instead of $t->nama_barang
            $sheet->setCellValue('E' . $row, $t->pelanggan ? $t->pelanggan->nama_pelanggan : 'N/A');
            $sheet->setCellValue('F' . $row, $t->user ? $t->user->name : 'N/A');
            $sheet->setCellValue('G' . $row, $t->harga);
            $sheet->setCellValue('H' . $row, $t->status);
            $row++;
        }

        // Create temporary file
        $fileName = 'transaksi_' . date('YmdHis') . '.xlsx';
        $filePath = storage_path('app/public/' . $fileName);

        // Save the spreadsheet
        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);

        // Download the file
        return response()->download($filePath)->deleteFileAfterSend(true);
    }

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

        // Fix: Get technicians with their repair count (same as admin)
        $teknisi = User::whereIn('role', ['teknisi', 'kepala teknisi'])->get();
        $teknisiStats = [];

        foreach ($teknisi as $t) {
            // Get count of completed repairs
            $repairCount = Perbaikan::where('user_id', $t->id)
                ->where('status', 'Selesai')
                ->whereMonth('tanggal_perbaikan', $month)
                ->whereYear('tanggal_perbaikan', $year)
                ->count();

            // Get count of pending/in-process repairs
            $pendingCount = Perbaikan::where('user_id', $t->id)
                ->whereIn('status', ['Menunggu', 'Proses'])
                ->whereMonth('tanggal_perbaikan', $month)
                ->whereYear('tanggal_perbaikan', $year)
                ->count();

            // Calculate income from all repairs
            $income = Perbaikan::where('user_id', $t->id)
                ->whereMonth('tanggal_perbaikan', $month)
                ->whereYear('tanggal_perbaikan', $year)
                ->sum('harga');

            $teknisiStats[] = [
                'name' => $t->name,
                'repair_count' => $repairCount,
                'pending_count' => $pendingCount,
                'income' => $income
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

    public function dashboard()
    {
        $user = Auth::user();

         $karyawan = User::whereIn('role', ['admin', 'teknisi', 'kepala teknisi'])
        ->orderBy('created_at', 'desc')
        ->take(3)
        ->get();

        $currentMonth = date('m');
        $currentYear = date('Y');

        // Update: Get counts for each status for monthly chart
        $monthlyRepairCounts = [];
        for ($i = 1; $i <= 12; $i++) {
            $selesaiCount = Perbaikan::where('status', 'Selesai')
                ->whereMonth('tanggal_perbaikan', $i)
                ->whereYear('tanggal_perbaikan', $currentYear)
                ->count();

            $prosesCount = Perbaikan::where('status', 'Proses')
                ->whereMonth('tanggal_perbaikan', $i)
                ->whereYear('tanggal_perbaikan', $currentYear)
                ->count();

            $menungguCount = Perbaikan::where('status', 'Menunggu')
                ->whereMonth('tanggal_perbaikan', $i)
                ->whereYear('tanggal_perbaikan', $currentYear)
                ->count();

            $monthlyRepairCounts[] = [
                'month' => date('F', mktime(0, 0, 0, $i, 10)),
                'selesai' => $selesaiCount,
                'proses' => $prosesCount,
                'menunggu' => $menungguCount
            ];
        }

        // Change to only count Teknisi and Kepala Teknisi
       $teknisiCount = User::whereIn('role', ['teknisi', 'kepala teknisi'])->count();

        $latestTransaksi = Perbaikan::with('user')
    ->where('status', 'Selesai')
    ->orderBy('tanggal_perbaikan', 'desc')
    ->take(3)
    ->get()
    ->map(function($item) {
        $item->tanggal_formatted = DateHelper::formatTanggalIndonesia($item->tanggal_perbaikan);
        return $item;
    });

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
