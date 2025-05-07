<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\User;
use App\Models\Perbaikan;
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
        // Generate ID Karyawan baru (format: 10xxxx)
        $lastKaryawan = Karyawan::orderBy('id_karyawan', 'desc')->first();
        $lastId = $lastKaryawan ? intval(substr($lastKaryawan->id_karyawan, -5)) : 0;
        $newId = '10' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);
        
        return view('kepala_toko.tambah_karyawan', compact('user', 'newId'));
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
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'
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

        // Tentukan role user berdasarkan jabatan
        $userRole = 'user'; // Default
        if ($request->jabatan == 'Admin') {
            $userRole = 'admin';
        } elseif ($request->jabatan == 'Teknisi' || $request->jabatan == 'Kepala Teknisi') {
            $userRole = 'teknisi';
        }
        
        // Buat akun user
        User::create([
            'name' => $request->nama_karyawan,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $userRole,
        ]);

        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = Auth::user();
        $karyawan = Karyawan::findOrFail($id);
        
        // Jika karyawan adalah teknisi, ambil data perbaikan yang dilakukan
        $perbaikanList = [];
        if ($karyawan->jabatan == 'Teknisi' || $karyawan->jabatan == 'Kepala Teknisi') {
            // Cari user dengan nama yang sama dengan karyawan
            $teknisiUser = User::where('name', $karyawan->nama_karyawan)
                ->where('role', 'teknisi')
                ->first();
            
            if ($teknisiUser) {
                $perbaikanList = Perbaikan::where('user_id', $teknisiUser->id)
                    ->orderBy('tanggal_perbaikan', 'desc')
                    ->get();
            }
        }
        
        return view('kepala_toko.detail_karyawan', compact('user', 'karyawan', 'perbaikanList'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = Auth::user();
        $karyawan = Karyawan::findOrFail($id);
        
        return view('kepala_toko.edit_karyawan', compact('user', 'karyawan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $karyawan = Karyawan::findOrFail($id);
        
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

        // Update data user jika ada
        $user = User::where('name', $karyawan->nama_karyawan)->first();
        if ($user) {
            // Tentukan role user berdasarkan jabatan
            $userRole = 'user'; // Default
            if ($request->jabatan == 'Admin') {
                $userRole = 'admin';
            } elseif ($request->jabatan == 'Teknisi' || $request->jabatan == 'Kepala Teknisi') {
                $userRole = 'teknisi';
            }
            
            // Update nama dan role
            $user->update([
                'name' => $request->nama_karyawan,
                'role' => $userRole,
            ]);
        }

        return redirect()->route('karyawan.index')->with('success', 'Data karyawan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $karyawan = Karyawan::findOrFail($id);
        
        // Hapus user jika ada
        $user = User::where('name', $karyawan->nama_karyawan)->first();
        if ($user) {
            $user->delete();
        }
        
        $karyawan->delete();
        
        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil dihapus');
    }
}