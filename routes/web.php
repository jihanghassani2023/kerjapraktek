<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\PerbaikanController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TrackingController;
use Illuminate\Support\Facades\Auth;

// Halaman utama adalah tracking
Route::get('/', [TrackingController::class, 'index'])->name('tracking');

// API untuk tracking
Route::get('/api/tracking/{kode}', [TrackingController::class, 'search']);

// Auth routes
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

    // Admin routes
    Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
        Route::get('/dashboard', function () {
            $user = Auth::user();
            return view('admin.dashboard', compact('user'));
        })->name('dashboard');
        
        // Tambahkan rute untuk transaksi admin
        Route::get('/transaksi', [AdminController::class, 'transaksi'])->name('transaksi');
        Route::get('/transaksi/{id}', [AdminController::class, 'showTransaksi'])->name('transaksi.show');
    });

    // Kepala toko routes
    Route::get('/kepala-toko/dashboard', [TransaksiController::class, 'dashboard'])->name('kepala-toko.dashboard');
    Route::get('/kepala-toko/transaksi', [TransaksiController::class, 'index'])->name('kepala-toko.transaksi');
    
    // Transaksi routes for kepala toko
    Route::prefix('transaksi')->name('transaksi.')->middleware(['auth'])->group(function () {
        Route::get('/', [TransaksiController::class, 'index'])->name('index');
        Route::get('/export', [TransaksiController::class, 'export'])->name('export');
        Route::get('/{id}', [TransaksiController::class, 'show'])->name('show')->where('id', '[0-9]+');
    });

    // Karyawan routes - for kepala toko
    Route::middleware(['auth'])->group(function () {
        Route::resource('karyawan', KaryawanController::class);
    });

    // Teknisi routes
    Route::prefix('teknisi')->middleware(['auth'])->group(function () {
        // Dashboard
        Route::get('/dashboard', [PerbaikanController::class, 'index'])->name('teknisi.dashboard');
        
        // Progress page
        Route::get('/progress', [PerbaikanController::class, 'progress'])->name('teknisi.progress');
        
        // Laporan page
        Route::get('/laporan', [PerbaikanController::class, 'laporan'])->name('teknisi.laporan');
        
        // Perbaikan routes
        Route::get('/perbaikan/create', [PerbaikanController::class, 'create'])->name('perbaikan.create');
        Route::post('/perbaikan', [PerbaikanController::class, 'store'])->name('perbaikan.store');
        Route::get('/perbaikan/{id}', [PerbaikanController::class, 'show'])->name('perbaikan.show');
        Route::get('/perbaikan/{id}/edit', [PerbaikanController::class, 'edit'])->name('perbaikan.edit');
        Route::put('/perbaikan/{id}', [PerbaikanController::class, 'update'])->name('perbaikan.update');
        Route::delete('/perbaikan/{id}', [PerbaikanController::class, 'destroy'])->name('perbaikan.destroy');
        
        // Generate key for repairs
        Route::get('/generate-key', [PerbaikanController::class, 'generateKey'])->name('perbaikan.generate-key');
        
        // Update status with AJAX
        Route::put('/perbaikan/{id}/status', [PerbaikanController::class, 'updateStatus'])->name('perbaikan.update-status');
        
        // Confirm status change
        Route::get('/perbaikan/{id}/confirm-status/{status}', [PerbaikanController::class, 'confirmStatus'])->name('perbaikan.confirm-status');
    });
});