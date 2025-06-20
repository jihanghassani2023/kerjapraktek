<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Perbaikan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // CRUD Methods for User Management
    public function index()
    {
        $user = Auth::user();
        $users = User::whereIn('role', ['admin', 'teknisi', 'kepala teknisi', 'kepala_toko'])->get();
        return view('kepala_toko.data_user', compact('users', 'user'));
    }

    public function create()
    {
        $user = Auth::user();
        $lastId = User::max('id') ?? 1000;
        $nextId = $lastId + 1;

        $formattedId = $nextId;
        return view('kepala_toko.tambah_user', compact('user', 'formattedId'));
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

        // Determine user role based on jabatan
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

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan');
    }

    public function show($id)
    {
        $user = Auth::user();
        $userData = User::findOrFail($id);
        $perbaikanList = [];

        if ($userData->jabatan == 'Teknisi' || $userData->jabatan == 'Kepala Teknisi') {
            $perbaikanList = Perbaikan::where('user_id', $userData->id)
                ->with('detail')
                ->orderBy('tanggal_perbaikan', 'desc')
                ->get();
        }

        return view('kepala_toko.detail_user', compact('user', 'userData', 'perbaikanList'));
    }

    public function edit($id)
    {
        $user = Auth::user();
        $userData = User::findOrFail($id);

        return view('kepala_toko.edit_user', compact('user', 'userData'));
    }

    public function update(Request $request, $id)
    {
        $userData = User::findOrFail($id);

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

        // Determine user role based on jabatan
        $userRole = 'user';
        if ($request->jabatan == 'Admin') {
            $userRole = 'admin';
        } elseif ($request->jabatan == 'Kepala Toko') {
            $userRole = 'kepala_toko';
        } elseif ($request->jabatan == 'Teknisi' || $request->jabatan == 'Kepala Teknisi') {
            $userRole = 'teknisi';
        }

        $userData->update([
            'name' => $request->name,
            'alamat' => $request->alamat,
            'jabatan' => $request->jabatan,
            'role' => $userRole,
        ]);

        return redirect()->route('user.index')->with('success', 'Data user berhasil diperbarui');
    }

    public function destroy($id)
    {
        $userData = User::findOrFail($id);
        $userData->delete();

        return redirect()->route('user.index')->with('success', 'User berhasil dihapus');
    }

    // Password Update Method
    public function updatePassword(Request $request, $userId)
    {
        // Validasi input password
        $request->validate([
            'new_password' => 'required|string|min:8|max:100|confirmed',
        ]);

        // Mencari pengguna berdasarkan ID
        $userData = User::find($userId);

        // Periksa apakah pengguna ada
        if (!$userData) {
            return redirect()->back()->withErrors(['user' => 'Pengguna tidak ditemukan.']);
        }

        // Meng-hash password baru dan menyimpannya
        $userData->password = Hash::make($request->new_password);
        $userData->save();

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}
