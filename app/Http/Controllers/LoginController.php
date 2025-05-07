<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Middleware wajib login
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Menentukan redirect ke dashboard berdasarkan role
    public function index()
    {
        $user = Auth::user();

        if ($user->role == 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role == 'kepala_toko') {
            return redirect()->route('kepala-toko.dashboard');
        } elseif ($user->role == 'teknisi') {
            return redirect()->route('teknisi.dashboard');
        }

        return redirect()->route('login');
    }

    // Dashboard untuk admin
    public function adminDashboard()
    {
        $user = Auth::user();
        return view('admin.dashboard', compact('user'));
    }

    // Dashboard untuk kepala toko
    public function kepalaDashboard()
    {
        $user = Auth::user();
        return view('kepala_toko.dashboard', compact('user'));
    }

    // Dashboard untuk teknisi
    public function teknisiDashboard()
    {
        $user = Auth::user();
        return view('teknisi.dashboard', compact('user'));
    }
}
