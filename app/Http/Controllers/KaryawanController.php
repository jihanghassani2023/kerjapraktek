<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $karyawan = Karyawan::all();
        return view('kepala_toko.data_karyawan', compact('karyawan', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        return view('kepala_toko.tambah_karyawan', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'id_karyawan' => 'required|unique:karyawan,id_karyawan',
            'nama_karyawan' => 'required|string|max:255',
            'ttl' => 'required|string|max:255',
            'alamat' => 'required|string',
            'jabatan' => 'required|string|in:Kepala Teknisi,Teknisi,Admin',
            'status' => 'required|string|in:Kontrak,Tetap',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Simpan data karyawan
        $karyawan = Karyawan::create([
            'id_karyawan' => $request->id_karyawan,
            'nama_karyawan' => $request->nama_karyawan,
            'ttl' => $request->ttl,
            'alamat' => $request->alamat,
            'jabatan' => $request->jabatan,
            'status' => $request->status,
        ]);

        // Jika jabatan Admin atau Teknisi, buat akun user juga
        if (in_array($request->jabatan, ['Admin', 'Teknisi'])) {
            // Buat username dari nama (tanpa spasi) dan domain sesuai jabatan
            $namaParts = explode(' ', $request->nama_karyawan);
            $username = strtolower($namaParts[0]);
            
            if (count($namaParts) > 1) {
                $username .= strtolower($namaParts[1][0]); // Ambil inisial nama kedua jika ada
            }
            
            $domain = $request->jabatan === 'Admin' ? '@admin.mgtech' : '@teknisi.mgtech';
            $email = $username . $domain;
            
            // Cek jika email sudah digunakan
            $existingUser = User::where('email', $email)->first();
            if ($existingUser) {
                // Tambahkan angka random ke username jika sudah ada
                $username .= rand(1, 99);
                $email = $username . $domain;
            }
            
            // Buat password default (bisa diganti nanti)
            $defaultPassword = ucfirst($request->jabatan) . '_mgtech1';
            
            // Simpan user baru
            User::create([
                'name' => $request->nama_karyawan,
                'email' => $email,
                'password' => Hash::make($defaultPassword),
                'role' => strtolower($request->jabatan === 'Admin' ? 'admin' : 'teknisi'),
            ]);
        }

        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Karyawan $karyawan)
    {
        $user = Auth::user();
        return view('kepala_toko.detail_karyawan', compact('karyawan', 'user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Karyawan $karyawan)
    {
        $user = Auth::user();
        return view('kepala_toko.edit_karyawan', compact('karyawan', 'user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Karyawan $karyawan)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nama_karyawan' => 'required|string|max:255',
            'ttl' => 'required|string|max:255',
            'alamat' => 'required|string',
            'jabatan' => 'required|string|in:Kepala Teknisi,Teknisi,Admin',
            'status' => 'required|string|in:Kontrak,Tetap',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update data karyawan
        $karyawan->update([
            'nama_karyawan' => $request->nama_karyawan,
            'ttl' => $request->ttl,
            'alamat' => $request->alamat,
            'jabatan' => $request->jabatan,
            'status' => $request->status,
        ]);

        return redirect()->route('karyawan.index')->with('success', 'Data karyawan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Karyawan $karyawan)
    {
        $karyawan->delete();
        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil dihapus');
    }
}