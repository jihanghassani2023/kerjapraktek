<?php

namespace App\Http\Controllers;

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
        $karyawan = User::whereIn('role', ['admin', 'teknisi'])->get();
        return view('kepala_toko.data_karyawan', compact('karyawan', 'user'));
    }

    public function create()
    {
        $user = Auth::user();
        $lastKaryawan = User::where('id_karyawan', '!=', null)
                          ->orderBy('id_karyawan', 'desc')->first();
        $lastId = $lastKaryawan ? intval(substr($lastKaryawan->id_karyawan, -5)) : 0;
        $newId = '10' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);

        return view('kepala_toko.tambah_karyawan', compact('user', 'newId'));
    }public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'id_karyawan' => 'required|unique:users,id_karyawan',
        'name' => 'required|string|max:255', // Ubah ini dari nama_karyawan ke name
        'alamat' => 'required|string',
        'jabatan' => 'required|string|in:Kepala Teknisi,Teknisi,Admin',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6'
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    $userRole = 'user';
    if ($request->jabatan == 'Admin') {
        $userRole = 'admin';
    } elseif ($request->jabatan == 'Teknisi' || $request->jabatan == 'Kepala Teknisi') {
        $userRole = 'teknisi';
    }

    User::create([
        'id_karyawan' => $request->id_karyawan,
        'name' => $request->name, // Ubah ini dari nama_karyawan ke name
        'alamat' => $request->alamat,
        'jabatan' => $request->jabatan,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $userRole,
    ]);

    return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil ditambahkan');
}

    public function show($id)
    {
        $user = Auth::user();
        $karyawan = User::findOrFail($id);
        $perbaikanList = [];

        if ($karyawan->jabatan == 'Teknisi' || $karyawan->jabatan == 'Kepala Teknisi') {
            $perbaikanList = Perbaikan::where('user_id', $karyawan->id)
                ->orderBy('tanggal_perbaikan', 'desc')
                ->get();
        }

        return view('kepala_toko.detail_karyawan', compact('user', 'karyawan', 'perbaikanList'));
    }

    public function edit($id)
    {
        $user = Auth::user();
        $karyawan = User::findOrFail($id);

        return view('kepala_toko.edit_karyawan', compact('user', 'karyawan'));
    }

    public function update(Request $request, $id)
{
    $karyawan = User::findOrFail($id);

    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255', // Ubah ini
        'alamat' => 'required|string',
        'jabatan' => 'required|string|in:Kepala Teknisi,Teknisi,Admin',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    $userRole = 'user';
    if ($request->jabatan == 'Admin') {
        $userRole = 'admin';
    } elseif ($request->jabatan == 'Teknisi' || $request->jabatan == 'Kepala Teknisi') {
        $userRole = 'teknisi';
    }

    $karyawan->update([
        'name' => $request->name, // Ubah ini
        'alamat' => $request->alamat,
        'jabatan' => $request->jabatan,
        'role' => $userRole,
    ]);

    return redirect()->route('karyawan.index')->with('success', 'Data karyawan berhasil diperbarui');
}

    public function destroy($id)
    {
        $karyawan = User::findOrFail($id);
        $karyawan->delete();

        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil dihapus');
    }
}
