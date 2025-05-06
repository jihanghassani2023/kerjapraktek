<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('dashboard', compact('user'));
    }

    public function users()
    {
        // Admin function to manage users
        return view('admin.users');
    }

    public function reports()
    {
        // Kepala Toko function to view reports
        return view('kepala.reports');
    }

    public function tasks()
    {
        // Teknisi function to manage tasks
        return view('teknisi.tasks');
    }
}
