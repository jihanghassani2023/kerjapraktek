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

        $request->validate([
            'status' => 'required|in:Menunggu,Proses,Selesai',
            'tindakan_perbaikan' => 'nullable|string',
        ]);

        $perbaikan->status = $request->status;

        // Update tindakan_perbaikan if provided
        if ($request->has('tindakan_perbaikan')) {
            $perbaikan->tindakan_perbaikan = $request->tindakan_perbaikan;
        }

        $perbaikan->save();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'status' => $perbaikan->status]);
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
            'nomor_telp' => 'required|string|max:15',
            'email' => 'nullable|email|max:255',
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
            'nama_pelanggan' => 'required|string|max:255',
            'nomor_telp' => 'required|string|max:15',
            'email' => 'nullable|email|max:255',
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
            'tindakan_perbaikan' => 'nullable|string',
            'kode_perbaikan' => 'required|string|unique:perbaikan,kode_perbaikan',
            'harga' => 'nullable|numeric',
            'garansi' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Buat data perbaikan baru
        $perbaikan = new Perbaikan();
        $perbaikan->pelanggan_id = $request->pelanggan_id;
        $perbaikan->user_id = $request->user_id; // Teknisi yang dipilih
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
