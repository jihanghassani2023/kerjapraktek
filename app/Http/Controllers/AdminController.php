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

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = Auth::user();

        $totalTeknisi = User::whereIn('role', ['teknisi', 'kepala teknisi'])->count();

        $totalTransaksiHariIni = DetailPerbaikan::whereHas('perbaikan', function($query) {
            $query->whereDate('tanggal_perbaikan', date('Y-m-d'));
        })->sum('harga');

        $totalTransaksiBulanIni = DetailPerbaikan::whereHas('perbaikan', function($query) {
            $query->whereMonth('tanggal_perbaikan', date('m'))
                  ->whereYear('tanggal_perbaikan', date('Y'));
        })->sum('harga');

        // If search is submitted via the search form, redirect to search results page
        if ($request->has('search') && $request->search) {
            return redirect()->route('admin.search', ['search' => $request->search]);
        }

        // Mendapatkan transaksi terbaru
        $latestTransaksi = Perbaikan::with(['user', 'pelanggan', 'detail'])
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

    /**
     * Search perbaikan based on keyword.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $user = Auth::user();
        $search = $request->input('search');

        if (empty($search)) {
            return redirect()->route('admin.dashboard');
        }

        $query = Perbaikan::with(['user', 'pelanggan', 'detail']);

        $query->where(function ($q) use ($search) {
            $q->where('id', 'like', "%{$search}%")
                ->orWhereHas('detail', function ($subq) use ($search) {
                    $subq->where('nama_device', 'like', "%{$search}%");
                })
                ->orWhereHas('pelanggan', function ($subq) use ($search) {
                    $subq->where('nama_pelanggan', 'like', "%{$search}%")
                        ->orWhere('nomor_telp', 'like', "%{$search}%");
                });
        });

        $perbaikan = $query->orderBy('created_at', 'desc')->get();

        return view('admin.search_results', compact('user', 'perbaikan', 'search'));
    }

    public function transaksi(Request $request)
    {
        $user = Auth::user();

        // Ambil parameter filter dari request (biarkan kosong jika tidak ada)
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
        // Jika keduanya kosong, tampilkan semua data

        $transaksi = $query->with(['user', 'pelanggan', 'detail'])
            ->orderBy('tanggal_perbaikan', 'desc')
            ->get()
            ->map(function ($item) {
                $item->tanggal_formatted = DateHelper::formatTanggalIndonesia($item->tanggal_perbaikan);
                return $item;
            });

        // Calculate summary statistics
        $totalTransaksi = $transaksi->sum(function($item) {
            return $item->detail ? $item->detail->harga : 0;
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

        // DIPERBAIKI: Get technicians AND kepala teknisi with their repair count
        $teknisi = User::whereIn('role', ['teknisi', 'kepala teknisi'])->get();
        $teknisiStats = [];

        foreach ($teknisi as $t) {
            // Base query untuk setiap teknisi/kepala teknisi
            $querySelesai = Perbaikan::where('user_id', $t->id)->where('status', 'Selesai');
            $queryPending = Perbaikan::where('user_id', $t->id)->whereIn('status', ['Menunggu', 'Proses']);

            // For income calculation, join with detail table
            $incomeQuery = DetailPerbaikan::whereHas('perbaikan', function($query) use ($t) {
                $query->where('user_id', $t->id);
            });

            // Terapkan filter HANYA jika ada nilai (bukan kosong atau null)
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
                'role' => $t->role, // Tambahkan role untuk identifikasi
                'jabatan' => $t->jabatan ?? $t->role, // Tambahkan jabatan
                'repair_count' => $repairCount,
                'pending_count' => $pendingCount,
                'income' => $income
            ];
        }

        // Urutkan teknisi stats: Kepala Teknisi di atas, kemudian Teknisi
        $teknisiStats = collect($teknisiStats)->sortBy(function ($teknisi) {
            // Kepala teknisi akan muncul di atas (nilai 0), teknisi di bawah (nilai 1)
            if ($teknisi['role'] === 'kepala teknisi' ||
                (isset($teknisi['jabatan']) && strtolower($teknisi['jabatan']) === 'kepala teknisi')) {
                return 0;
            }
            return 1;
        })->values()->toArray();

        // Generate year options dinamis (5 tahun ke belakang sampai 2 tahun ke depan)
        $currentYear = date('Y'); // Akan berubah otomatis setiap tahun
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
            'yearOptions' // Tambahkan yearOptions ke view
        ));
    }

    public function showTransaksi($id)
    {
        $user = Auth::user();
        $transaksi = Perbaikan::with(['user', 'pelanggan', 'detail'])->findOrFail($id);

        return view('admin.detail_transaksi', compact('user', 'transaksi'));
    }

    public function updateStatus(Request $request, $id)
    {
        $perbaikan = Perbaikan::with('detail')->findOrFail($id);
        $currentStatus = $perbaikan->status;
        $newStatus = $request->status;

        // Validate the status transition
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:Menunggu,Proses,Selesai',
            'tindakan_perbaikan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
            }
            return redirect()->back()->withErrors($validator);
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

        $perbaikan->detail->update(['proses_pengerjaan' => $currentProcess]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'status' => $perbaikan->status,
                'message' => "Status berhasil diperbarui dari {$currentStatus} menjadi {$perbaikan->status}"
            ]);
        }

        return redirect()->route('admin.transaksi.show', $id)
            ->with('success', 'Status berhasil diperbarui');
    }

    // ADMIN TIDAK DAPAT MENAMBAH PROSES PENGERJAAN - DIHAPUS
    // public function addProcessStep() - FUNCTION INI DIHAPUS

    // Tambahan method untuk pengelolaan pelanggan
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
                'unique:pelanggan,nomor_telp' // Tambahkan validasi unique
            ],
            'email' => 'nullable|email|max:100|unique:pelanggan,email', // Tambahkan unique untuk email juga
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

    // ADMIN TIDAK DAPAT EDIT PERBAIKAN - FUNCTION INI DIHAPUS
    // public function editPerbaikan() - DIHAPUS
    // public function updatePerbaikan() - DIHAPUS

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
                'unique:pelanggan,nomor_telp,' . $id // Ignore current record saat update
            ],
            'email' => [
                'nullable',
                'email',
                'max:100',
                'unique:pelanggan,email,' . $id // Ignore current record saat update
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

        // Periksa apakah ada perbaikan terkait dengan pelanggan ini
        $hasPerbaikan = Perbaikan::where('pelanggan_id', $id)->exists();

        if ($hasPerbaikan) {
            return redirect()->route('admin.pelanggan')
                ->with('error', 'Pelanggan tidak dapat dihapus karena memiliki data perbaikan');
        }

        $pelanggan->delete();

        return redirect()->route('admin.pelanggan')
            ->with('success', 'Data pelanggan berhasil dihapus');
    }

    // Tambahan method untuk pengelolaan perbaikan oleh admin - HANYA BUAT BARU
    public function createPerbaikan()
    {
        $user = Auth::user();
        $pelanggan = Pelanggan::all();
        $teknisi = User::whereIn('role', ['teknisi', 'kepala teknisi'])
            ->orderBy('jabatan', 'desc') // Kepala Teknisi akan muncul di atas
            ->orderBy('name', 'asc')     // Kemudian urutkan berdasarkan nama
            ->get();

        return view('admin.tambah_perbaikan', compact('user', 'pelanggan', 'teknisi'));
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
            'garansi' => 'required|string|max:50',
            'kategori_device' => 'required|string|max:50',
        ], [
            'pelanggan_id.required' => 'Pelanggan wajib dipilih.',
            'user_id.required' => 'Teknisi wajib dipilih.',
            'nama_device.required' => 'Nama barang wajib diisi.',
            'kategori_device.required' => 'Kategori device wajib diisi.',
            'masalah.required' => 'Masalah wajib diisi.',
            'tindakan_perbaikan.required' => 'Tindakan perbaikan wajib diisi.',
            'harga.required' => 'Harga wajib diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'garansi.required' => 'Garansi wajib diisi.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create perbaikan header
        $perbaikan = new Perbaikan();
        $perbaikan->pelanggan_id = $request->pelanggan_id;
        $perbaikan->user_id = $request->user_id;
        $perbaikan->tanggal_perbaikan = date('Y-m-d');
        $perbaikan->status = 'Menunggu';
        $perbaikan->save();

        // Create perbaikan detail
        DetailPerbaikan::create([
            'perbaikan_id' => $perbaikan->id,
            'nama_device' => $request->nama_device,
            'kategori_device' => $request->kategori_device,
            'masalah' => $request->masalah,
            'tindakan_perbaikan' => $request->tindakan_perbaikan,
            'harga' => $request->harga,
            'garansi' => $request->garansi,
            'proses_pengerjaan' => [[
                'step' => 'Menunggu Antrian Perbaikan',
                'timestamp' => now()->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s')
            ]]
        ]);

        return redirect()->route('admin.transaksi')
            ->with('success', 'Perbaikan berhasil disimpan');
    }
}
