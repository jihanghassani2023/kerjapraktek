<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

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

    public function adminDashboard()
    {
        $user = Auth::user();
        return view('admin.dashboard', compact('user'));
    }

    public function kepalaDashboard()
    {
        $user = Auth::user();
        return view('kepala_toko.dashboard', compact('user'));
    }

    public function teknisiDashboard()
    {
        $user = Auth::user();
        return view('teknisi.dashboard', compact('user'));
    }
}
