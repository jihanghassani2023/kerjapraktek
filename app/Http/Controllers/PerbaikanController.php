<?php

namespace App\Http\Controllers;

use App\Models\Perbaikan;
use App\Models\Pelanggan;
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

        // Get all repairs assigned to this technician
        $perbaikan = Perbaikan::where('user_id', $user->id)
            ->with('pelanggan')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('teknisi.dashboard', compact('user', 'perbaikan', 'sedangMenunggu', 'perbaikanSelesaiHari', 'perbaikanSelesaiBulan'));
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
    'tindakan_perbaikan' => 'required|string', // Changed from nullable to required
    'status' => 'required|in:Menunggu,Proses,Selesai',
    'harga' => 'required|numeric', // Changed from nullable to required
    'garansi' => 'required|string', // Changed from nullable to required
], [
    'masalah.required' => 'Masalah wajib diisi.',
    'tindakan_perbaikan.required' => 'Tindakan perbaikan wajib diisi.', // Added message
    'status.required' => 'Status wajib diisi.',
    'status.in' => 'Status tidak valid.',
    'harga.required' => 'Harga wajib diisi.', // Added message
    'harga.numeric' => 'Harga harus berupa angka.',
    'garansi.required' => 'Garansi wajib diisi.', // Added message
]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update allowed fields for technician
        $perbaikan->masalah = $request->masalah;
        $perbaikan->tindakan_perbaikan = $request->tindakan_perbaikan; // Save the new field
        $perbaikan->status = $request->status;
        if ($request->has('harga')) {
            $perbaikan->harga = $request->harga;
        }
        $perbaikan->save();

        return redirect()->route('teknisi.progress')->with('success', 'Data perbaikan berhasil diperbarui');
    }


    /**
     * Update the status of a repair.
     */

   public function updateStatus(Request $request, $id)
    {
        $perbaikan = Perbaikan::findOrFail($id);

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
    'tindakan_perbaikan' => 'required|string', // Changed from nullable to required
    'harga' => 'required|numeric', // Changed from nullable to required
], [
    'status.required' => 'Status wajib diisi.',
    'status.in' => 'Status tidak valid.',
    'tindakan_perbaikan.required' => 'Tindakan perbaikan wajib diisi.', // Added message
    'harga.required' => 'Harga wajib diisi.', // Added message
    'harga.numeric' => 'Harga harus berupa angka.',
]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Update status
        $oldStatus = $perbaikan->status;
        $perbaikan->status = $request->status;

        // Update tindakan_perbaikan if provided
        if ($request->has('tindakan_perbaikan')) {
            $perbaikan->tindakan_perbaikan = $request->tindakan_perbaikan;
        }

        // Update harga if provided
        if ($request->has('harga')) {
            $perbaikan->harga = $request->harga;
        }

        $perbaikan->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'status' => $perbaikan->status,
                'message' => "Status berhasil diperbarui dari {$oldStatus} menjadi {$perbaikan->status}",
                'id' => $perbaikan->id
            ]);
        }

        return redirect()->route('teknisi.progress')->with('success', 'Status berhasil diperbarui');
    }

    /**
     * Show the progress view.
     */
    public function progress()
    {
        $user = Auth::user();

        // Get all repairs for this user
        $perbaikan = Perbaikan::where('user_id', $user->id)
            ->with('pelanggan')
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
            ->with('pelanggan')
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
        if ($perbaikan->user_id != $user->id && $user->role !== 'admin') {
            return redirect()->route('teknisi.dashboard')->with('error', 'Anda tidak memiliki akses');
        }

        return view('teknisi.confirm_status', compact('user', 'perbaikan', 'status'));
    }
}
