<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\PerbaikanController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Auth;

// Route halaman tracking untuk pelanggan
Route::get('/', [TrackingController::class, 'index'])->name('tracking.index');
Route::post('/check', [TrackingController::class, 'check'])->name('tracking.check');

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
    // Admin routes
    Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Search functionality route
        Route::get('/search', [AdminController::class, 'search'])->name('search');

        // Add this new route for admin to update repair status
        Route::put('/perbaikan/{id}/status', [AdminController::class, 'updateStatus'])->name('perbaikan.update-status');

        Route::get('/pelanggan/{id}/edit-pelanggan', [AdminController::class, 'editPelanggan'])->name('perbaikan.edit-pelanggan');

        // Transaksi admin routes
        Route::get('/transaksi', [AdminController::class, 'transaksi'])->name('transaksi');

        // PINDAHKAN ROUTE EXPORT KE ATAS SEBELUM ROUTE DENGAN PARAMETER {id}
        Route::get('/transaksi/export', [AdminController::class, 'exportTransaksi'])->name('transaksi.export');

        // Route dengan parameter {id} di bawah
        Route::get('/transaksi/{id}', [AdminController::class, 'showTransaksi'])->name('transaksi.show');
        Route::put('/transaksi/{id}/status', [AdminController::class, 'updateStatus'])->name('transaksi.update-status');

        // Pelanggan management
        Route::get('/pelanggan', [AdminController::class, 'pelanggan'])->name('pelanggan');
        Route::get('/pelanggan/create', [AdminController::class, 'createPelanggan'])->name('pelanggan.create');
        Route::post('/pelanggan', [AdminController::class, 'storePelanggan'])->name('pelanggan.store');
        Route::get('/pelanggan/{id}/edit', [AdminController::class, 'editPelanggan'])->name('pelanggan.edit');
        Route::put('/pelanggan/{id}', [AdminController::class, 'updatePelanggan'])->name('pelanggan.update');
        Route::delete('/pelanggan/{id}', [AdminController::class, 'destroyPelanggan'])->name('pelanggan.destroy');

        // Perbaikan management - ADMIN HANYA BISA CREATE, TIDAK BISA EDIT
        Route::get('/perbaikan/create', [AdminController::class, 'createPerbaikan'])->name('perbaikan.create');
        Route::post('/perbaikan', [AdminController::class, 'storePerbaikan'])->name('perbaikan.store');
    });

    // Search suggestions API route (outside of admin group)
    Route::get('/search/suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');

    // Kepala toko routes
    Route::get('/kepala-toko/dashboard', [TransaksiController::class, 'dashboard'])->name('kepala-toko.dashboard');

    // Transaksi routes for kepala toko
    Route::prefix('transaksi')->name('transaksi.')->middleware(['auth'])->group(function () {
        Route::get('/', [TransaksiController::class, 'index'])->name('index');
        Route::get('/export', [TransaksiController::class, 'export'])->name('export');
        Route::get('/{id}', [TransaksiController::class, 'show'])->name('show')->where('id', 'MG\d{8}');
    });

    // Karyawan routes - for kepala toko
    Route::middleware(['auth'])->group(function () {
        Route::resource('karyawan', KaryawanController::class);
    });

    // Teknisi routes
    Route::prefix('teknisi')->middleware(['auth'])->group(function () {
        // Dashboard
        Route::get('/dashboard', [PerbaikanController::class, 'index'])->name('teknisi.dashboard');

        Route::post('/perbaikan/{id}/add-process', [PerbaikanController::class, 'addProcessStep'])->name('perbaikan.add-process');

        // Laporan page
        Route::get('/laporan', [PerbaikanController::class, 'laporan'])->name('teknisi.laporan');

        // Export laporan route - mengarah ke method yang benar
        Route::get('/laporan/export', [PerbaikanController::class, 'exportLaporan'])->name('laporan.export');

        // Perbaikan routes (hanya untuk view, edit, dan update status)
        Route::get('/perbaikan/{id}', [PerbaikanController::class, 'show'])->name('perbaikan.show');
        Route::get('/perbaikan/{id}/edit', [PerbaikanController::class, 'edit'])->name('perbaikan.edit');
        Route::put('/perbaikan/{id}', [PerbaikanController::class, 'update'])->name('perbaikan.update');

        // Confirm status change
        Route::get('/perbaikan/{id}/confirm-status/{status}', [PerbaikanController::class, 'confirmStatus'])->name('perbaikan.confirm-status');
    });

    // Add this to your routes/web.php
    Route::get('/admin/api/customers', [AdminController::class, 'getCustomers'])->name('admin.api.customers');

    // FIX: Unified single route for status updates - this lets both admin and teknisi controllers handle status updates
    Route::put('/perbaikan/{id}/status', [PerbaikanController::class, 'updateStatus'])->name('perbaikan.update-status');
});
