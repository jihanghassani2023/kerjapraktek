<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\PerbaikanController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ==========================================
// PUBLIC ROUTES (NO AUTH REQUIRED)
// ==========================================

// Route halaman tracking untuk pelanggan
Route::get('/', [TrackingController::class, 'index'])->name('tracking.index');
Route::post('/check', [TrackingController::class, 'check'])->name('tracking.check');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==========================================
// AUTHENTICATED ROUTES
// ==========================================

Route::middleware(['auth'])->group(function () {

    // ==========================================
    // DASHBOARD REDIRECT ROUTE
    // ==========================================
    Route::get('/dashboard', function () {
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

    // ==========================================
    // SEARCH SUGGESTIONS API (SHARED)
    // ==========================================
    Route::get('/search/suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');

    // ==========================================
    // ADMIN ROUTES
    // ==========================================
    Route::prefix('admin')->name('admin.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Search functionality
        Route::get('/search', [AdminController::class, 'search'])->name('search');

        // Transaksi management
        Route::get('/transaksi', [AdminController::class, 'transaksi'])->name('transaksi');
        Route::get('/transaksi/export', [AdminController::class, 'exportTransaksi'])->name('transaksi.export');
        Route::get('/transaksi/{id}', [AdminController::class, 'showTransaksi'])->name('transaksi.show');
        Route::put('/transaksi/{id}/status', [AdminController::class, 'updateStatus'])->name('transaksi.update-status');

        // Pelanggan management
        Route::get('/pelanggan', [AdminController::class, 'pelanggan'])->name('pelanggan');
        Route::get('/pelanggan/create', [AdminController::class, 'createPelanggan'])->name('pelanggan.create');
        Route::post('/pelanggan', [AdminController::class, 'storePelanggan'])->name('pelanggan.store');
        Route::get('/pelanggan/{id}/edit', [AdminController::class, 'editPelanggan'])->name('pelanggan.edit');
        Route::put('/pelanggan/{id}', [AdminController::class, 'updatePelanggan'])->name('pelanggan.update');
        Route::delete('/pelanggan/{id}', [AdminController::class, 'destroyPelanggan'])->name('pelanggan.destroy');
        Route::get('/pelanggan/{id}/edit-pelanggan', [AdminController::class, 'editPelanggan'])->name('perbaikan.edit-pelanggan');

        // Perbaikan management (Admin only create, not edit)
        Route::get('/perbaikan/create', [AdminController::class, 'createPerbaikan'])->name('perbaikan.create');
        Route::post('/perbaikan', [AdminController::class, 'storePerbaikan'])->name('perbaikan.store');
        Route::put('/perbaikan/{id}/status', [AdminController::class, 'updateStatus'])->name('perbaikan.update-status');

        // API routes
        Route::get('/api/customers', [AdminController::class, 'getCustomers'])->name('api.customers');
    });

    // ==========================================
    // KEPALA TOKO ROUTES
    // ==========================================
    Route::prefix('kepala-toko')->name('kepala-toko.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [LaporanController::class, 'dashboard'])->name('dashboard');

        // Search functionality
        Route::get('/search', [AdminController::class, 'search'])->name('search');
    });

    // ==========================================
    // LAPORAN ROUTES (KEPALA TOKO)
    // ==========================================
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        Route::get('/export', [LaporanController::class, 'export'])->name('export');
        Route::get('/{id}', [LaporanController::class, 'show'])
            ->name('show')
            ->where('id', 'MG\d{9}'); // Constraint untuk format ID MG + 9 digit
    });

    // ==========================================
    // USER MANAGEMENT ROUTES (KEPALA TOKO)
    // ==========================================
    Route::resource('user', UserController::class);

    // ==========================================
    // TEKNISI ROUTES
    // ==========================================
    Route::prefix('teknisi')->name('teknisi.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [PerbaikanController::class, 'index'])->name('dashboard');

        // Search functionality
        Route::get('/search', [AdminController::class, 'search'])->name('search');

        // Laporan
        Route::get('/laporan', [PerbaikanController::class, 'laporan'])->name('laporan');

        // Perbaikan management
        Route::get('/perbaikan/{id}', [PerbaikanController::class, 'show'])->name('perbaikan.show');
        Route::get('/perbaikan/{id}/edit', [PerbaikanController::class, 'edit'])->name('perbaikan.edit');
        Route::put('/perbaikan/{id}', [PerbaikanController::class, 'update'])->name('perbaikan.update');
        Route::post('/perbaikan/{id}/add-process', [PerbaikanController::class, 'addProcessStep'])->name('perbaikan.add-process');

        // Status management
        Route::get('/perbaikan/{id}/confirm-status/{status}', [PerbaikanController::class, 'confirmStatus'])->name('perbaikan.confirm-status');
    });

    // ==========================================
    // SHARED PERBAIKAN ROUTES
    // ==========================================
    // Unified route for status updates (used by both admin and teknisi)
    Route::put('/perbaikan/{id}/status', [PerbaikanController::class, 'updateStatus'])->name('perbaikan.update-status');
});

// ==========================================
// ROUTE ALIASES & REDIRECTS
// ==========================================

// Redirect old routes if needed
Route::redirect('/admin', '/admin/dashboard');
Route::redirect('/kepala-toko', '/kepala-toko/dashboard');
Route::redirect('/teknisi', '/teknisi/dashboard');
