<?php
// app/Http/Controllers/TransaksiController.php

namespace App\Http\Controllers;

use App\Helpers\DateHelper;
use App\Models\Perbaikan;
use App\Models\DetailPerbaikan;
use App\Models\User;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class TransaksiController extends Controller
{
    public function export(Request $request)
    {
        // Get filter parameters
        $month = $request->input('month');
        $year = $request->input('year');

        // Query data based on filters
        $query = Perbaikan::query();

        if ($month && $year) {
            $query->whereMonth('tanggal_perbaikan', $month)
                ->whereYear('tanggal_perbaikan', $year);
        } elseif ($month) {
            $query->whereMonth('tanggal_perbaikan', $month);
        } elseif ($year) {
            $query->whereYear('tanggal_perbaikan', $year);
        }

        $transaksi = $query->with(['user', 'pelanggan'])
            ->orderBy('tanggal_perbaikan', 'desc')
            ->get()
            ->map(function ($item) {
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
            $sheet->setCellValue('B' . $row, $t->id);
            $sheet->setCellValue('C' . $row, \Carbon\Carbon::parse($t->tanggal_perbaikan)->format('d M Y'));
            $sheet->setCellValue('D' . $row, $t->nama_device); // UPDATED: langsung dari perbaikan
            $sheet->setCellValue('E' . $row, $t->pelanggan ? $t->pelanggan->nama_pelanggan : 'N/A');
            $sheet->setCellValue('F' . $row, $t->user ? $t->user->name : 'N/A');
            $sheet->setCellValue('G' . $row, $t->harga); // UPDATED: dari accessor
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

        // Ambil parameter filter dari request
        $month = $request->input('month');
        $year = $request->input('year');

        // Query transaksi dengan filter kondisional
        $query = Perbaikan::query();

        if ($month && $year) {
            $query->whereMonth('tanggal_perbaikan', $month)
                  ->whereYear('tanggal_perbaikan', $year);
        } elseif ($month) {
            $query->whereMonth('tanggal_perbaikan', $month);
        } elseif ($year) {
            $query->whereYear('tanggal_perbaikan', $year);
        }

        $transaksi = $query->with(['user', 'pelanggan'])
            ->orderBy('tanggal_perbaikan', 'desc')
            ->get();

        // Calculate summary statistics
        $totalTransaksi = $transaksi->sum(function($item) {
            return $item->harga; // UPDATED: langsung dari accessor
        });

        $totalTransaksiHariIni = DetailPerbaikan::whereHas('perbaikan', function($query) {
            $query->where('status', 'Selesai')
                  ->whereDate('tanggal_perbaikan', date('Y-m-d'));
        })->sum('harga');

        $totalTransaksiBulanIni = DetailPerbaikan::whereHas('perbaikan', function($query) {
            $query->where('status', 'Selesai')
                  ->whereMonth('tanggal_perbaikan', date('m'))
                  ->whereYear('tanggal_perbaikan', date('Y'));
        })->sum('harga');

        // Get technicians stats
        $teknisi = User::whereIn('role', ['teknisi', 'kepala teknisi'])->get();
        $teknisiStats = [];

        foreach ($teknisi as $t) {
            $querySelesai = Perbaikan::where('user_id', $t->id)->where('status', 'Selesai');
            $queryPending = Perbaikan::where('user_id', $t->id)->whereIn('status', ['Menunggu', 'Proses']);

            $incomeQuery = DetailPerbaikan::whereHas('perbaikan', function($query) use ($t) {
                $query->where('user_id', $t->id);
            });

            if ($month) {
                $querySelesai->whereMonth('tanggal_perbaikan', $month);
                $queryPending->whereMonth('tanggal_perbaikan', $month);
                $incomeQuery->whereHas('perbaikan', function($query) use ($month) {
                    $query->whereMonth('tanggal_perbaikan', $month);
                });
            }

            if ($year) {
                $querySelesai->whereYear('tanggal_perbaikan', $year);
                $queryPending->whereYear('tanggal_perbaikan', $year);
                $incomeQuery->whereHas('perbaikan', function($query) use ($year) {
                    $query->whereYear('tanggal_perbaikan', $year);
                });
            }

            $repairCount = $querySelesai->count();
            $pendingCount = $queryPending->count();
            $income = $incomeQuery->sum('harga');

            $teknisiStats[] = [
                'name' => $t->name,
                'role' => $t->role,
                'jabatan' => $t->jabatan ?? $t->role,
                'repair_count' => $repairCount,
                'pending_count' => $pendingCount,
                'income' => $income
            ];
        }

        // Sort teknisi stats
        $teknisiStats = collect($teknisiStats)->sortBy(function ($teknisi) {
            if ($teknisi['role'] === 'kepala teknisi' ||
                (isset($teknisi['jabatan']) && strtolower($teknisi['jabatan']) === 'kepala teknisi')) {
                return 0;
            }
            return 1;
        })->values()->toArray();

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
        $transaksi = Perbaikan::with(['user', 'pelanggan'])->findOrFail($id);

        return view('kepala_toko.detail_transaksi', compact('user', 'transaksi'));
    }

    public function dashboard(Request $request)
    {
        $user = Auth::user();

        // Get selected year and month from request, default to current
        $selectedYear = $request->input('year', date('Y'));
        $selectedMonth = $request->input('month', 'all'); // Default to 'all' for all months

        $karyawan = User::whereIn('role', ['admin', 'teknisi', 'kepala teknisi'])
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // Update: Get counts for each status based on selected month/year
        $monthlyRepairCounts = [];

        if ($selectedMonth === 'all') {
            // Show all months for the selected year
            for ($i = 1; $i <= 12; $i++) {
                $selesaiCount = Perbaikan::where('status', 'Selesai')
                    ->whereMonth('tanggal_perbaikan', $i)
                    ->whereYear('tanggal_perbaikan', $selectedYear)
                    ->count();

                $prosesCount = Perbaikan::where('status', 'Proses')
                    ->whereMonth('tanggal_perbaikan', $i)
                    ->whereYear('tanggal_perbaikan', $selectedYear)
                    ->count();

                $menungguCount = Perbaikan::where('status', 'Menunggu')
                    ->whereMonth('tanggal_perbaikan', $i)
                    ->whereYear('tanggal_perbaikan', $selectedYear)
                    ->count();

                $monthlyRepairCounts[] = [
                    'month' => date('M', mktime(0, 0, 0, $i, 10)),
                    'selesai' => $selesaiCount,
                    'proses' => $prosesCount,
                    'menunggu' => $menungguCount
                ];
            }
        } else {
            // Show only the selected month (weekly breakdown)
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $selectedMonth, $selectedYear);
            $weeksInMonth = ceil($daysInMonth / 7);

            for ($week = 1; $week <= $weeksInMonth; $week++) {
                $startDay = ($week - 1) * 7 + 1;
                $endDay = min($week * 7, $daysInMonth);

                $startDate = date('Y-m-d', mktime(0, 0, 0, $selectedMonth, $startDay, $selectedYear));
                $endDate = date('Y-m-d', mktime(0, 0, 0, $selectedMonth, $endDay, $selectedYear));

                $selesaiCount = Perbaikan::where('status', 'Selesai')
                    ->whereBetween('tanggal_perbaikan', [$startDate, $endDate])
                    ->count();

                $prosesCount = Perbaikan::where('status', 'Proses')
                    ->whereBetween('tanggal_perbaikan', [$startDate, $endDate])
                    ->count();

                $menungguCount = Perbaikan::where('status', 'Menunggu')
                    ->whereBetween('tanggal_perbaikan', [$startDate, $endDate])
                    ->count();

                $monthlyRepairCounts[] = [
                    'month' => "Minggu {$week}",
                    'selesai' => $selesaiCount,
                    'proses' => $prosesCount,
                    'menunggu' => $menungguCount
                ];
            }
        }

        // Count only Teknisi and Kepala Teknisi
        $teknisiCount = User::whereIn('role', ['teknisi', 'kepala teknisi'])->count();

        // Get latest transactions based on filter
        if ($selectedMonth === 'all') {
            $latestTransaksi = Perbaikan::with(['user'])
                ->where('status', 'Selesai')
                ->whereYear('tanggal_perbaikan', $selectedYear)
                ->orderBy('tanggal_perbaikan', 'desc')
                ->take(3)
                ->get()
                ->map(function($item) {
                    $item->tanggal_formatted = DateHelper::formatTanggalIndonesia($item->tanggal_perbaikan);
                    return $item;
                });

            // Calculate transactions for selected year
            $totalTransaksiBulanIni = DetailPerbaikan::whereHas('perbaikan', function($query) use ($selectedYear) {
                $query->where('status', 'Selesai')
                      ->whereYear('tanggal_perbaikan', $selectedYear);
            })->sum('harga');

            $repairsByTeknisi = Perbaikan::selectRaw('user_id, count(*) as count')
                ->where('status', 'Selesai')
                ->whereYear('tanggal_perbaikan', $selectedYear)
                ->groupBy('user_id')
                ->get();
        } else {
            $latestTransaksi = Perbaikan::with(['user'])
                ->where('status', 'Selesai')
                ->whereMonth('tanggal_perbaikan', $selectedMonth)
                ->whereYear('tanggal_perbaikan', $selectedYear)
                ->orderBy('tanggal_perbaikan', 'desc')
                ->take(3)
                ->get()
                ->map(function($item) {
                    $item->tanggal_formatted = DateHelper::formatTanggalIndonesia($item->tanggal_perbaikan);
                    return $item;
                });

            // Calculate transactions for selected month and year
            $totalTransaksiBulanIni = DetailPerbaikan::whereHas('perbaikan', function($query) use ($selectedMonth, $selectedYear) {
                $query->where('status', 'Selesai')
                      ->whereMonth('tanggal_perbaikan', $selectedMonth)
                      ->whereYear('tanggal_perbaikan', $selectedYear);
            })->sum('harga');

            $repairsByTeknisi = Perbaikan::selectRaw('user_id, count(*) as count')
                ->where('status', 'Selesai')
                ->whereMonth('tanggal_perbaikan', $selectedMonth)
                ->whereYear('tanggal_perbaikan', $selectedYear)
                ->groupBy('user_id')
                ->get();
        }

        // Generate year options (current year ± 5 years)
        $currentYear = date('Y');
        $yearOptions = [];
        for ($year = $currentYear - 5; $year <= $currentYear + 2; $year++) {
            $yearOptions[] = $year;
        }

        // Calculate transactions for today (always current date)
        $totalTransaksiHariIni = DetailPerbaikan::whereHas('perbaikan', function($query) {
            $query->where('status', 'Selesai')
                  ->whereDate('tanggal_perbaikan', date('Y-m-d'));
        })->sum('harga');

        // Generate month options
        $monthOptions = [
            'all' => 'Semua Bulan',
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        return view('kepala_toko.dashboard', compact(
            'user',
            'karyawan',
            'teknisiCount',
            'monthlyRepairCounts',
            'latestTransaksi',
            'totalTransaksiHariIni',
            'totalTransaksiBulanIni',
            'repairsByTeknisi',
            'selectedYear',
            'selectedMonth',
            'yearOptions',
            'monthOptions'
        ));
    }
}
