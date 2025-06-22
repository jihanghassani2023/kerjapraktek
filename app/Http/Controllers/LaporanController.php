<?php

namespace App\Http\Controllers;

use App\Helpers\DateHelper;
use App\Models\Perbaikan;
use App\Models\DetailPerbaikan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LaporanController extends Controller
{
public function export(Request $request)
{
    $month = $request->input('month');
    $year = $request->input('year');

    $query = Perbaikan::query();

    if ($month && $year) {
        $query->whereMonth('tanggal_perbaikan', $month)
              ->whereYear('tanggal_perbaikan', $year);
    } elseif ($month) {
        $query->whereMonth('tanggal_perbaikan', $month)
              ->whereYear('tanggal_perbaikan', now()->year);
    } elseif ($year) {
        $query->whereYear('tanggal_perbaikan', $year);
    }

    $transaksi = $query->with(['user', 'pelanggan', 'garansi'])->orderBy('tanggal_perbaikan', 'desc')->get();

    if ($transaksi->isEmpty()) {
        return back()->with('info', 'Tidak ada data yang bisa diekspor.');
    }

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // === Judul & Subjudul ===
    $sheet->setCellValue('A1', 'LAPORAN PERBAIKAN');
    $sheet->mergeCells('A1:K1');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

    $namaBulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];

    $filterText = 'Filter: ';
    if ($month && $year) {
        $filterText .= $namaBulan[(int)$month] . ' ' . $year;
    } elseif ($month) {
        $filterText .= $namaBulan[(int)$month] . ' ' . now()->year;
    } elseif ($year) {
        $filterText .= $year;
    } else {
        $filterText .= 'Semua Data';
    }

    $sheet->setCellValue('A2', $filterText);
    $sheet->mergeCells('A2:K2');
    $sheet->getStyle('A2')->getFont()->setItalic(true);

    // === Header Tabel ===
    $headers = [
        'No.', 'Kode Perbaikan', 'Tanggal', 'Device', 'Pelanggan',
        'Teknisi', 'Harga', 'Status', 'Masalah', 'Tindakan', 'Garansi'
    ];

    $column = 'A';
    foreach ($headers as $header) {
        $sheet->setCellValue($column . '4', $header);
        $sheet->getStyle($column . '4')->getFont()->setBold(true);
        $column++;
    }

    // === Isi Data ===
    $row = 5;
    foreach ($transaksi as $index => $t) {
        $garansiText = $t->garansi->map(function ($g) {
            return $g->sparepart . ' (' . $g->periode . ')';
        })->implode(', ');

        $sheet->setCellValue('A' . $row, $index + 1);
        $sheet->setCellValue('B' . $row, $t->id);
        $sheet->setCellValue('C' . $row, \Carbon\Carbon::parse($t->tanggal_perbaikan)->format('d M Y'));
        $sheet->setCellValue('D' . $row, $t->nama_device);
        $sheet->setCellValue('E' . $row, $t->pelanggan->nama_pelanggan ?? '-');
        $sheet->setCellValue('F' . $row, $t->user->name ?? '-');
        $sheet->setCellValue('G' . $row, 'Rp. ' . number_format($t->harga, 0, ',', '.'));
        $sheet->setCellValue('H' . $row, $t->status);
        $sheet->setCellValue('I' . $row, $t->masalah ?? '-');
        $sheet->setCellValue('J' . $row, $t->tindakan_perbaikan ?? '-');
        $sheet->setCellValue('K' . $row, $garansiText ?: '-');

        $row++;
    }

    // Auto-size kolom
    foreach (range('A', 'K') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Nama file
    $filterInfo = '';
    if ($month && $year) {
        $filterInfo = '_' . $namaBulan[(int)$month] . '_' . $year;
    } elseif ($month) {
        $filterInfo = '_' . $namaBulan[(int)$month] . '_' . now()->year;
    } elseif ($year) {
        $filterInfo = '_' . $year;
    }

    $fileName = 'laporan_kepala_toko' . $filterInfo . '_' . now()->format('YmdHis') . '.xlsx';
    $filePath = storage_path('app/public/' . $fileName);

    $writer = new Xlsx($spreadsheet);
    $writer->save($filePath);

    return response()->download($filePath)->deleteFileAfterSend(true);
}


    public function index(Request $request)
    {
        $user = Auth::user();

        $month = $request->input('month');
        $year = $request->input('year');

        // Debug: Log parameter yang diterima
        Log::info('Index parameters:', ['month' => $month, 'year' => $year]);

        $query = Perbaikan::query();

        // Perbaiki logika filter yang sama
        if ($month && $year) {
            $query->whereMonth('tanggal_perbaikan', $month)
                ->whereYear('tanggal_perbaikan', $year);
        } elseif ($month) {
            $query->whereMonth('tanggal_perbaikan', $month)
                ->whereYear('tanggal_perbaikan', date('Y')); // Default ke tahun ini
        } elseif ($year) {
            $query->whereYear('tanggal_perbaikan', $year);
        }

        $transaksi = $query->with(['user', 'pelanggan'])
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

        $teknisi = User::whereIn('role', ['teknisi', 'kepala teknisi'])->get();
        $teknisiStats = [];

        foreach ($teknisi as $t) {
            $querySelesai = Perbaikan::where('user_id', $t->id)->where('status', 'Selesai');
            $queryPending = Perbaikan::where('user_id', $t->id)->whereIn('status', ['Menunggu', 'Proses']);
            $incomeQuery = Perbaikan::where('user_id', $t->id)->where('status', 'Selesai');

            // Terapkan filter yang sama untuk teknisi stats
            if ($month && $year) {
                $querySelesai->whereMonth('tanggal_perbaikan', $month)
                    ->whereYear('tanggal_perbaikan', $year);
                $queryPending->whereMonth('tanggal_perbaikan', $month)
                    ->whereYear('tanggal_perbaikan', $year);
                $incomeQuery->whereMonth('tanggal_perbaikan', $month)
                    ->whereYear('tanggal_perbaikan', $year);
            } elseif ($month) {
                $querySelesai->whereMonth('tanggal_perbaikan', $month)
                    ->whereYear('tanggal_perbaikan', date('Y'));
                $queryPending->whereMonth('tanggal_perbaikan', $month)
                    ->whereYear('tanggal_perbaikan', date('Y'));
                $incomeQuery->whereMonth('tanggal_perbaikan', $month)
                    ->whereYear('tanggal_perbaikan', date('Y'));
            } elseif ($year) {
                $querySelesai->whereYear('tanggal_perbaikan', $year);
                $queryPending->whereYear('tanggal_perbaikan', $year);
                $incomeQuery->whereYear('tanggal_perbaikan', $year);
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

        $teknisiStats = collect($teknisiStats)->sortBy(function ($teknisi) {
            if (
                $teknisi['role'] === 'kepala teknisi' ||
                (isset($teknisi['jabatan']) && strtolower($teknisi['jabatan']) === 'kepala teknisi')
            ) {
                return 0;
            }
            return 1;
        })->values()->toArray();

        return view('kepala_toko.laporan', compact(
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

    // Fungsi lainnya tetap sama...
    public function show($id)
    {
        $user = Auth::user();
        $transaksi = Perbaikan::with(['user', 'pelanggan'])->findOrFail($id);

        return view('kepala_toko.detail_transaksi', compact('user', 'transaksi'));
    }

    public function dashboard(Request $request)
    {
        $user = Auth::user();

        $selectedYear = $request->input('year', date('Y'));
        $selectedMonth = $request->input('month', 'all');

        $karyawan = User::whereIn('role', ['admin', 'teknisi', 'kepala teknisi'])
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
        $monthlyRepairCounts = [];

        if ($selectedMonth === 'all') {
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

        $teknisiCount = User::whereIn('role', ['teknisi', 'kepala teknisi'])->count();

        if ($selectedMonth === 'all') {
            $latestTransaksi = Perbaikan::with(['user'])
                ->where('status', 'Selesai')
                ->whereYear('tanggal_perbaikan', $selectedYear)
                ->orderBy('tanggal_perbaikan', 'desc')
                ->take(3)
                ->get()
                ->map(function ($item) {
                    $item->tanggal_formatted = DateHelper::formatTanggalIndonesia($item->tanggal_perbaikan);
                    return $item;
                });

            $totalTransaksiBulanIni = Perbaikan::where('status', 'Selesai')
                ->whereYear('tanggal_perbaikan', $selectedYear)
                ->sum('harga');

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
                ->map(function ($item) {
                    $item->tanggal_formatted = DateHelper::formatTanggalIndonesia($item->tanggal_perbaikan);
                    return $item;
                });

            $totalTransaksiBulanIni = Perbaikan::where('status', 'Selesai')
                ->whereMonth('tanggal_perbaikan', $selectedMonth)
                ->whereYear('tanggal_perbaikan', $selectedYear)
                ->sum('harga');

            $repairsByTeknisi = Perbaikan::selectRaw('user_id, count(*) as count')
                ->where('status', 'Selesai')
                ->whereMonth('tanggal_perbaikan', $selectedMonth)
                ->whereYear('tanggal_perbaikan', $selectedYear)
                ->groupBy('user_id')
                ->get();
        }

        $currentYear = date('Y');
        $yearOptions = [];
        for ($year = $currentYear - 2; $year <= $currentYear + 2; $year++) {
            $yearOptions[] = $year;
        }

        $totalTransaksiHariIni = Perbaikan::where('status', 'Selesai')
            ->whereDate('tanggal_perbaikan', date('Y-m-d'))
            ->sum('harga');

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
