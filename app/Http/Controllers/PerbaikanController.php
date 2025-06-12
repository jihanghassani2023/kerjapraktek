<?php

namespace App\Http\Controllers;

use App\Models\Perbaikan;
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
            ->with('pelanggan')
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
        $perbaikan = Perbaikan::with('pelanggan')->findOrFail($id);

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
        $perbaikan = Perbaikan::with('pelanggan')->findOrFail($id);

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
        $perbaikan = Perbaikan::findOrFail($id);

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

        // Update allowed fields for technician
        $perbaikan->masalah = $request->masalah;
        $perbaikan->tindakan_perbaikan = $request->tindakan_perbaikan;
        $perbaikan->kategori_device = $request->kategori_device;
        $perbaikan->harga = $request->harga;
        $perbaikan->garansi = $request->garansi;
        $perbaikan->save();

        return redirect()->route('teknisi.dashboard')->with('success', 'Data perbaikan berhasil diperbarui');
    }

    /**
     * Update the status of a repair.
     */
    public function updateStatus(Request $request, $id)
    {
        $perbaikan = Perbaikan::findOrFail($id);
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

        // Update tindakan_perbaikan if provided
        if ($request->has('tindakan_perbaikan')) {
            $perbaikan->tindakan_perbaikan = $request->tindakan_perbaikan;
        }

        // Update harga if provided
        if ($request->has('harga')) {
            $perbaikan->harga = $request->harga;
        }

        // Add status change to proses_pengerjaan
        $currentProcess = $perbaikan->proses_pengerjaan ?? [];

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

        $perbaikan->proses_pengerjaan = $currentProcess;
        $perbaikan->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'status' => $perbaikan->status,
                'message' => "Status berhasil diperbarui dari {$currentStatus} menjadi {$perbaikan->status}",
                'id' => $perbaikan->id
            ]);
        }

        return redirect()->route('teknisi.dashboard')->with('success', 'Status berhasil diperbarui');
    }

    public function addProcessStep(Request $request, $id)
    {
        $perbaikan = Perbaikan::findOrFail($id);

        // Pastikan perbaikan milik teknisi yang login
        if ($perbaikan->user_id != Auth::id() && Auth::user()->role !== 'admin') {
            return redirect()->route('teknisi.dashboard')->with('error', 'Anda tidak memiliki akses');
        }

        // Validasi input
        $request->validate([
            'proses_step' => 'required|string|max:255',
        ]);

        // Tambahkan langkah proses baru
        $currentProcess = $perbaikan->proses_pengerjaan ?? [];
        $currentProcess[] = [
            'step' => $request->proses_step,
            'timestamp' => now()->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s')
        ];

        $perbaikan->proses_pengerjaan = $currentProcess;
        $perbaikan->save();

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

        // Query untuk mendapatkan data perbaikan selesai milik teknisi yang login
        $query = Perbaikan::where('user_id', $user->id)
            ->with('pelanggan')
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
     * Export laporan teknisi to Excel - SAMA SEPERTI TransaksiController::export()
     */
    public function exportLaporan(Request $request)
    {
        $user = Auth::user();

        // Get filter parameters
        $month = $request->input('month');
        $year = $request->input('year');

        // Query data based on filters - sama seperti di laporan()
        $query = Perbaikan::where('user_id', $user->id)
            ->with(['pelanggan'])
            ->where('status', 'Selesai');

        if ($month) {
            $query->whereMonth('tanggal_perbaikan', $month);
        }
        if ($year) {
            $query->whereYear('tanggal_perbaikan', $year);
        }

        $perbaikan = $query->orderBy('tanggal_perbaikan', 'desc')->get();

        // Create a new spreadsheet (sama seperti TransaksiController)
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Add headers
        $sheet->setCellValue('A1', 'No.');
        $sheet->setCellValue('B1', 'Kode Perbaikan');
        $sheet->setCellValue('C1', 'Tanggal');
        $sheet->setCellValue('D1', 'Device');
        $sheet->setCellValue('E1', 'Pelanggan');
        $sheet->setCellValue('F1', 'Masalah');
        $sheet->setCellValue('G1', 'Tindakan');
        $sheet->setCellValue('H1', 'Harga');
        $sheet->setCellValue('I1', 'Status');

        // Add data
        $row = 2;
        foreach ($perbaikan as $index => $p) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $p->id);
            $sheet->setCellValue('C' . $row, \Carbon\Carbon::parse($p->tanggal_perbaikan)->format('d M Y'));
            $sheet->setCellValue('D' . $row, $p->nama_device);
            $sheet->setCellValue('E' . $row, $p->pelanggan ? $p->pelanggan->nama_pelanggan : 'N/A');
            $sheet->setCellValue('F' . $row, $p->masalah);
            $sheet->setCellValue('G' . $row, $p->tindakan_perbaikan);
            $sheet->setCellValue('H' . $row, $p->harga);
            $sheet->setCellValue('I' . $row, $p->status);
            $row++;
        }

        // Create temporary file (sama seperti TransaksiController)
        $fileName = 'laporan_teknisi_' . str_replace(' ', '_', $user->name) . '_' . date('YmdHis') . '.xlsx';
        $filePath = storage_path('app/public/' . $fileName);

        // Save the spreadsheet
        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);

        // Download the file (sama seperti TransaksiController)
        return response()->download($filePath)->deleteFileAfterSend(true);
    }
}
