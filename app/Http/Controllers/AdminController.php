<?php

namespace App\Http\Controllers;

use App\Models\Perbaikan;
use App\Models\DetailPerbaikan;
use App\Models\User;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Helpers\DateHelper;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = Auth::user();

        $totalTeknisi = User::whereIn('role', ['teknisi', 'kepala teknisi'])->count();

        $totalTransaksiHariIni = Perbaikan::where('status', 'Selesai')
            ->whereDate('tanggal_perbaikan', date('Y-m-d'))
            ->sum('harga');

        $totalTransaksiBulanIni = Perbaikan::where('status', 'Selesai')
            ->whereMonth('tanggal_perbaikan', date('m'))
            ->whereYear('tanggal_perbaikan', date('Y'))
            ->sum('harga');

        if ($request->has('search') && $request->search) {
            return redirect()->route('admin.search', ['search' => $request->search]);
        }

        $latestTransaksi = Perbaikan::with(['user', 'pelanggan'])
            ->orderBy('created_at', 'desc')
            ->take(30)
            ->get()
            ->map(function ($item) {
                $item->tanggal_formatted = DateHelper::formatTanggalIndonesia($item->tanggal_perbaikan);
                return $item;
            });

        return view('admin.dashboard', compact(
            'user',
            'totalTeknisi',
            'totalTransaksiHariIni',
            'totalTransaksiBulanIni',
            'latestTransaksi'
        ));
    }

    public function search(Request $request)
    {
        try {
            $search = $request->get('search');
            $user = Auth::user();

            if (empty($search)) {
                return redirect()->back()->with('error', 'Kata kunci pencarian tidak boleh kosong.');
            }

            $perbaikan = Perbaikan::with(['user', 'pelanggan'])
                ->where(function ($query) use ($search) {
                    $query->where('id', 'LIKE', "%{$search}%")
                        ->orWhere('nama_device', 'LIKE', "%{$search}%")
                        ->orWhereHas('pelanggan', function ($q) use ($search) {
                            $q->where('nama_pelanggan', 'LIKE', "%{$search}%");
                        });
                })
                ->orderBy('created_at', 'desc')
                ->get();

            $perbaikan = $perbaikan->map(function ($item) {
                $item->tanggal_formatted = DateHelper::formatTanggalIndonesia($item->tanggal_perbaikan);
                return $item;
            });

            return view('search_results', compact('perbaikan', 'search', 'user'));
        } catch (\Exception $e) {
            Log::error('Search error: ' . $e->getMessage(), [
                'search_term' => $request->get('search'),
                'user_id' => Auth::id(),
                'stack_trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat melakukan pencarian. Silakan coba lagi.');
        }
    }

    public function transaksi(Request $request)
    {
        $user = Auth::user();
        $month = $request->input('month');
        $year = $request->input('year');
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

            if ($month) {
                $querySelesai->whereMonth('tanggal_perbaikan', $month);
                $queryPending->whereMonth('tanggal_perbaikan', $month);
                $incomeQuery->whereMonth('tanggal_perbaikan', $month);
            }

            if ($year) {
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

        $currentYear = date('Y');
        $yearOptions = [];
        for ($year = $currentYear - 5; $year <= $currentYear + 2; $year++) {
            $yearOptions[] = $year;
        }

        return view('admin.transaksi', compact(
            'user',
            'transaksi',
            'totalTransaksi',
            'totalTransaksiHariIni',
            'totalTransaksiBulanIni',
            'teknisiStats',
            'month',
            'year',
            'yearOptions'
        ));
    }

    public function showTransaksi($id)
    {
        $user = Auth::user();
        $transaksi = Perbaikan::with(['user', 'pelanggan', 'garansi'])->findOrFail($id);

        return view('admin.detail_transaksi', compact('user', 'transaksi'));
    }

    public function updateStatus(Request $request, $id)
    {
        $perbaikan = Perbaikan::findOrFail($id);
        $currentStatus = $perbaikan->status;
        $newStatus = $request->status;

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:Menunggu,Proses,Selesai',
            'tindakan_perbaikan' => 'nullable|string',
            'harga' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
            }
            return redirect()->back()->withErrors($validator);
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

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'status' => $perbaikan->status,
                    'message' => "Status berhasil diperbarui dari {$currentStatus} menjadi {$perbaikan->status}"
                ]);
            }

            return redirect()->route('admin.transaksi.show', $id)
                ->with('success', 'Status berhasil diperbarui');
        } catch (\Exception $e) {
            logger()->error('Error updating status: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat mengubah status: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengubah status: ' . $e->getMessage());
        }
    }


    public function pelanggan()
    {
        $user = Auth::user();
        $pelanggan = Pelanggan::all();
        return view('admin.pelanggan', compact('user', 'pelanggan'));
    }

    public function createPelanggan()
    {
        $user = Auth::user();
        return view('admin.tambah_pelanggan', compact('user'));
    }

    public function storePelanggan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_pelanggan' => 'required|string|max:50',
            'nomor_telp' => [
                'required',
                'string',
                'max:13',
                'regex:/^[0-9]+$/',
                'unique:pelanggan,nomor_telp'
            ],
            'email' => 'nullable|email|max:100|unique:pelanggan,email',
        ], [
            'nama_pelanggan.required' => 'Nama pelanggan wajib diisi.',
            'nomor_telp.required' => 'Nomor telepon wajib diisi.',
            'nomor_telp.max' => 'Nomor telepon maksimal 13 digit.',
            'nomor_telp.regex' => 'Nomor telepon hanya boleh berisi angka.',
            'nomor_telp.unique' => 'Nomor telepon sudah terdaftar. Gunakan nomor lain.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar. Gunakan email lain.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $pelanggan = Pelanggan::create([
            'nama_pelanggan' => $request->nama_pelanggan,
            'nomor_telp' => $request->nomor_telp,
            'email' => $request->email,
        ]);

        return redirect()->route('admin.pelanggan')
            ->with('success', 'Data pelanggan berhasil ditambahkan');
    }

    public function editPelanggan($id)
    {
        $user = Auth::user();
        $pelanggan = Pelanggan::findOrFail($id);
        return view('admin.edit_pelanggan', compact('user', 'pelanggan'));
    }

    public function updatePelanggan(Request $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_pelanggan' => 'required|string|max:50',
            'nomor_telp' => [
                'required',
                'string',
                'max:13',
                'regex:/^[0-9]+$/',
                'unique:pelanggan,nomor_telp,' . $id
            ],
            'email' => [
                'nullable',
                'email',
                'max:100',
                'unique:pelanggan,email,' . $id
            ],
        ], [
            'nama_pelanggan.required' => 'Nama pelanggan wajib diisi.',
            'nomor_telp.required' => 'Nomor telepon wajib diisi.',
            'nomor_telp.max' => 'Nomor telepon maksimal 13 digit.',
            'nomor_telp.regex' => 'Nomor telepon hanya boleh berisi angka.',
            'nomor_telp.unique' => 'Nomor telepon sudah terdaftar. Gunakan nomor lain.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar. Gunakan email lain.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $pelanggan->update([
            'nama_pelanggan' => $request->nama_pelanggan,
            'nomor_telp' => $request->nomor_telp,
            'email' => $request->email,
        ]);

        return redirect()->route('admin.pelanggan')
            ->with('success', 'Data pelanggan berhasil diperbarui');
    }

    public function getCustomers()
    {
        $customers = Pelanggan::select('id', 'nama_pelanggan', 'nomor_telp', 'email')->get();
        return response()->json($customers);
    }

    public function destroyPelanggan($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        $hasPerbaikan = Perbaikan::where('pelanggan_id', $id)->exists();

        if ($hasPerbaikan) {
            return redirect()->route('admin.pelanggan')
                ->with('error', 'Pelanggan tidak dapat dihapus karena memiliki data perbaikan');
        }

        $pelanggan->delete();

        return redirect()->route('admin.pelanggan')
            ->with('success', 'Data pelanggan berhasil dihapus');
    }


    public function createPerbaikan()
    {
        $user = Auth::user();
        $pelanggan = Pelanggan::all();
        $teknisi = User::whereIn('role', ['teknisi', 'kepala teknisi'])
            ->orderBy('jabatan', 'desc')
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.tambah_perbaikan', compact('user', 'pelanggan', 'teknisi'));
    }

