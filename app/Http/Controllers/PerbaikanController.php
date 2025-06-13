<?php

namespace App\Http\Controllers;

use App\Models\Perbaikan;
use App\Models\DetailPerbaikan;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\DateHelper;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PerbaikanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        // Get counts for dashboard
        $sedangMenunggu = Perbaikan::where('user_id', $user->id)
            ->where('status', 'Menunggu')
            ->count();

        $sedangProses = Perbaikan::where('user_id', $user->id)
            ->where('status', 'Proses')
            ->count();

        $perbaikanSelesaiHari = Perbaikan::where('user_id', $user->id)
            ->where('status', 'Selesai')
            ->whereDate('tanggal_perbaikan', date('Y-m-d'))
            ->count();

        $perbaikanSelesaiBulan = Perbaikan::where('user_id', $user->id)
            ->where('status', 'Selesai')
            ->whereMonth('tanggal_perbaikan', date('m'))
            ->whereYear('tanggal_perbaikan', date('Y'))
            ->count();

        // Get all repairs assigned to this technician
        $perbaikan = Perbaikan::where('user_id', $user->id)
            ->with(['pelanggan', 'detail'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                $item->tanggal_formatted = DateHelper::formatTanggalIndonesia($item->tanggal_perbaikan);
                return $item;
            });
        return view('teknisi.dashboard', compact('user', 'perbaikan', 'sedangMenunggu', 'sedangProses', 'perbaikanSelesaiHari', 'perbaikanSelesaiBulan'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = Auth::user();
        $perbaikan = Perbaikan::with(['pelanggan', 'detail'])->findOrFail($id);

        // Make sure the repair belongs to the logged-in user
        if ($perbaikan->user_id != $user->id && $user->role !== 'admin') {
            return redirect()->route('teknisi.dashboard')->with('error', 'Anda tidak memiliki akses');
        }

        return view('teknisi.detail_perbaikan', compact('user', 'perbaikan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = Auth::user();
        $perbaikan = Perbaikan::with(['pelanggan', 'detail'])->findOrFail($id);

        // Make sure the repair belongs to the logged-in user
        if ($perbaikan->user_id != $user->id && $user->role !== 'admin') {
            return redirect()->route('teknisi.dashboard')->with('error', 'Anda tidak memiliki akses');
        }

        return view('teknisi.edit_perbaikan', compact('user', 'perbaikan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $perbaikan = Perbaikan::with('detail')->findOrFail($id);

        // Make sure the repair belongs to the logged-in user
        if ($perbaikan->user_id != Auth::id() && Auth::user()->role !== 'admin') {
            return redirect()->route('teknisi.dashboard')->with('error', 'Anda tidak memiliki akses');
        }

        // Validate form input
        $validator = Validator::make($request->all(), [
            'masalah' => 'required|string',
            'tindakan_perbaikan' => 'required|string',
            'kategori_device' => 'required|string|max:50',
            'harga' => 'required|numeric',
            'garansi' => 'required|string',

        ], [
            'masalah.required' => 'Masalah wajib diisi.',
            'tindakan_perbaikan.required' => 'Tindakan perbaikan wajib diisi.',
            'kategori_device.required' => 'Kategori device wajib diisi.',
            'status.required' => 'Status wajib diisi.',
            'status.in' => 'Status tidak valid.',
            'harga.required' => 'Harga wajib diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'garansi.required' => 'Garansi wajib diisi.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update detail perbaikan
        $perbaikan->detail->update([
            'masalah' => $request->masalah,
            'tindakan_perbaikan' => $request->tindakan_perbaikan,
            'kategori_device' => $request->kategori_device,
            'harga' => $request->harga,
            'garansi' => $request->garansi,
        ]);

        return redirect()->route('teknisi.dashboard')->with('success', 'Data perbaikan berhasil diperbarui');
    }

    /**
     * Update the status of a repair.
     */

    public function updateStatus(Request $request, $id)
    {
        $perbaikan = Perbaikan::with('detail')->findOrFail($id);
        $currentStatus = $perbaikan->status;
        $newStatus = $request->status;

        // Make sure the repair belongs to the logged-in user or user is admin
        if ($perbaikan->user_id != Auth::id() && Auth::user()->role !== 'admin') {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Anda tidak memiliki akses'], 403);
            }
            return redirect()->route('teknisi.dashboard')->with('error', 'Anda tidak memiliki akses');
        }

        // Validate status
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:Menunggu,Proses,Selesai',
            'tindakan_perbaikan' => 'nullable|string',
            'harga' => 'nullable|numeric',
            'proses_step' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Prevent invalid status transitions
        if (
            $currentStatus == 'Selesai' ||
            ($currentStatus == 'Proses' && $newStatus == 'Menunggu')
        ) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => "Tidak dapat mengubah status dari {$currentStatus} menjadi {$newStatus}"
                ], 422);
            }
            return redirect()->back()->with('error', "Tidak dapat mengubah status dari {$currentStatus} menjadi {$newStatus}");
        }

        // Update status
        $perbaikan->status = $newStatus;
        $perbaikan->save();

        // Update tindakan_perbaikan if provided
        if ($request->has('tindakan_perbaikan') && $perbaikan->detail) {
            $perbaikan->detail->update(['tindakan_perbaikan' => $request->tindakan_perbaikan]);
        }

        // Update harga if provided
        if ($request->has('harga') && $perbaikan->detail) {
            $perbaikan->detail->update(['harga' => $request->harga]);
        }

        // Add status change to proses_pengerjaan
        $currentProcess = $perbaikan->detail->proses_pengerjaan ?? [];

        // Add status change entry
        $statusMessage = "";
        if ($newStatus == 'Menunggu') {
            $statusMessage = "Menunggu Antrian Perbaikan";
        } elseif ($newStatus == 'Proses') {
            $statusMessage = "Device Anda Sedang diproses";
        } elseif ($newStatus == 'Selesai') {
            $statusMessage = "Device Anda Telah Selesai";
        }

        $currentProcess[] = [
            'step' => $statusMessage,
            'timestamp' => now()->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s')
        ];

        // Add custom process step if provided
        if ($request->filled('proses_step')) {
            $currentProcess[] = [
                'step' => $request->proses_step,
                'timestamp' => now()->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s')
            ];
        }

        $perbaikan->detail->update(['proses_pengerjaan' => $currentProcess]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'status' => $perbaikan->status,
                'message' => "Status berhasil diperbarui dari {$currentStatus} menjadi {$perbaikan->status}",
                'id' => $perbaikan->id
            ]);
        }

        return redirect()->route('teknisi.progress')->with('success', 'Status berhasil diperbarui');
    }

    public function addProcessStep(Request $request, $id)
    {
        $perbaikan = Perbaikan::with('detail')->findOrFail($id);

        // Pastikan perbaikan milik teknisi yang login
        if ($perbaikan->user_id != Auth::id() && Auth::user()->role !== 'admin') {
            return redirect()->route('teknisi.dashboard')->with('error', 'Anda tidak memiliki akses');
        }

        // Validasi input
        $request->validate([
            'proses_step' => 'required|string|max:255',
        ]);

        // Tambahkan langkah proses baru
        $currentProcess = $perbaikan->detail->proses_pengerjaan ?? [];
        $currentProcess[] = [
            'step' => $request->proses_step,
            'timestamp' => now()->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s')
        ];

        $perbaikan->detail->update(['proses_pengerjaan' => $currentProcess]);

        return redirect()->route('perbaikan.show', $id)
            ->with('success', 'Langkah proses pengerjaan berhasil ditambahkan.');
    }

    /**
     * Show the laporan view.
     */
    public function laporan(Request $request)
    {
        $user = Auth::user();

        // Get filter parameters
        $month = $request->input('month');
        $year = $request->input('year');

        // Base query for completed repairs by this technician
        $query = Perbaikan::where('user_id', $user->id)
            ->with(['pelanggan', 'detail'])
            ->where('status', 'Selesai');

        // Apply filters if provided
        if ($month) {
            $query->whereMonth('tanggal_perbaikan', $month);
        }

        if ($year) {
            $query->whereYear('tanggal_perbaikan', $year);
        }

        $perbaikan = $query->orderBy('tanggal_perbaikan', 'desc')
            ->get()
            ->map(function ($item) {
                $item->tanggal_formatted = DateHelper::formatTanggalIndonesia($item->tanggal_perbaikan);
                return $item;
            });

        return view('teknisi.laporan', compact('user', 'perbaikan'));
    }

    /**
     * Export laporan to Excel file.
     */
    public function exportLaporan(Request $request)
    {
        $user = Auth::user();

        // Get filter parameters
        $month = $request->input('month');
        $year = $request->input('year');

        // Base query for completed repairs by this technician
        $query = Perbaikan::where('user_id', $user->id)
            ->with(['pelanggan', 'detail'])
            ->where('status', 'Selesai');

        // Apply filters if provided
        if ($month) {
            $query->whereMonth('tanggal_perbaikan', $month);
        }

        if ($year) {
            $query->whereYear('tanggal_perbaikan', $year);
        }

        $perbaikan = $query->orderBy('tanggal_perbaikan', 'desc')->get();

        // Create a new spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator('MG Tech')
            ->setTitle('Laporan Perbaikan - ' . $user->name)
            ->setSubject('Laporan Perbaikan')
            ->setDescription('Laporan data perbaikan yang telah selesai');

        // Add headers
        $sheet->setCellValue('A1', 'LAPORAN PERBAIKAN');
        $sheet->setCellValue('A2', 'Teknisi: ' . $user->name);
        $sheet->setCellValue('A3', 'Tanggal Export: ' . now()->format('d/m/Y H:i:s'));

        // Filter info
        $filterInfo = 'Filter: ';
        if ($month && $year) {
            $monthNames = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];
            $filterInfo .= $monthNames[$month] . ' ' . $year;
        } elseif ($month) {
            $monthNames = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];
            $filterInfo .= 'Bulan ' . $monthNames[$month];
        } elseif ($year) {
            $filterInfo .= 'Tahun ' . $year;
        } else {
            $filterInfo .= 'Semua Data';
        }
        $sheet->setCellValue('A4', $filterInfo);

        // Add table headers
        $sheet->setCellValue('A6', 'No.');
        $sheet->setCellValue('B6', 'Kode Perbaikan');
        $sheet->setCellValue('C6', 'Tanggal');
        $sheet->setCellValue('D6', 'Device');
        $sheet->setCellValue('E6', 'Kategori');
        $sheet->setCellValue('F6', 'Pelanggan');
        $sheet->setCellValue('G6', 'No. Telepon');
        $sheet->setCellValue('H6', 'Masalah');
        $sheet->setCellValue('I6', 'Tindakan');
        $sheet->setCellValue('J6', 'Harga');
        $sheet->setCellValue('K6', 'Garansi');
        $sheet->setCellValue('L6', 'Status');

        // Style the headers
        $headerStyle = [
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E2E8F0']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('A6:L6')->applyFromArray($headerStyle);

        // Add data
        $row = 7;
        foreach ($perbaikan as $index => $p) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $p->id);
            $sheet->setCellValue('C' . $row, \Carbon\Carbon::parse($p->tanggal_perbaikan)->format('d/m/Y'));
            $sheet->setCellValue('D' . $row, $p->detail ? $p->detail->nama_device : 'N/A');
            $sheet->setCellValue('E' . $row, $p->detail ? $p->detail->kategori_device : 'N/A');
            $sheet->setCellValue('F' . $row, $p->pelanggan ? $p->pelanggan->nama_pelanggan : 'N/A');
            $sheet->setCellValue('G' . $row, $p->pelanggan ? $p->pelanggan->nomor_telp : 'N/A');
            $sheet->setCellValue('H' . $row, $p->detail ? $p->detail->masalah : 'N/A');
            $sheet->setCellValue('I' . $row, $p->detail ? $p->detail->tindakan_perbaikan : 'N/A');
            $sheet->setCellValue('J' . $row, $p->detail ? 'Rp ' . number_format($p->detail->harga, 0, ',', '.') : 'Rp 0');
            $sheet->setCellValue('K' . $row, $p->detail ? $p->detail->garansi : 'N/A');
            $sheet->setCellValue('L' . $row, $p->status);
            $row++;
        }

        // Style the data rows
        if ($row > 7) {
            $dataRange = 'A7:L' . ($row - 1);
            $dataStyle = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ];
            $sheet->getStyle($dataRange)->applyFromArray($dataStyle);
        }

        // Auto-size columns
        foreach (range('A', 'L') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Add summary
        $summaryRow = $row + 1;
        $sheet->setCellValue('A' . $summaryRow, 'Total Perbaikan Selesai: ' . $perbaikan->count());
        $totalHarga = $perbaikan->sum(function($item) {
            return $item->detail ? $item->detail->harga : 0;
        });
        $sheet->setCellValue('A' . ($summaryRow + 1), 'Total Pendapatan: Rp ' . number_format($totalHarga, 0, ',', '.'));

        // Generate filename
        $filename = 'laporan_perbaikan_' . strtolower(str_replace(' ', '_', $user->name)) . '_' . date('YmdHis') . '.xlsx';

        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        // Create writer and output file
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');

        exit;
    }
}
