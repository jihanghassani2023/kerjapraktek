<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Perbaikan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        // Pelanggan yang pernah memperbaiki di teknisi ini
        $pelanggan = Pelanggan::whereHas('perbaikan', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        return view('teknisi.pelanggan', compact('user', 'pelanggan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        return view('teknisi.tambah_pelanggan', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate form input
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

        // Create new pelanggan record
        $pelanggan = Pelanggan::create([
            'nama_pelanggan' => $request->nama_pelanggan,
            'nomor_telp' => $request->nomor_telp,
            'email' => $request->email,
        ]);

        // Store pelanggan ID in session for the next step
        session(['pelanggan_id' => $pelanggan->id]);

        return redirect()->route('perbaikan.create')
            ->with('success', 'Data pelanggan berhasil disimpan. Silakan isi data perbaikan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = Auth::user();
        $pelanggan = Pelanggan::findOrFail($id);

        // Get all repairs for this customer done by this technician
        $perbaikan = Perbaikan::where('pelanggan_id', $pelanggan->id)
            ->where('user_id', $user->id)
            ->orderBy('tanggal_perbaikan', 'desc')
            ->get();

        return view('teknisi.detail_pelanggan', compact('user', 'pelanggan', 'perbaikan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = Auth::user();
        $pelanggan = Pelanggan::findOrFail($id);

        // Check if this technician has any repairs for this customer
        $hasPerbaikan = Perbaikan::where('pelanggan_id', $pelanggan->id)
            ->where('user_id', $user->id)
            ->exists();

        if (!$hasPerbaikan) {
            return redirect()->route('pelanggan.index')->with('error', 'Anda tidak memiliki akses ke data pelanggan ini');
        }

        return view('teknisi.edit_pelanggan', compact('user', 'pelanggan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        // Check if this technician has any repairs for this customer
        $hasPerbaikan = Perbaikan::where('pelanggan_id', $pelanggan->id)
            ->where('user_id', Auth::id())
            ->exists();

        if (!$hasPerbaikan) {
            return redirect()->route('pelanggan.index')->with('error', 'Anda tidak memiliki akses ke data pelanggan ini');
        }

        // Validate form input
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

        // Update pelanggan record
        $pelanggan->nama_pelanggan = $request->nama_pelanggan;
        $pelanggan->nomor_telp = $request->nomor_telp;
        $pelanggan->email = $request->email;
        $pelanggan->save();

        return redirect()->route('pelanggan.index')
            ->with('success', 'Data pelanggan berhasil diperbarui');
    }
}
