<?php

namespace App\Http\Controllers;

use App\Models\Perbaikan;
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
        $totalTransaksiHariIni = Perbaikan::whereDate('tanggal_perbaikan', date('Y-m-d'))->sum('harga');
        $totalTransaksiBulanIni = Perbaikan::whereMonth('tanggal_perbaikan', date('m'))->whereYear('tanggal_perbaikan', date('Y'))->sum('harga');

        // If search is submitted via the search form, redirect to search results page
        if ($request->has('search') && $request->search) {
            return redirect()->route('admin.search', ['search' => $request->search]);
        }

        // Mendapatkan transaksi terbaru
        $latestTransaksi = Perbaikan::with(['user', 'pelanggan'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($item) {
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

        $query = Perbaikan::with(['user', 'pelanggan']);

        $query->where(function ($q) use ($search) {
            $q->where('kode_perbaikan', 'like', "%{$search}%")
                ->orWhere('nama_device', 'like', "%{$search}%")
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

        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        $query = Perbaikan::query();

        if ($month && $year) {
            $query->whereMonth('tanggal_perbaikan', $month)
                ->whereYear('tanggal_perbaikan', $year);
        }

        $transaksi = $query->with(['user', 'pelanggan'])
    ->orderBy('tanggal_perbaikan', 'desc')
    ->get()
    ->map(function($item) {
        $item->tanggal_formatted = DateHelper::formatTanggalIndonesia($item->tanggal_perbaikan);
        return $item;
    });

        // Calculate summary statistics
        $totalTransaksi = $transaksi->sum('harga');
        $totalTransaksiHariIni = Perbaikan::where('status', 'Selesai')
            ->whereDate('tanggal_perbaikan', date('Y-m-d'))
            ->sum('harga');
        $totalTransaksiBulanIni = Perbaikan::where('status', 'Selesai')
            ->whereMonth('tanggal_perbaikan', date('m'))
            ->whereYear('tanggal_perbaikan', date('Y'))
            ->sum('harga');

        // Get technicians with their repair count
        $teknisi = User::where('role', 'teknisi')->get();
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
        $transaksi = Perbaikan::with(['user', 'pelanggan'])->findOrFail($id);

        return view('admin.detail_transaksi', compact('user', 'transaksi'));
    }
    public function updateStatus(Request $request, $id)
    {
        $perbaikan = Perbaikan::findOrFail($id);
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

        // Update tindakan_perbaikan if provided
        if ($request->has('tindakan_perbaikan')) {
            $perbaikan->tindakan_perbaikan = $request->tindakan_perbaikan;
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
        $perbaikan->proses_pengerjaan = $currentProcess;
        $perbaikan->save();

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
            'nomor_telp' => 'required|string|max:13|regex:/^[0-9]+$/', // Only digits, max 13
            'email' => 'nullable|email|max:100',
        ], [
            'nama_pelanggan.required' => 'Nama pelanggan wajib diisi.',
            'nomor_telp.required' => 'Nomor telepon wajib diisi.',
            'nomor_telp.max' => 'Nomor telepon maksimal 13 digit.',
            'nomor_telp.regex' => 'Nomor telepon hanya boleh berisi angka.',
            'email.email' => 'Format email tidak valid.',
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
    public function editPerbaikan($id)
    {
        $user = Auth::user();
        $perbaikan = Perbaikan::with('pelanggan')->findOrFail($id);

        return view('admin.edit_perbaikan', compact('user', 'perbaikan'));
    }

    public function updatePerbaikan(Request $request, $id)
    {
        $perbaikan = Perbaikan::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'masalah' => 'required|string',
            'tindakan_perbaikan' => 'required|string',
            'kategori_device' => 'required|string|max:50',
            'harga' => 'required|numeric',
            'garansi' => 'required|string',
            'proses_step' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $updateData = [
            'masalah' => $request->masalah,
            'tindakan_perbaikan' => $request->tindakan_perbaikan,
            'kategori_device' => $request->kategori_device,
            'harga' => $request->harga,
            'garansi' => $request->garansi,
        ];

        $perbaikan->update($updateData);

        // Add a new process step if provided
        if ($request->filled('proses_step')) {
            $perbaikan->addProsesStep($request->proses_step);
        }

        return redirect()->route('admin.transaksi.show', $id)
            ->with('success', 'Data perbaikan berhasil diperbarui');
    }
    public function addProcessStep(Request $request, $id)
    {
        $perbaikan = Perbaikan::findOrFail($id);

        // Validasi input
        $request->validate([
            'proses_step' => 'required|string|max:255',
        ]);

        // Tambahkan langkah proses baru
        $currentProcess = $perbaikan->proses_pengerjaan ?? [];
        $currentProcess[] = [
            'step' => $request->proses_step,
            'timestamp' => now()->format('Y-m-d H:i:s')
        ];

        $perbaikan->proses_pengerjaan = $currentProcess;
        $perbaikan->save();

        return redirect()->route('admin.transaksi.show', $id)
            ->with('success', 'Langkah proses pengerjaan berhasil ditambahkan.');
    }
    public function updatePelanggan(Request $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_pelanggan' => 'required|string|max:50',
            'nomor_telp' => 'required|string|max:13|regex:/^[0-9]+$/', // Only digits, max 13
            'email' => 'nullable|email|max:100',
        ], [
            'nama_pelanggan.required' => 'Nama pelanggan wajib diisi.',
            'nomor_telp.required' => 'Nomor telepon wajib diisi.',
            'nomor_telp.max' => 'Nomor telepon maksimal 13 digit.',
            'nomor_telp.regex' => 'Nomor telepon hanya boleh berisi angka.',
            'email.email' => 'Format email tidak valid.',
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

    // Tambahan method untuk pengelolaan perbaikan oleh admin
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
            'proses_step' => 'nullable|string',
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

        $perbaikan = new Perbaikan();
        $perbaikan->pelanggan_id = $request->pelanggan_id;
        $perbaikan->user_id = $request->user_id;
        $perbaikan->nama_device = $request->nama_device;
        $perbaikan->kategori_device = $request->kategori_device;
        $perbaikan->masalah = $request->masalah;
        $perbaikan->tindakan_perbaikan = $request->tindakan_perbaikan;
        $perbaikan->harga = $request->harga;
        $perbaikan->garansi = $request->garansi;
        $perbaikan->tanggal_perbaikan = date('Y-m-d');
        $perbaikan->status = 'Menunggu';
        $perbaikan->proses_pengerjaan = [[
            'step' => 'Menunggu Antrian Perbaikan',
            'timestamp' => now()->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s')
        ]];
        $perbaikan->save();
        return redirect()->route('admin.transaksi')
            ->with('success', 'Perbaikan berhasil disimpan');
    }
}
