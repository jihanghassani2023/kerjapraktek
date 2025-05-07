<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard routes
Route::middleware(['auth'])->group(function () {
    // Rute default dashboard yang akan melakukan redirect sesuai role
    Route::get('/dashboard', function () {
        // Menggunakan Auth::user()->role langsung
        $role = Auth::user()->role;
        
        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($role === 'kepala_toko') {
            return redirect()->route('kepala-toko.dashboard');
        } elseif ($role === 'teknisi') {
            return redirect()->route('teknisi.dashboard');
        } else {
            return redirect()->route('login');
        }
    })->name('dashboard');

    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/kepala-toko/dashboard', function () {
        $user = Auth::user(); // Tambahkan ini
        return view('kepala_toko.dashboard', ['user' => $user]); // Kirim user ke view
    })->name('kepala-toko.dashboard');

    Route::get('/teknisi/dashboard', function () {
        return view('teknisi.dashboard');
    })->name('teknisi.dashboard');
});