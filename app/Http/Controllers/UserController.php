<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function updatePassword(Request $request, $userId)
    {
        // Validasi input password
        $request->validate([
            'new_password' => 'required|string|min:8|max:100|confirmed', // Validasi password minimal 8 karakter dan konfirmasi
        ]);

        // Mencari pengguna berdasarkan ID
        $user = User::find($userId);

        // Periksa apakah pengguna ada
        if (!$user) {
            return redirect()->back()->withErrors(['user' => 'Pengguna tidak ditemukan.']);
        }

        // Meng-hash password baru dan menyimpannya
        $user->password = Hash::make($request->new_password); // Menggunakan Hash::make() untuk meng-hash password
        $user->save();

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}
