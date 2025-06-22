<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Perbaikan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PelangganController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $pelanggan = Pelanggan::whereHas('perbaikan', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        return view('teknisi.pelanggan', compact('user', 'pelanggan'));
    }

    public function create()
    {
        $user = Auth::user();
        return view('teknisi.tambah_pelanggan', compact('user'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_pelanggan' => 'required|string|max:50',
            'nomor_telp' => 'required|string|max:13|regex:/^[0-9]+$/',
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

        session(['pelanggan_id' => $pelanggan->id]);

        return redirect()->route('perbaikan.create')
            ->with('success', 'Data pelanggan berhasil disimpan. Silakan isi data perbaikan.');
    }

    public function show($id)
    {
        $user = Auth::user();
        $pelanggan = Pelanggan::findOrFail($id);
        $perbaikan = Perbaikan::where('pelanggan_id', $pelanggan->id)
            ->where('user_id', $user->id)
            ->with('detail')
            ->orderBy('tanggal_perbaikan', 'desc')
            ->get();

        return view('teknisi.detail_pelanggan', compact('user', 'pelanggan', 'perbaikan'));
    }

    public function edit($id)
    {
        $user = Auth::user();
        $pelanggan = Pelanggan::findOrFail($id);

        $hasPerbaikan = Perbaikan::where('pelanggan_id', $pelanggan->id)
            ->where('user_id', $user->id)
            ->exists();

        if (!$hasPerbaikan) {
            return redirect()->route('pelanggan.index')->with('error', 'Anda tidak memiliki akses ke data pelanggan ini');
        }

        return view('teknisi.edit_pelanggan', compact('user', 'pelanggan'));
    }

    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        $hasPerbaikan = Perbaikan::where('pelanggan_id', $pelanggan->id)
            ->where('user_id', Auth::id())
            ->exists();

        if (!$hasPerbaikan) {
            return redirect()->route('pelanggan.index')->with('error', 'Anda tidak memiliki akses ke data pelanggan ini');
        }

        $validator = Validator::make($request->all(), [
            'nama_pelanggan' => 'required|string|max:255',
            'nomor_telp' => 'required|string|max:13|regex:/^[0-9]+$/',
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

        $pelanggan->nama_pelanggan = $request->nama_pelanggan;
        $pelanggan->nomor_telp = $request->nomor_telp;
        $pelanggan->email = $request->email;
        $pelanggan->save();

        return redirect()->route('pelanggan.index')
            ->with('success', 'Data pelanggan berhasil diperbarui');
    }
}
