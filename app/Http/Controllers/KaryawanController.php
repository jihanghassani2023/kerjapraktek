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
        $karyawan = User::whereIn('role', ['admin', 'teknisi', 'kepala teknisi', 'kepala_toko'])->get();
        return view('kepala_toko.data_karyawan', compact('karyawan', 'user'));
    }

    public function create()
    {
        $user = Auth::user();
        $lastId = User::max('id') ?? 1000;
        $nextId = $lastId + 1;

        $formattedId = $nextId;
        return view('kepala_toko.tambah_karyawan', compact('user', 'formattedId'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'alamat' => 'required|string',
            'jabatan' => 'required|string|max:50|in:Kepala Toko,Kepala Teknisi,Teknisi,Admin',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => 'required|min:6|max:100'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $userRole = 'user';
        if ($request->jabatan == 'Admin') {
            $userRole = 'admin';
        } elseif ($request->jabatan == 'Kepala Toko') {
            $userRole = 'kepala_toko';
        } elseif ($request->jabatan == 'Teknisi' || $request->jabatan == 'Kepala Teknisi') {
            $userRole = 'teknisi';
        }

        User::create([
            'name' => $request->name,
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
                ->with('detail')
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
            'name' => 'required|string|max:255',
            'alamat' => 'required|string',
            'jabatan' => 'required|string|in:Kepala Toko,Kepala Teknisi,Teknisi,Admin',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $userRole = 'user';
        if ($request->jabatan == 'Admin') {
            $userRole = 'admin';
        } elseif ($request->jabatan == 'Kepala Toko') {
            $userRole = 'kepala_toko';
        } elseif ($request->jabatan == 'Teknisi' || $request->jabatan == 'Kepala Teknisi') {
            $userRole = 'teknisi';
        }

        $karyawan->update([
            'name' => $request->name,
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
