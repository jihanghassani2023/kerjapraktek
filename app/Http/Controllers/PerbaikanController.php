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
            'tindakan_perbaikan' => 'required|string',
            'kategori_device' => 'required|string|max:50',
            'status' => 'required|in:Menunggu,Proses,Selesai',
            'harga' => 'required|numeric',
            'garansi' => 'required|string',
            'proses_step' => 'nullable|string',
        ], [
            'masalah.required' => 'Masalah wajib diisi.',
            'tindakan_perbaikan.required' => 'Tindakan perbaikan wajib diisi.',
            'kategori_device.required' => 'Kategori device wajib diisi.',
            'status.required' => 'Status wajib diisi.',
            'status.in' => 'Status tidak valid.',
            'harga.required' => 'Harga wajib diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'garansi.required' => 'Garansi wajib diisi.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update allowed fields for technician
        $perbaikan->masalah = $request->masalah;
        $perbaikan->tindakan_perbaikan = $request->tindakan_perbaikan;
        $perbaikan->kategori_device = $request->kategori_device;
        $perbaikan->status = $request->status;
        $perbaikan->harga = $request->harga;
        $perbaikan->garansi = $request->garansi;
        $perbaikan->save();

        // Add a new process step if provided
        if ($request->filled('proses_step')) {
            $perbaikan->addProsesStep($request->proses_step);
        }

        return redirect()->route('teknisi.progress')->with('success', 'Data perbaikan berhasil diperbarui');
    }

    /**
     * Update the status of a repair.
     */

    public function updateStatus(Request $request, $id)
    {
        $perbaikan = Perbaikan::findOrFail($id);
        $currentStatus = $perbaikan->status;
        $newStatus = $request->status;

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

        // Update status
        $perbaikan->status = $newStatus;

        // Update tindakan_perbaikan if provided
        if ($request->has('tindakan_perbaikan')) {
            $perbaikan->tindakan_perbaikan = $request->tindakan_perbaikan;
        }

        // Update harga if provided
        if ($request->has('harga')) {
            $perbaikan->harga = $request->harga;
        }

        // Add status change to proses_pengerjaan
        $currentProcess = $perbaikan->proses_pengerjaan ?? [];

        // Add status change entry
        $statusMessage = "Status diubah menjadi " . $newStatus;
        $currentProcess[] = [
            'step' => $statusMessage,
            'timestamp' => now()->format('Y-m-d H:i:s')
        ];

        // Add custom process step if provided
        if ($request->filled('proses_step')) {
            $currentProcess[] = [
                'step' => $request->proses_step,
                'timestamp' => now()->format('Y-m-d H:i:s')
            ];
        }

        $perbaikan->proses_pengerjaan = $currentProcess;
        $perbaikan->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'status' => $perbaikan->status,
                'message' => "Status berhasil diperbarui dari {$currentStatus} menjadi {$perbaikan->status}",
                'id' => $perbaikan->id
            ]);
        }

        return redirect()->route('teknisi.progress')->with('success', 'Status berhasil diperbarui');
    }
    public function addProcessStep(Request $request, $id)
    {
        $perbaikan = Perbaikan::findOrFail($id);

        // Pastikan perbaikan milik teknisi yang login
        if ($perbaikan->user_id != Auth::id() && Auth::user()->role !== 'admin') {
            return redirect()->route('teknisi.dashboard')->with('error', 'Anda tidak memiliki akses');
        }

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

        return redirect()->route('perbaikan.show', $id)
            ->with('success', 'Langkah proses pengerjaan berhasil ditambahkan.');
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
