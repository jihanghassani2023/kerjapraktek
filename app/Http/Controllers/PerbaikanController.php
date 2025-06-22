<?php

namespace App\Http\Controllers;

use App\Models\Perbaikan;
use App\Models\DetailPerbaikan;
use App\Models\Pelanggan;
use App\Models\Garansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\DateHelper;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PerbaikanController extends Controller
{
    public function index()
    {
        $user = Auth::user();

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

        $perbaikan = Perbaikan::where('user_id', $user->id)
            ->with(['pelanggan', 'garansi'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                $item->tanggal_formatted = DateHelper::formatTanggalIndonesia($item->tanggal_perbaikan);
                return $item;
            });

        return view('teknisi.dashboard', compact('user', 'perbaikan', 'sedangMenunggu', 'sedangProses', 'perbaikanSelesaiHari', 'perbaikanSelesaiBulan'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $perbaikan = Perbaikan::findOrFail($id);
        if ($perbaikan->user_id != $user->id && !in_array($user->role, ['admin', 'kepala teknisi'])) {
            return redirect()->route('teknisi.dashboard')->with('error', 'Anda tidak memiliki akses');
        }
        $perbaikan->load(['pelanggan', 'garansi']);
        $processHistory = DetailPerbaikan::getProcessHistory($id);

        return view('teknisi.detail_perbaikan', compact(
            'user',
            'perbaikan',
            'processHistory'
        ));
    }

    public function edit($id)
    {
        $user = Auth::user();
        $perbaikan = Perbaikan::findOrFail($id);
        if ($perbaikan->user_id != $user->id && !in_array($user->role, ['admin', 'kepala teknisi'])) {
            return redirect()->route('teknisi.dashboard')->with('error', 'Anda tidak memiliki akses');
        }
        $perbaikan->load(['pelanggan', 'garansi']);

        return view('teknisi.edit_perbaikan', compact(
            'user',
            'perbaikan'
        ));
    }

    public function update(Request $request, $id)
    {
        $perbaikan = Perbaikan::findOrFail($id);

        if ($perbaikan->user_id != Auth::id() && !in_array(Auth::user()->role, ['admin', 'kepala teknisi'])) {
            return redirect()->route('teknisi.dashboard')->with('error', 'Anda tidak memiliki akses');
        }

        $validator = Validator::make($request->all(), [
            'masalah' => 'required|string',
            'tindakan_perbaikan' => 'required|string',
            'kategori_device' => 'required|string|max:50',
            'harga' => 'required|numeric',
            'garansi_items' => 'nullable|array',
            'garansi_items.*.sparepart' => 'nullable|string|max:100',
            'garansi_items.*.periode' => 'nullable|string|in:Tidak ada garansi,1 Bulan,12 Bulan',
        ], [
            'masalah.required' => 'Masalah wajib diisi.',
            'tindakan_perbaikan.required' => 'Tindakan perbaikan wajib diisi.',
            'kategori_device.required' => 'Kategori device wajib diisi.',
            'harga.required' => 'Harga wajib diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $newGaransiItems = [];
            if (!empty($request->garansi_items) && is_array($request->garansi_items)) {
                foreach ($request->garansi_items as $item) {
                    if (!empty(trim($item['sparepart'] ?? '')) && !empty(trim($item['periode'] ?? ''))) {
                        $newGaransiItems[] = [
                            'sparepart' => trim($item['sparepart']),
                            'periode' => trim($item['periode'])
                        ];
                    }
                }
            }
            $garansiChanged = $perbaikan->hasGaransiChanged($newGaransiItems);
            $perbaikan->update([
                'kategori_device' => $request->kategori_device,
                'masalah' => $request->masalah,
                'tindakan_perbaikan' => $request->tindakan_perbaikan,
                'harga' => $request->harga
            ]);
            if ($garansiChanged) {
                $perbaikan->syncGaransiItems($newGaransiItems);

                if (empty($newGaransiItems)) {
                    $messageText = 'Data perbaikan berhasil diperbarui. Semua garansi dihapus.';
                } else {
                    $garansiText = collect($newGaransiItems)
                        ->map(function ($item) {
                            return $item['sparepart'] . ': ' . $item['periode'];
                        })
                        ->implode('; ');

                    $messageText = 'Data perbaikan berhasil diperbarui dengan garansi: ' . $garansiText;
                }
            } else {
                $messageText = 'Data perbaikan berhasil diperbarui.';
            }

            return redirect()->route('teknisi.dashboard')
                ->with('success', $messageText);
        } catch (\Exception $e) {
            logger()->error('Error updating perbaikan: ' . $e->getMessage());

            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $perbaikan = Perbaikan::findOrFail($id);
        $currentStatus = $perbaikan->status;
        $newStatus = $request->status;
        if ($perbaikan->user_id != Auth::id() && !in_array(Auth::user()->role, ['admin', 'kepala teknisi'])) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Anda tidak memiliki akses'], 403);
            }
            return redirect()->route('teknisi.dashboard')->with('error', 'Anda tidak memiliki akses');
        }

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

        try {
            $updates = [];
            if ($request->has('tindakan_perbaikan') && !empty($request->tindakan_perbaikan)) {
                $updates['tindakan_perbaikan'] = $request->tindakan_perbaikan;
            }
            if ($request->has('harga') && !empty($request->harga)) {
                $updates['harga'] = $request->harga;
            }

            $perbaikan->status = $newStatus;
            if (!empty($updates)) {
                $perbaikan->fill($updates);
            }
            $perbaikan->save();
            $statusMessage = null;
            if ($currentStatus !== $newStatus) {
                if ($newStatus == 'Menunggu') {
                    $statusMessage = "Menunggu Antrian Perbaikan";
                } elseif ($newStatus == 'Proses') {
                    $statusMessage = "Device Anda Sedang diproses";
                } elseif ($newStatus == 'Selesai') {
                    $statusMessage = "Device Anda Telah Selesai";
                }
            }
            if ($statusMessage) {
                DetailPerbaikan::createProcessStep($perbaikan->id, $statusMessage);
            }
            if ($request->filled('proses_step')) {
                DetailPerbaikan::createProcessStep($perbaikan->id, $request->proses_step);
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'status' => $perbaikan->status,
                    'message' => "Status berhasil diperbarui dari {$currentStatus} menjadi {$perbaikan->status}",
                    'id' => $perbaikan->id
                ]);
            }

            return redirect()->route('teknisi.dashboard')->with('success', 'Status berhasil diperbarui');
        } catch (\Exception $e) {
            logger()->error('Error updating perbaikan status: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat mengubah status: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengubah status: ' . $e->getMessage());
        }
    }

    public function addProcessStep(Request $request, $id)
    {
        $perbaikan = Perbaikan::findOrFail($id);
        if ($perbaikan->user_id != Auth::id() && !in_array(Auth::user()->role, ['admin', 'kepala teknisi'])) {
            return redirect()->route('teknisi.dashboard')->with('error', 'Anda tidak memiliki akses');
        }
        $validator = Validator::make($request->all(), [
            'proses_step' => 'required|string|max:255',
        ], [
            'proses_step.required' => 'Proses step wajib diisi.',
            'proses_step.max' => 'Proses step maksimal 255 karakter.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DetailPerbaikan::createProcessStep($perbaikan->id, $request->proses_step);

            return redirect()->route('perbaikan.show', $id)
                ->with('success', 'Langkah proses pengerjaan berhasil ditambahkan.');
        } catch (\Exception $e) {
            logger()->error('Error adding process step: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menambahkan proses: ' . $e->getMessage());
        }
    }

    public function laporan(Request $request)
    {
        $user = Auth::user();
        $month = $request->input('month');
        $year = $request->input('year');

        $query = Perbaikan::where('user_id', $user->id)
            ->with(['pelanggan', 'garansi'])
            ->where('status', 'Selesai');
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

    public function exportLaporan(Request $request)
    {
        $user = Auth::user();
        $month = $request->input('month');
        $year = $request->input('year');
        $query = Perbaikan::where('user_id', $user->id)
            ->with(['pelanggan', 'garansi'])
            ->where('status', 'Selesai');

        if ($month) {
            $query->whereMonth('tanggal_perbaikan', $month);
        }

        if ($year) {
            $query->whereYear('tanggal_perbaikan', $year);
        }

        $perbaikan = $query->orderBy('tanggal_perbaikan', 'desc')->get();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getProperties()
            ->setCreator('MG Tech')
            ->setTitle('Laporan Perbaikan - ' . $user->name)
            ->setSubject('Laporan Perbaikan')
            ->setDescription('Laporan data perbaikan yang telah selesai');

        $sheet->setCellValue('A1', 'LAPORAN PERBAIKAN');
        $sheet->setCellValue('A2', 'Teknisi: ' . $user->name);
        $sheet->setCellValue('A3', 'Tanggal Export: ' . now()->format('d/m/Y H:i:s'));

        $filterInfo = 'Filter: ';
        if ($month && $year) {
            $monthNames = [
                1 => 'Januari',
                2 => 'Februari',
                3 => 'Maret',
                4 => 'April',
                5 => 'Mei',
                6 => 'Juni',
                7 => 'Juli',
                8 => 'Agustus',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Desember'
            ];
            $filterInfo .= $monthNames[$month] . ' ' . $year;
        } elseif ($month) {
            $monthNames = [
                1 => 'Januari',
                2 => 'Februari',
                3 => 'Maret',
                4 => 'April',
                5 => 'Mei',
                6 => 'Juni',
                7 => 'Juli',
                8 => 'Agustus',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Desember'
            ];
            $filterInfo .= 'Bulan ' . $monthNames[$month];
        } elseif ($year) {
            $filterInfo .= 'Tahun ' . $year;
        } else {
            $filterInfo .= 'Semua Data';
        }
        $sheet->setCellValue('A4', $filterInfo);

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
        $sheet->setCellValue('K6', 'Status');

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
        $sheet->getStyle('A6:K6')->applyFromArray($headerStyle);

        $row = 7;
        foreach ($perbaikan as $index => $p) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $p->id);
            $sheet->setCellValue('C' . $row, \Carbon\Carbon::parse($p->tanggal_perbaikan)->format('d/m/Y'));
            $sheet->setCellValue('D' . $row, $p->nama_device);
            $sheet->setCellValue('E' . $row, $p->kategori_device);
            $sheet->setCellValue('F' . $row, $p->pelanggan ? $p->pelanggan->nama_pelanggan : 'N/A');
            $sheet->setCellValue('G' . $row, $p->pelanggan ? $p->pelanggan->nomor_telp : 'N/A');
            $sheet->setCellValue('H' . $row, $p->masalah);
            $sheet->setCellValue('I' . $row, $p->tindakan_perbaikan);
            $sheet->setCellValue('J' . $row, 'Rp ' . number_format($p->harga, 0, ',', '.'));
            $sheet->setCellValue('K' . $row, $p->status);
            $row++;
        }

        if ($row > 7) {
            $dataRange = 'A7:K' . ($row - 1);
            $dataStyle = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ];
            $sheet->getStyle($dataRange)->applyFromArray($dataStyle);
        }

        foreach (range('A', 'K') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $summaryRow = $row + 1;
        $sheet->setCellValue('A' . $summaryRow, 'Total Perbaikan Selesai: ' . $perbaikan->count());
        $totalHarga = $perbaikan->sum('harga');
        $sheet->setCellValue('A' . ($summaryRow + 1), 'Total Pendapatan: Rp ' . number_format($totalHarga, 0, ',', '.'));
        $filename = 'laporan_perbaikan_' . strtolower(str_replace(' ', '_', $user->name)) . '_' . date('YmdHis') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');

        exit;
    }
}
