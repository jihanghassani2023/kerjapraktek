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
    public function index()
    {
        $user = Auth::user();
        $karyawan = Karyawan::all();
        return view('kepala_toko.data_karyawan', compact('karyawan', 'user'));
    }

    public function create()
    {
        $user = Auth::user();
        $lastKaryawan = Karyawan::orderBy('id_karyawan', 'desc')->first();
        $lastId = $lastKaryawan ? intval(substr($lastKaryawan->id_karyawan, -5)) : 0;
        $newId = '10' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);

        return view('kepala_toko.tambah_karyawan', compact('user', 'newId'));
    }

    public function store(Request $request)
    {
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

        $karyawan = Karyawan::create([
            'id_karyawan' => $request->id_karyawan,
            'nama_karyawan' => $request->nama_karyawan,
            'ttl' => $request->ttl,
            'alamat' => $request->alamat,
            'jabatan' => $request->jabatan,
            'status' => $request->status,
        ]);

        $userRole = 'user';
        if ($request->jabatan == 'Admin') {
            $userRole = 'admin';
        } elseif ($request->jabatan == 'Teknisi' || $request->jabatan == 'Kepala Teknisi') {
            $userRole = 'teknisi';
        }

        User::create([
            'name' => $request->nama_karyawan,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $userRole,
        ]);

        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil ditambahkan');
    }

    public function show($id)
    {
        $user = Auth::user();
        $karyawan = Karyawan::findOrFail($id);
        $perbaikanList = [];

        if ($karyawan->jabatan == 'Teknisi' || $karyawan->jabatan == 'Kepala Teknisi') {
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

    public function edit($id)
    {
        $user = Auth::user();
        $karyawan = Karyawan::findOrFail($id);

        return view('kepala_toko.edit_karyawan', compact('user', 'karyawan'));
    }

    public function update(Request $request, $id)
    {
        $karyawan = Karyawan::findOrFail($id);

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

        $karyawan->update([
            'nama_karyawan' => $request->nama_karyawan,
            'ttl' => $request->ttl,
            'alamat' => $request->alamat,
            'jabatan' => $request->jabatan,
            'status' => $request->status,
        ]);

        $user = User::where('name', $karyawan->nama_karyawan)->first();
        if ($user) {
            $userRole = 'user';
            if ($request->jabatan == 'Admin') {
                $userRole = 'admin';
            } elseif ($request->jabatan == 'Teknisi' || $request->jabatan == 'Kepala Teknisi') {
                $userRole = 'teknisi';
            }

            $user->update([
                'name' => $request->nama_karyawan,
                'role' => $userRole,
            ]);
        }

        return redirect()->route('karyawan.index')->with('success', 'Data karyawan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $karyawan = Karyawan::findOrFail($id);

        $user = User::where('name', $karyawan->nama_karyawan)->first();
        if ($user) {
            $user->delete();
        }

        $karyawan->delete();

        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil dihapus');
    }
}
