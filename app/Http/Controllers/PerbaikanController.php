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
     * Display a listing of the resource (Dashboard Teknisi).
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

        // FIXED: Get all repairs with current data only
        $perbaikan = Perbaikan::where('user_id', $user->id)
            ->with(['pelanggan'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                $item->tanggal_formatted = DateHelper::formatTanggalIndonesia($item->tanggal_perbaikan);
                // Load current detail for each perbaikan
                $item->current_detail = $item->getCurrentDetail();
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
        $perbaikan = Perbaikan::findOrFail($id);

        // Make sure the repair belongs to the logged-in user or user is admin
        if ($perbaikan->user_id != $user->id && !in_array($user->role, ['admin', 'kepala teknisi'])) {
            return redirect()->route('teknisi.dashboard')->with('error', 'Anda tidak memiliki akses');
        }

        // FIXED: Load current data only
        $perbaikan->load(['pelanggan']);

        // Get current detail and garansi separately
        $currentDetail = $perbaikan->getCurrentDetail();
        $currentGaransiItems = $perbaikan->getCurrentGaransiItems();

        // Get process history (untuk timeline)
        $processHistory = DetailPerbaikan::getProcessHistory($id);

        return view('teknisi.detail_perbaikan', compact(
            'user',
            'perbaikan',
            'currentDetail',
            'currentGaransiItems',
            'processHistory'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = Auth::user();
        $perbaikan = Perbaikan::findOrFail($id);

        // Make sure the repair belongs to the logged-in user or user is admin
        if ($perbaikan->user_id != $user->id && !in_array($user->role, ['admin', 'kepala teknisi'])) {
            return redirect()->route('teknisi.dashboard')->with('error', 'Anda tidak memiliki akses');
        }

        // FIXED: Load current data only for editing
        $perbaikan->load(['pelanggan']);

        // Get current detail and garansi for editing
        $currentDetail = $perbaikan->getCurrentDetail();
        $currentGaransiItems = $perbaikan->getCurrentGaransiItems();

        return view('teknisi.edit_perbaikan', compact(
            'user',
            'perbaikan',
            'currentDetail',
            'currentGaransiItems'
        ));
    }

    /**
     * Update the specified resource in storage.
     * FLEXIBLE: User can delete any amount of garansi (some, all, or add more)
     */
    public function update(Request $request, $id)
    {
        $perbaikan = Perbaikan::findOrFail($id);

        // Make sure the repair belongs to the logged-in user or user is admin
        if ($perbaikan->user_id != Auth::id() && !in_array(Auth::user()->role, ['admin', 'kepala teknisi'])) {
            return redirect()->route('teknisi.dashboard')->with('error', 'Anda tidak memiliki akses');
        }

        // FLEXIBLE: Validasi yang sangat fleksibel
        $validator = Validator::make($request->all(), [
            'masalah' => 'required|string',
            'tindakan_perbaikan' => 'required|string',
            'kategori_device' => 'required|string|max:50',
            'harga' => 'required|numeric',
            'garansi_items' => 'nullable|array', // Bisa kosong, bisa ada
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
            // FLEXIBLE: Process garansi items - filter hanya yang valid
            $newGaransiItems = [];

            if (!empty($request->garansi_items) && is_array($request->garansi_items)) {
                foreach ($request->garansi_items as $item) {
                    // Hanya tambahkan jika kedua field diisi
                    if (!empty(trim($item['sparepart'] ?? '')) && !empty(trim($item['periode'] ?? ''))) {
                        $newGaransiItems[] = [
                            'sparepart' => trim($item['sparepart']),
                            'periode' => trim($item['periode'])
                        ];
                    }
                }
            }

            // FIXED: Get current data using latest records method
            $currentDetail = $perbaikan->getCurrentDetail();
            $garansiChanged = $perbaikan->hasGaransiChanged($newGaransiItems);

            // Prepare main data
            $mainData = [
                'nama_device' => $currentDetail->nama_device,
                'kategori_device' => $request->kategori_device,
                'masalah' => $request->masalah,
                'tindakan_perbaikan' => $request->tindakan_perbaikan,
                'harga' => $request->harga
            ];

            if ($garansiChanged) {
                // FLEXIBLE: Handle berbagai skenario garansi
                if (empty($newGaransiItems)) {
                    // SKENARIO 1: User hapus SEMUA garansi
                    // Buat 1 record dengan garansi kosong/null
                    $defaultGaransiItem = [
                        ['sparepart' => null, 'periode' => null]
                    ];

                    DetailPerbaikan::createPerbaikanRecordsFlexible(
                        $perbaikan->id,
                        $mainData,
                        $defaultGaransiItem,
                        null // Tidak ada process step message
                    );

                    $messageText = 'Data perbaikan berhasil diperbarui. Semua garansi dihapus.';
                } else {
                    // SKENARIO 2: User ubah/hapus sebagian/tambah garansi
                    DetailPerbaikan::createPerbaikanRecordsFlexible(
                        $perbaikan->id,
                        $mainData,
                        $newGaransiItems,
                        null // Tidak ada process step message
                    );

                    $garansiText = collect($newGaransiItems)
                        ->map(function ($item) {
                            return $item['sparepart'] . ': ' . $item['periode'];
                        })
                        ->implode('; ');

                    $messageText = 'Data perbaikan berhasil diperbarui dengan garansi: ' . $garansiText;
                }
            } else {
                // SKENARIO 3: Garansi tidak berubah, update data lain saja
                // FIXED: Update only latest records, not all records
                $latestRecords = DetailPerbaikan::getLatestRecords($perbaikan->id);

                foreach ($latestRecords as $record) {
                    $record->update([
                        'kategori_device' => $request->kategori_device,
                        'masalah' => $request->masalah,
                        'tindakan_perbaikan' => $request->tindakan_perbaikan,
                        'harga' => $request->harga,
                        'updated_at' => now()
                    ]);
                }

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

    /**
     * Update the status of a repair.
     * FIXED: Use latest records only
     */
    /**
     * Update the status of a repair.
     * FIXED: Always use current garansi state, never old garansi
     */
    public function updateStatus(Request $request, $id)
    {
        $perbaikan = Perbaikan::findOrFail($id);
        $currentStatus = $perbaikan->status;
        $newStatus = $request->status;

        // Make sure the repair belongs to the logged-in user or user is admin
        if ($perbaikan->user_id != Auth::id() && !in_array(Auth::user()->role, ['admin', 'kepala teknisi'])) {
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

        try {
            // Update status
            $perbaikan->status = $newStatus;
            $perbaikan->save();

            // Prepare updates
            $updates = [];
            if ($request->has('tindakan_perbaikan') && !empty($request->tindakan_perbaikan)) {
                $updates['tindakan_perbaikan'] = $request->tindakan_perbaikan;
            }
            if ($request->has('harga') && !is_null($request->harga)) {
                $updates['harga'] = $request->harga;
            }

            // Add status change message HANYA untuk perubahan status yang signifikan
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

            // FIXED: Always use current state, never add old garansi back
            if (!empty($updates) || $statusMessage) {
                // Get current detail and current garansi
                $currentDetail = $perbaikan->getCurrentDetail();
                $currentGaransiItems = $perbaikan->getCurrentGaransiItems();

                // Prepare main data
                $mainData = [
                    'nama_device' => $currentDetail->nama_device,
                    'kategori_device' => $currentDetail->kategori_device,
                    'masalah' => $updates['tindakan_perbaikan'] ?? $currentDetail->masalah,
                    'tindakan_perbaikan' => $updates['tindakan_perbaikan'] ?? $currentDetail->tindakan_perbaikan,
                    'harga' => $updates['harga'] ?? $currentDetail->harga
                ];

                // CRITICAL FIX: Use current garansi state
                if ($currentGaransiItems->count() > 0) {
                    // Ada garansi current, gunakan garansi current
                    $garansiArray = $currentGaransiItems->map(function ($item) {
                        return [
                            'sparepart' => $item->garansi_sparepart,
                            'periode' => $item->garansi_periode
                        ];
                    })->toArray();

                    DetailPerbaikan::createPerbaikanRecordsFlexible(
                        $perbaikan->id,
                        $mainData,
                        $garansiArray,
                        $statusMessage
                    );
                } else {
                    // Tidak ada garansi current, buat dengan garansi null
                    DetailPerbaikan::createPerbaikanRecordsFlexible(
                        $perbaikan->id,
                        $mainData,
                        [['sparepart' => null, 'periode' => null]],
                        $statusMessage
                    );
                }
            }

            // Add custom process step if provided
            if ($request->filled('proses_step')) {
                // FIXED: Also use current garansi state for process step
                $currentDetail = $perbaikan->getCurrentDetail();
                $currentGaransiItems = $perbaikan->getCurrentGaransiItems();

                $mainData = [
                    'nama_device' => $currentDetail->nama_device,
                    'kategori_device' => $currentDetail->kategori_device,
                    'masalah' => $currentDetail->masalah,
                    'tindakan_perbaikan' => $currentDetail->tindakan_perbaikan,
                    'harga' => $currentDetail->harga
                ];

                if ($currentGaransiItems->count() > 0) {
                    $garansiArray = $currentGaransiItems->map(function ($item) {
                        return [
                            'sparepart' => $item->garansi_sparepart,
                            'periode' => $item->garansi_periode
                        ];
                    })->toArray();

                    DetailPerbaikan::createPerbaikanRecordsFlexible(
                        $perbaikan->id,
                        $mainData,
                        $garansiArray,
                        $request->proses_step
                    );
                } else {
                    DetailPerbaikan::createPerbaikanRecordsFlexible(
                        $perbaikan->id,
                        $mainData,
                        [['sparepart' => null, 'periode' => null]],
                        $request->proses_step
                    );
                }
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

    /**
     * Add process step to repair workflow
     * FIXED: Use current garansi state
     */
    /**
 * Add process step to repair workflow
 * FIXED: Always use current garansi state
 */
public function addProcessStep(Request $request, $id)
{
    $perbaikan = Perbaikan::findOrFail($id);

    // Pastikan perbaikan milik teknisi yang login atau admin
    if ($perbaikan->user_id != Auth::id() && !in_array(Auth::user()->role, ['admin', 'kepala teknisi'])) {
        return redirect()->route('teknisi.dashboard')->with('error', 'Anda tidak memiliki akses');
    }

    // Validasi input
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
        // FIXED: Get current state and use it
        $currentDetail = $perbaikan->getCurrentDetail();
        $currentGaransiItems = $perbaikan->getCurrentGaransiItems();

        // Prepare main data from current state
        $mainData = [
            'nama_device' => $currentDetail->nama_device,
            'kategori_device' => $currentDetail->kategori_device,
            'masalah' => $currentDetail->masalah,
            'tindakan_perbaikan' => $currentDetail->tindakan_perbaikan,
            'harga' => $currentDetail->harga
        ];

        // CRITICAL FIX: Use current garansi state only
        if ($currentGaransiItems->count() > 0) {
            // Ada garansi current, gunakan garansi current
            $garansiArray = $currentGaransiItems->map(function($item) {
                return [
                    'sparepart' => $item->garansi_sparepart,
                    'periode' => $item->garansi_periode
                ];
            })->toArray();

            DetailPerbaikan::createPerbaikanRecordsFlexible(
                $perbaikan->id,
                $mainData,
                $garansiArray,
                $request->proses_step
            );
        } else {
            // Tidak ada garansi current, buat dengan garansi null
            DetailPerbaikan::createPerbaikanRecordsFlexible(
                $perbaikan->id,
                $mainData,
                [['sparepart' => null, 'periode' => null]],
                $request->proses_step
            );
        }

        return redirect()->route('perbaikan.show', $id)
            ->with('success', 'Langkah proses pengerjaan berhasil ditambahkan.');

    } catch (\Exception $e) {
        logger()->error('Error adding process step: ' . $e->getMessage());

        return redirect()->back()
            ->with('error', 'Terjadi kesalahan saat menambahkan proses: ' . $e->getMessage());
    }
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
            $sheet->setCellValue('K' . $row, $p->garansi ?: 'N/A');
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
        $totalHarga = $perbaikan->sum(function ($item) {
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