public function exportTransaksi(Request $request)
{
    $month = $request->input('month');
    $year = $request->input('year');

    Log::info('Export parameters:', ['month' => $month, 'year' => $year]);

    $query = Perbaikan::query();

    if ($month && $year) {
        $query->whereMonth('tanggal_perbaikan', $month)->whereYear('tanggal_perbaikan', $year);
    } elseif ($month) {
        $query->whereMonth('tanggal_perbaikan', $month)->whereYear('tanggal_perbaikan', date('Y'));
    } elseif ($year) {
        $query->whereYear('tanggal_perbaikan', $year);
    }

    // Include relasi user, pelanggan, garansi
    $transaksi = $query->with(['user', 'pelanggan', 'garansi'])->orderBy('tanggal_perbaikan', 'desc')->get();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // === Judul & Filter Info ===
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
        $filterText .= $namaBulan[(int)$month] . ' ' . date('Y');
    } elseif ($year) {
        $filterText .= $year;
    } else {
        $filterText .= 'Semua Data';
    }

    $sheet->setCellValue('A2', $filterText);
    $sheet->mergeCells('A2:K2');

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
        // Format garansi: sparepart (periode), ...
        $garansiText = $t->garansi->map(function ($g) {
            return $g->sparepart . ' (' . $g->periode . ')';
        })->implode(', ');

        $sheet->setCellValue('A' . $row, $index + 1);
        $sheet->setCellValue('B' . $row, $t->id);
        $sheet->setCellValue('C' . $row, \Carbon\Carbon::parse($t->tanggal_perbaikan)->format('d M Y'));
        $sheet->setCellValue('D' . $row, $t->nama_device);
        $sheet->setCellValue('E' . $row, $t->pelanggan->nama_pelanggan ?? 'N/A');
        $sheet->setCellValue('F' . $row, $t->user->name ?? 'N/A');
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
        $filterInfo = '_' . $namaBulan[(int)$month] . '_' . date('Y');
    } elseif ($year) {
        $filterInfo = '_' . $year;
    }

    $fileName = 'admin_transaksi' . $filterInfo . '_' . date('YmdHis') . '.xlsx';
    $filePath = storage_path('app/public/' . $fileName);

    $writer = new Xlsx($spreadsheet);
    $writer->save($filePath);

    return response()->download($filePath)->deleteFileAfterSend(true);
}




    public function storePerbaikan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pelanggan_id' => 'required|exists:pelanggan,id',
            'user_id' => 'required|exists:users,id',
            'nama_device' => 'required|string|max:100',
            'masalah' => 'required|string|max:200',
            'tindakan_perbaikan' => 'required|string',
            'harga' => 'required|numeric',
            'kategori_device' => 'required|string|max:50',
            'garansi_items' => 'required|array|min:1',
            'garansi_items.*.sparepart' => 'required|string|max:100',
            'garansi_items.*.periode' => 'required|string|in:Tidak ada garansi,1 Bulan,12 Bulan',
        ], [
            'pelanggan_id.required' => 'Pelanggan wajib dipilih.',
            'user_id.required' => 'Teknisi wajib dipilih.',
            'nama_device.required' => 'Nama barang wajib diisi.',
            'kategori_device.required' => 'Kategori device wajib diisi.',
            'masalah.required' => 'Masalah wajib diisi.',
            'tindakan_perbaikan.required' => 'Tindakan perbaikan wajib diisi.',
            'harga.required' => 'Harga wajib diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'garansi_items.required' => 'Minimal satu item garansi harus diisi.',
            'garansi_items.min' => 'Minimal satu item garansi harus diisi.',
            'garansi_items.*.sparepart.required' => 'Nama sparepart/komponen wajib diisi.',
            'garansi_items.*.periode.required' => 'Periode garansi wajib dipilih.',
            'garansi_items.*.periode.in' => 'Periode garansi tidak valid.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $kodeId = Perbaikan::generateKodePerbaikan();
            $perbaikan = new Perbaikan();
            $perbaikan->id = $kodeId;
            $perbaikan->pelanggan_id = $request->pelanggan_id;
            $perbaikan->user_id = $request->user_id;
            $perbaikan->tanggal_perbaikan = date('Y-m-d');
            $perbaikan->status = 'Menunggu';
            $perbaikan->nama_device = $request->nama_device;
            $perbaikan->kategori_device = $request->kategori_device;
            $perbaikan->masalah = $request->masalah;
            $perbaikan->tindakan_perbaikan = $request->tindakan_perbaikan;
            $perbaikan->harga = $request->harga;
            $perbaikan->created_at = now();
            $perbaikan->updated_at = now();
            $perbaikan->save();

            $garansiItems = [];
            foreach ($request->garansi_items as $item) {
                if (!empty($item['sparepart']) && !empty($item['periode'])) {
                    $garansiItems[] = [
                        'sparepart' => trim($item['sparepart']),
                        'periode' => trim($item['periode'])
                    ];
                }
            }

            if (empty($garansiItems)) {
                $perbaikan->delete();
                return redirect()->back()
                    ->withErrors(['garansi_items' => 'Minimal satu item garansi harus diisi dengan lengkap.'])
                    ->withInput();
            }

            try {

                $perbaikan->syncGaransiItems($garansiItems);
                DetailPerbaikan::createProcessStep($perbaikan->id, 'Menunggu Antrian Perbaikan');

                $garansiText = collect($garansiItems)
                    ->map(function ($item) {
                        return $item['sparepart'] . ': ' . $item['periode'];
                    })
                    ->implode('; ');

                return redirect()->route('admin.transaksi')
                    ->with('success', "Perbaikan berhasil disimpan dengan ID: {$perbaikan->id}. Garansi: " . $garansiText);
            } catch (\Exception $detailException) {
                $perbaikan->delete();
                throw $detailException;
            }
        } catch (\Exception $e) {
            logger()->error('Error creating perbaikan: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'stack_trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()])
                ->withInput();
        }
    }
}
