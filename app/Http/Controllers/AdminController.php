<?php

namespace App\Http\Controllers;

use App\Models\Perbaikan;
use App\Models\User;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        // Menghitung statistik untuk dashboard
        $totalTeknisi = User::where('role', 'teknisi')->count();
        $totalTransaksiHariIni = Perbaikan::whereDate('tanggal_perbaikan', date('Y-m-d'))->sum('harga');
        $totalTransaksiBulanIni = Perbaikan::whereMonth('tanggal_perbaikan', date('m'))->whereYear('tanggal_perbaikan', date('Y'))->sum('harga');

        // Mendapatkan transaksi terbaru
        $latestTransaksi = Perbaikan::with(['user', 'pelanggan'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'user',
            'totalTeknisi',
            'totalTransaksiHariIni',
            'totalTransaksiBulanIni',
            'latestTransaksi'
        ));
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
                         ->get();

        $totalTransaksi = $transaksi->sum('harga');
        $totalTransaksiHariIni = Perbaikan::where('status', 'Selesai')
                                        ->whereDate('tanggal_perbaikan', date('Y-m-d'))
                                        ->sum('harga');
        $totalTransaksiBulanIni = Perbaikan::where('status', 'Selesai')
                                         ->whereMonth('tanggal_perbaikan', date('m'))
                                         ->whereYear('tanggal_perbaikan', date('Y'))
                                         ->sum('harga');

        $teknisi = User::where('role', 'teknisi')->get();
        $teknisiStats = [];

        foreach ($teknisi as $t) {
            $repairCount = Perbaikan::where('user_id', $t->id)
                                  ->whereMonth('tanggal_perbaikan', $month)
                                  ->whereYear('tanggal_perbaikan', $year)
                                  ->count();

            $teknisiStats[] = [
                'name' => $t->name,
                'repair_count' => $repairCount,
                'income' => Perbaikan::where('user_id', $t->id)
                                  ->whereMonth('tanggal_perbaikan', $month)
                                  ->whereYear('tanggal_perbaikan', $year)
                                  ->sum('harga')
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
    if ($currentStatus == 'Selesai' ||
        ($currentStatus == 'Proses' && $newStatus == 'Menunggu')) {
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => "Tidak dapat mengubah status dari {$currentStatus} menjadi {$newStatus}"
            ], 422);
        }
        return redirect()->back()->with('error', "Tidak dapat mengubah status dari {$currentStatus} menjadi {$newStatus}");
    }

    $perbaikan->status = $newStatus;

    // Update tindakan_perbaikan if provided
    if ($request->has('tindakan_perbaikan')) {
        $perbaikan->tindakan_perbaikan = $request->tindakan_perbaikan;
    }

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
            'nama_pelanggan' => 'required|string|max:255',
            'nomor_telp' => 'required|string|max:13|regex:/^[0-9]+$/', // Only digits, max 13
            'email' => 'nullable|email|max:255',
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
        'status' => 'required|in:Menunggu,Proses,Selesai',
        'harga' => 'required|numeric',
        'garansi' => 'required|string',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    $perbaikan->update([
        'masalah' => $request->masalah,
        'tindakan_perbaikan' => $request->tindakan_perbaikan,
        'status' => $request->status,
        'harga' => $request->harga,
        'garansi' => $request->garansi,
    ]);

    return redirect()->route('admin.transaksi.show', $id)
        ->with('success', 'Data perbaikan berhasil diperbarui');
}
    public function updatePelanggan(Request $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_pelanggan' => 'required|string|max:255',
            'nomor_telp' => 'required|string|max:13|regex:/^[0-9]+$/', // Only digits, max 13
            'email' => 'nullable|email|max:255',
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
        $teknisi = User::where('role', 'teknisi')->get();

        return view('admin.tambah_perbaikan', compact('user', 'pelanggan', 'teknisi'));
    }

    public function storePerbaikan(Request $request)
    {
        $validator = Validator::make($request->all(), [
    'pelanggan_id' => 'required|exists:pelanggan,id',
    'user_id' => 'required|exists:users,id',
    'nama_barang' => 'required|string|max:255',
    'masalah' => 'required|string',
    'tindakan_perbaikan' => 'required|string', // Changed from nullable to required
    'kode_perbaikan' => 'required|string|unique:perbaikan,kode_perbaikan',
    'harga' => 'required|numeric', // Changed from nullable to required
    'garansi' => 'required|string|max:255', // Changed from nullable to required
], [
    'pelanggan_id.required' => 'Pelanggan wajib dipilih.',
    'user_id.required' => 'Teknisi wajib dipilih.',
    'nama_barang.required' => 'Nama barang wajib diisi.',
    'masalah.required' => 'Masalah wajib diisi.',
    'tindakan_perbaikan.required' => 'Tindakan perbaikan wajib diisi.', // Added message
    'kode_perbaikan.required' => 'Kode perbaikan wajib diisi.',
    'kode_perbaikan.unique' => 'Kode perbaikan sudah digunakan.',
    'harga.required' => 'Harga wajib diisi.', // Added message
    'harga.numeric' => 'Harga harus berupa angka.',
    'garansi.required' => 'Garansi wajib diisi.', // Added message
]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create new repair data
        $perbaikan = new Perbaikan();
$perbaikan->pelanggan_id = $request->pelanggan_id;
$perbaikan->user_id = $request->user_id;
$perbaikan->nama_barang = $request->nama_barang;
$perbaikan->masalah = $request->masalah;
$perbaikan->tindakan_perbaikan = $request->tindakan_perbaikan;
$perbaikan->kode_perbaikan = $request->kode_perbaikan;
$perbaikan->harga = $request->harga;
$perbaikan->garansi = $request->garansi;
$perbaikan->tanggal_perbaikan = date('Y-m-d');
$perbaikan->status = 'Menunggu';
$perbaikan->save();
       return redirect()->route('admin.transaksi')
        ->with('success', 'Perbaikan berhasil disimpan');
    }
    public function generateKey()
    {
        // Generate a random number between 100000 and 999999
        $randomNumber = mt_rand(100000, 999999);

        // Format the code as MG followed by the random number
        $kode = 'MG' . $randomNumber;

        // Check if the code already exists
        while (Perbaikan::where('kode_perbaikan', $kode)->exists()) {
            $randomNumber = mt_rand(100000, 999999);
            $kode = 'MG' . $randomNumber;
        }

        return response()->json(['kode' => $kode]);
    }
}
