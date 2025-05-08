<?php

namespace App\Http\Controllers;

use App\Models\Perbaikan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
        
        $perbaikanSelesaiHari = Perbaikan::where('user_id', $user->id)
            ->where('status', 'Selesai')
            ->whereDate('tanggal_perbaikan', date('Y-m-d'))
            ->count();
            
        $perbaikanSelesaiBulan = Perbaikan::where('user_id', $user->id)
            ->where('status', 'Selesai')
            ->whereMonth('tanggal_perbaikan', date('m'))
            ->whereYear('tanggal_perbaikan', date('Y'))
            ->count();
        
        // Get all repairs for this user
        $perbaikan = Perbaikan::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('teknisi.dashboard', compact('user', 'perbaikan', 'sedangMenunggu', 'perbaikanSelesaiHari', 'perbaikanSelesaiBulan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        return view('teknisi.tambah_perbaikan', compact('user'));
    }

    /**
     * Generate a unique repair code.
     */
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate form input
        $validator = Validator::make($request->all(), [
            'nama_pelanggan' => 'required|string|max:255',
            'nama_barang' => 'required|string|max:255',
            'nomor_telp' => 'required|string|max:15',
            'email' => 'nullable|email|max:255',
            'masalah' => 'required|string',
            'harga' => 'nullable|numeric',
            'garansi' => 'nullable|string|max:255',
            'kode_perbaikan' => 'required|string|unique:perbaikan,kode_perbaikan',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create new repair record
        $perbaikan = new Perbaikan();
        $perbaikan->nama_pelanggan = $request->nama_pelanggan;
        $perbaikan->nama_barang = $request->nama_barang;
        $perbaikan->nomor_telp = $request->nomor_telp;
        $perbaikan->email = $request->email;
        $perbaikan->masalah = $request->masalah;
        $perbaikan->harga = $request->harga;
        $perbaikan->garansi = $request->garansi;
        $perbaikan->kode_perbaikan = $request->kode_perbaikan;
        $perbaikan->tanggal_perbaikan = date('Y-m-d');
        $perbaikan->status = 'Menunggu';
        $perbaikan->user_id = Auth::id();
        $perbaikan->save();

        return redirect()->route('teknisi.progress')->with('success', 'Data perbaikan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
{
    $user = Auth::user();
    $perbaikan = Perbaikan::findOrFail($id);
    
    // Make sure the repair belongs to the logged-in user
    if ($perbaikan->user_id != $user->id && $user->role !== 'admin' && $user->role !== 'kepala_toko') {
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
        $perbaikan = Perbaikan::findOrFail($id);
        
        // Make sure the repair belongs to the logged-in user
        if ($perbaikan->user_id != $user->id) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses');
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
        if ($perbaikan->user_id != Auth::id()) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses');
        }
        
        // Validate form input
        $validator = Validator::make($request->all(), [
            'nama_pelanggan' => 'required|string|max:255',
            'nama_barang' => 'required|string|max:255',
            'nomor_telp' => 'required|string|max:15',
            'email' => 'nullable|email|max:255',
            'masalah' => 'required|string',
            'harga' => 'nullable|numeric',
            'garansi' => 'nullable|string|max:255',
            'status' => 'required|in:Menunggu,Proses,Selesai',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update repair record
        $perbaikan->nama_pelanggan = $request->nama_pelanggan;
        $perbaikan->nama_barang = $request->nama_barang;
        $perbaikan->nomor_telp = $request->nomor_telp;
        $perbaikan->email = $request->email;
        $perbaikan->masalah = $request->masalah;
        $perbaikan->harga = $request->harga;
        $perbaikan->garansi = $request->garansi;
        $perbaikan->status = $request->status;
        $perbaikan->save();

        return redirect()->route('teknisi.progress')->with('success', 'Data perbaikan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $perbaikan = Perbaikan::findOrFail($id);
        
        // Make sure the repair belongs to the logged-in user
        if ($perbaikan->user_id != Auth::id()) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses');
        }
        
        $perbaikan->delete();
        
        return redirect()->route('teknisi.dashboard')->with('success', 'Data perbaikan berhasil dihapus');
    }

    /**
     * Update the status of a repair.
     */
    public function updateStatus(Request $request, $id)
    {
        $perbaikan = Perbaikan::findOrFail($id);
        
        // Make sure the repair belongs to the logged-in user
        if ($perbaikan->user_id != Auth::id()) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses');
        }
        
        // Validate status
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:Menunggu,Proses,Selesai',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Status tidak valid'], 400);
        }

        // Update status
        $perbaikan->status = $request->status;
        $perbaikan->save();
        
        return response()->json(['success' => true, 'status' => $perbaikan->status]);
    }

    /**
     * Show the progress view.
     */
    public function progress()
    {
        $user = Auth::user();
        
        // Get all repairs for this user
        $perbaikan = Perbaikan::where('user_id', $user->id)
            ->orderBy('tanggal_perbaikan', 'desc')
            ->get();
        
        return view('teknisi.progress', compact('user', 'perbaikan'));
    }

    /**
     * Show the laporan view.
     */
    public function laporan()
    {
        $user = Auth::user();
        
        // Get completed repairs for this user
        $perbaikan = Perbaikan::where('user_id', $user->id)
            ->where('status', 'Selesai')
            ->orderBy('tanggal_perbaikan', 'desc')
            ->get();
        
        return view('teknisi.laporan', compact('user', 'perbaikan'));
    }

    /**
     * Confirm status change dialog.
     */
    public function confirmStatus($id, $status)
    {
        $user = Auth::user();
        $perbaikan = Perbaikan::findOrFail($id);
        
        // Make sure the repair belongs to the logged-in user
        if ($perbaikan->user_id != $user->id) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses');
        }
        
        return view('teknisi.confirm_status', compact('user', 'perbaikan', 'status'));
    }
}